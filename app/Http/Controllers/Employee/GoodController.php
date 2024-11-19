<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\GoodsProduct;
use App\Models\GoodsSales;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoodController extends Controller
{
    /**
     * Show product list page.
     */
    function product(Request $request)
    {
        // Retrieve or create session
        $product_session = session('product', ['category' => 'created_at', 'order' => 'desc', 'keyword' => null]);
        // Save session to database
        $request->session()->put('product', $product_session);
        // Product filter
        $product_category = $product_session['category'];
        $product_order = $product_session['order'];
        $product_keyword = $product_session['keyword'];
        $product_list = $product_keyword !== null ?
            GoodsProduct::orderByRaw("
                CASE
                WHEN name = ? THEN 1
                WHEN name LIKE ? THEN 2
                WHEN name LIKE ? THEN 3
                ELSE 4
                END 
            ", [$product_keyword, "$product_keyword%", "%$product_keyword%"])->orderByRaw("
                CASE
                WHEN category = ? THEN 1
                WHEN category LIKE ? THEN 2
                WHEN category LIKE ? THEN 3
                ELSE 4
                END 
            ", [$product_keyword, "$product_keyword%", "%$product_keyword%"])->get()
            : GoodsProduct::orderBy($product_category, $product_order)->get();
        $cart_list = GoodsSales::where('transaction', '=', 0)->with(['order' => ['variant.product']])->get();
        $data = [
            'sidebar' => 'blaterian',
            'filter' => [
                'product' => [
                    'category' => $product_category,
                    'order' => $product_order,
                    'keyword' => $product_keyword,
                ]
            ],
            'product_list' => $product_list,
            'user_list' => User::select('id', 'name')->get(),
            'cart_list' => $cart_list,
        ];
        return view('pages.staff.good.product', $data);
    }

    /**
     * Insert new product.
     */
    function insertProduct(Request $request)
    {
        $request->validate([
            'category' => ['required', 'string'],
            'name' => ['required', 'string'],
            'pic' => ['required', 'numeric'],
        ]);
        $product = GoodsProduct::create([
            'category' => $request->input('category'),
            'name' => $request->input('name'),
            'pic_id' => $request->input('pic'),
        ]);

        // sucees insert
        if ($product) {
            return redirect()->route('good.product')->with('notif', ['type' => 'info', 'message' => 'Success add new product for Goods Item.']);
        } else {
            return redirect()->route('good.product')->with('notif', ['type' => 'warning', 'message' => 'Failed to add new product. Please try again or contact admin.']);
        }
    }

    /**
     * Delete selected product.
     */
    function deleteProduct($id)
    {
        $product = GoodsProduct::with(['variant'])->find($id);
        if ($product->variant->sum('sale')) {
            return  redirect()->route('good.product')->with('notif', ['type' => 'warning', 'message' => 'This product has sales. Please delete the sales of this product first.']);
        }

        foreach ($product->image as $image) {
            // Moving files to delete folder
            Storage::disk('public')->move('images/product/' . $image->image, 'trash/images/product/' . $image->image);
            $image->delete();
        }
        foreach ($product->variant as $variant) {
            $variant->delete();
        }

        if ($product->delete()) {
            return  redirect()->route('good.product')->with('notif', ['type' => 'info', 'message' => 'Success delete ' . $product->name . '.']);
        } else {
            return  redirect()->route('good.product')->with('notif', ['type' => 'warning', 'message' => 'Failed to delete ' . $product->name . '. Please try again or contact admin.']);
        }
    }

    /**
     * Filtering product list order.
     */
    function filterProduct(Request $request)
    {
        $keyword = $request->has('keyword') ? $request->input('keyword') : null;
        $category = !$request->has('keyword') && $request->has('category') ?  $request->input('category') : 'name';
        $order = !$request->has('keyword') && $request->has('order') ?  $request->input('order') : 'asc';
        session()->put('product', ['category' => $category, 'order' => $order, 'keyword' => $keyword,]);
        return redirect()->route('good.product');
    }
}
