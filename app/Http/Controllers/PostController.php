<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    public function page(Request $request)
    {

        return View('pages.blog-page');
    }
    public function listPost(Request $request)
    {


        
        return Post::with('user')->where('user_id', $request->user()->id)->get();

        

    }
    public function createPost(Request $request)
    {



        try {
            $user_id = $request->user()->id; //Authenticated user id

            if ($request->hasFile('img')) {

                //input validation
                $validated = Validator::make(
                    $request->all(),
                    [

                        'title' => 'required|unique:App\Models\Post,title|max:255',
                        'content' => 'required',
                        'excerpt' => 'required',
                        'img' => 'mimes:jpg,jpeg,png'
                    ],
                    [
                        'title' => 'Title is required',

                        'img' => 'Either jpg,png or jpeg allowed'
                    ]
                );

                if ($validated->fails()) {

                    return response()->json(['status' => 'Failed', 'message' => $validated->errors()], 403);
                }



                $image = $request->file('img');
                $file_name = $image->getClientOriginalName();
                $t = time();
                $image_name = "$user_id-$t-$file_name";
                $img_url = "/uploads/post-image/$image_name";


                //upload image in public uploads/post-image folder

                $image->move(public_path('uploads/post-image'), $image_name);
                //save to db
                Post::create([

                    'title' => $request->input('title'),
                    'excerpt' => $request->input('excerpt'),
                    'image' => $img_url,
                    'content' => $request->input('content'),
                    'isPublished' => 0,
                    'user_id' => $user_id

                ]);
            } else {

                echo "Without Image";

                $validated = Validator::make(
                    $request->all(),
                    [

                        'title' => 'required|unique:App\Models\Post,title|max:255',
                        'content' => 'required',
                        'excerpt' => 'required'

                    ],
                    [
                        'title' => 'Title is required',


                    ]
                );

                if ($validated->fails()) {

                    return response()->json(['status' => 'Failed', 'message' => $validated->errors()], 403);
                }


                //save to db
                Post::create([

                    'title' => $request->input('title'),
                    'excerpt' => $request->input('excerpt'),

                    'content' => $request->input('content'),
                    'isPublished' => 0,
                    'user_id' => $user_id

                ]);
            }










            return response()->json([
                "status" => "successfull",
                "message" => "Your response has been submitted"
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                "status" =>   $e,
                "message" => "Request Failed"
            ], 400);
        }
    }



    public function updatePost(Request $request)
    {

        try {

            $user_id = $request->user()->id;
            $post_id = $request->input('id');

            if ($request->hasFile('img')) {

                $validated = Validator::make(
                    $request->all(),
                    [

                        'title' => 'required|unique:App\Models\Post,title|max:255',
                        'content' => 'required',
                        'image' => 'mimes:jpg,jpeg,png'
                    ],
                    [
                        'title' => 'Title is required',

                        'image' => 'Either jpg,png or jpeg allowed'
                    ]
                );

                if ($validated->fails()) {

                    return response()->json(['status' => 'Failed', 'message' => $validated->errors()], 403);
                }



                $user_id = $request->user()->id;
                $image = $request->file('img');
                $file_name = $image->getClientOriginalName();
                $t = time();
                $image_name = "$user_id-$t-$file_name";
                $img_url = "/uploads/post-image/$image_name";


                //upload image in public uploads/post-image folder

                $image->move(public_path('uploads/post-image'), $image_name);

                //Delete Old file

                $filepath = $request->input('file_path');
                File::delete($filepath);


                //update to db
                return Post::where('user_id', $user_id)->where('id', $post_id)->update([

                    'title' => $request->input('title'),
                    'excerpt' => $request->input('excerpt'),
                    'image' => $img_url,
                    'content' => $request->input('content'),
                    'isPublished' => 0,
                    'user_id' => $user_id

                ]);
            } else {

                $validated = Validator::make(
                    $request->all(),
                    [

                        'title' => 'required',
                        'content' => 'required',

                    ],
                    [
                        'title' => 'Title is required',
                    ]
                );

                if ($validated->fails()) {

                    return response()->json(['status' => 'Failed', 'message' => $validated->errors()], 403);
                }
                return Post::where('id', $post_id)->where('user_id', $user_id)->update([
                    'title' => $request->input('title'),
                    'excerpt' => $request->input('excerpt'),

                    'content' => $request->input('content'),
                    'isPublished' => 0,
                    'user_id' => $user_id
                ]);
            }

            return response()->json([
                "status" => "successfull",
                "message" => "Your Post has been updated"
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                "status" => "Failed",
                "message" => $e
            ], 403);
        }
    }

    
    public function deletePost(Request $request)
    {

        $user_id = $request->user()->id;
        $post_id = $request->input('id');
        $filePath = $request->input('file_path');
        if($filePath!=null){
            
            File::delete($filePath);
        }
        
        return Post::where('id', $post_id)->where('user_id', $user_id)->delete();
    }
}
