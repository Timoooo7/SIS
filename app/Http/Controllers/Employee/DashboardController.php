<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Billboard;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'billboard_list' => Billboard::all(),
            'attachment_list' => Attachment::all(),
            'post_list' => Post::with('user')->get('*'),
        ];
        return view('pages.staff.dashboard', $data);
    }


    // Billboard function
    function addBillboard(Request $request)
    {
        $request->flash();
        $request->validate([
            'dashboard_title' => 'required',
            'dashboard_text' => Rule::requiredIf($request->input('typeText') == 'on'),
            'dashboard_image' => [Rule::requiredIf($request->input('typeImage') == 'on'), File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)],
        ]);

        $data = [
            'type' => ($request->input('typeImage') == 'on' ? 1 : 0) + ($request->input('typeImage') == 'on' ? 2 : 0),
            'title' => $request->input('dashboard_title'),
            'text' => $request->input('dashboard_text'),
        ];
        if ($request->input('typeImage') !== 'on' && $request->input('typeText') !== 'on') {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Please choose the dashboard type.']);
        }

        if ($request->input('typeImage') == 'on') {
            if ($request->hasFile('dashboard_image')) {
                $image = $request->file('dashboard_image');
                $image_name =  'Billboard_' . time() . '.' . $image->extension();
                // store image file
                $image->storePubliclyAs('images/billboard/', $image_name, 'public');
                $data += ['image' => $image_name];
            } else {
                return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'The file is empty. Uncheck image if you do not want to use image.']);
            }
        }

        // Save data
        Billboard::create($data);
        return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success add new Billboard.']);
    }

    function removeBillboard($id)
    {
        $billboard = Billboard::find($id);
        if (!$billboard) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Billboard doesn`t exist. Please ask administrator to check billboard id.']);
        }
        // Delete image if exist
        if ($billboard->image) {
            Storage::disk('public')->delete('images/billboard/' . $billboard->image);
        }
        $billboard->delete();

        return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success delete billboard ' . $billboard->name . '.']);
    }

    // Attachment function
    function addAttachment(Request $request)
    {
        $request->flash();
        $input = $request->validate([
            'attachment_type' => 'required|string',
            'attachment_title' => 'required|string|max:255',
            'attachment_link' => ['url:https', Rule::requiredIf($request->input('attachment_type') == 'link'), 'nullable'],
            'attachment_document' => ['file', 'mimes:pdf,docx,png,jpeg,jpg,heic', 'max:5120', Rule::requiredIf($request->input('attachment_type') == 'document'), 'nullable']
        ]);
        $data = [
            'user_id' => Auth::user()->id,
            'title' => $input['attachment_title'],
        ];

        if ($input['attachment_type'] == 'document') {
            $document = $request->file('attachment_document');
            $document_name =  'af_' . time() . '.' . $document->extension();
            // store image file
            $document->storePubliclyAs('document/attachment/', $document_name, 'public');
            $data += [
                'type' => 0,
                'document' => $document_name
            ];
        } elseif ($input['attachment_type'] == 'link') {
            $data += [
                'type' => 1,
                'link' => $input['attachment_link']
            ];
        }

        Attachment::create($data);

        return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success add new ' . $input['attachment_type'] . ' attachment']);
    }

    function removeAttachment($id)
    {
        $attachment = Attachment::find($id);
        if (!$attachment) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Attachment doesn`t existed. Your action can damage the system.']);
        }
        if ($attachment->type == 0) {
            Storage::disk('public')->delete('document/attachment/' . $attachment->document);
        }
        $attachment->delete();
        return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success remove ' . $attachment->title . ' from attachment list.']);
    }

    // Post function
    function addPost(Request $request)
    {
        $request->flash();
        $input = $request->validate([
            'post_text' => 'required|string|max:255',
        ]);
        Post::create([
            'user_id' => Auth::user()->id,
            'text' => $input['post_text'],
            'anonymus' => $request->input('post_username') == 'on' ? true : false,
        ]);

        return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'New post has been added!']);
    }

    function removePost($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Post is not exist. Please try again later or ask admin.']);
        }
        $post->delete();
        return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success remove post.']);
    }
}
