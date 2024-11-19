<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\GoodsProduct;
use App\Models\GoodsSales;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class GoodDetailController extends Controller
{
    /**
     * Show detail page of product.
     */
    function detail(Request $request, $id)
    {
        $product = GoodsProduct::with(['image', 'variant', 'pic'])->find($id);
        $cart_list = GoodsSales::where('transaction', '=', 0)->get();;
        if ($product == null) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Product is not exist, please select available product.']);
        }
        $data = [
            'sidebar' => 'blaterian',
            'product' => $product,
            'cart_list' => $cart_list,
        ];
        return view('pages.staff.good.detail', $data);
    }

    /**
     * Insert new product image.
     */
    function insertImage(Request $request, $id)
    {
        $request->validate([
            'note' => [$request->input('note') ? 'string' : ''],
            'image' => [File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024), 'required', 'dimensions:ratio=1/1'],
        ]);

        $image = $request->file('image');
        $product = GoodsProduct::with('image')->find($id);
        $total = $product->image->count();
        $image_name = 'PI_' . $id . $total + 1 . '.' . $image->extension();
        // store image file
        $image->storePubliclyAs('images/product/', $image_name, 'public');

        $product_image = ProductImage::create([
            'image' => $image_name,
            'note' => $request->input('note'),
            'product_id' => $id,
        ]);
        if ($product_image) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success add new image to ' . $product->name . '.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to add new image. Please try again or contact admin.']);
        }
    }

    /**
     * Move product image to trash folder.
     */
    function deleteImage(Request $request, $id)
    {
        $image = ProductImage::find($id);
        $product = $image->product;
        // Moving files to delete folder
        Storage::disk('public')->move('images/product/' . $image->image, 'trash/images/product/' . $image->image);

        if ($image->delete()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success delete image from ' . $product->name . '.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to delete image from ' . $product->name . '. Please try again or contact admin.']);
        }
    }

    /**
     * Insert new product variant.
     */
    function insertVariant(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'numeric'],
            'description' => ['required', 'string'],
        ]);
        $variant = ProductVariant::create([
            'product_id' => $id,
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'description' => $request->input('description'),
        ]);

        $product = GoodsProduct::select('name')->find($id);

        if ($variant) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success add new variant to ' . $product->name . '.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to add new image. Please try again or contact admin.']);
        }
    }

    /**
     * Change the transaction status of the product.
     */
    function productStatus(Request $request, $id)
    {
        $product = GoodsProduct::find($id);
        $product->operational_id = $product->operational_id == 0 ? Auth::user()->id : 0;
        if ($product->save()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success ' . ($product->operational_id > 0 ? 'open' : 'close') . ' transaction for ' . $product->name . '.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to ' . ($product->operational_id > 0 ? 'open' : 'close') . ' transaction for ' . $product->name . '. Please try again or contact admin.']);
        }
    }

    /**
     * Change the product variant description.
     */
    function updateDescription(Request $request, $id)
    {
        $variant = ProductVariant::find($id);
        if ($variant->product->pic_id !== Auth::user()->id) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'You are not authorized. Please contact the Person In Charge of this product.']);
        }
        $variant->description = $request->input('update_description');
        if ($variant->save()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success update description for ' . $variant->product->name . ' : ' . $variant->name . '.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to update description for ' . $variant->product->name . ' : ' . $variant->name . '. Please try again or contact admin.']);
        }
    }

    /**
     * Change the product variant stock.
     */
    function updateStock(Request $request, $id)
    {
        $variant = ProductVariant::find($id);
        if ($variant->product->pic_id !== Auth::user()->id) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'You are not authorized. Please contact the Person In Charge of this product.']);
        }
        $variant->stock += $request->input('update_stock');
        if ($variant->save()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success update stock for ' . $variant->product->name .  ' : ' . $variant->name . '.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to update stock for ' . $variant->product->name . ' : ' . $variant->name . '. Please try again or contact admin.']);
        }
    }
}
