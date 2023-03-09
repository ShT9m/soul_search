<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\PostTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    const LOCAL_STORAGE_FOLDER = 'public/images/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.profiles.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'body'  => 'required|min:1|max:10000',
            'image' => 'required|file|mimes:jpg,jpeg,png,gif|max:10000',
        ]);

        Post::create([
            'body'    => $request->body,
            'user_id' => Auth::id(),
            'image'   => $this->saveImage($request),
        ]);

        $latest_tag_id = Tag::max('id');
        $new_tag_id    = $latest_tag_id + 1;
        foreach($request->tag as $tag){
            // create data in tags table if it is new
            $db_tags = Tag::get();
            $is_new = true;
            foreach($db_tags as $db_tag){
                if($db_tag->tag === $tag){
                    $is_new = false;
                    // create data in post_tags table
                    PostTag::create([
                        'post_id' => Post::latest()->first()->id,
                        'tag_id'  => $db_tag->id,
                    ]);
                    break;
                }
            }
            if($is_new){
                Tag::create([
                    'tag' => $tag,
                ]);
                // create data in post_tags table
                PostTag::create([
                    'post_id' => Post::latest()->first()->id,
                    'tag_id'  => $new_tag_id,
                ]);
                $new_tag_id++;
            }

        }

        return redirect()->route('profile.index');
    }

    private function saveImage($request){
        // Change the name of the image to Current Time to avoid overwriting.
        $image_name = time() . "." . $request->image->extension();

        // Save the image inside the storage/app/public/images
        $request->image->storeAs(self::LOCAL_STORAGE_FOLDER, $image_name);

        return $image_name;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('users.profiles.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        // if not the owner of the post, redirect to homepage
        if(Auth::user()->id != $post->user->id):
          return redirect()->route('index');
        endif;

        return view('users.profiles.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'body'  => 'required|min:1|max:10000',
            'image' => 'mimes:jpg,jpeg,png,gif|max:10000|file',
        ]);

        // save data to post table
        $post        = Post::find($id);

        Post::where('id', $id)
            ->update([
                'body'    => $request->body,
                'user_id' => Auth::id(),
                'image'   => $this->saveImage($request),
        ]);

        // If there is a new image......
        if($request->image){
            // Delete the previous file from the local storage
            $this->deleteImage($post->image);

            // save data to post table and store image file
            Post::where('id', $id)
            ->update([
                'image'   => $this->saveImage($request),
            ]);
        }

        return redirect()->route('post.show', $id);
    }

    private function deleteImage($image_name)
    {
        $image_path = self::LOCAL_STORAGE_FOLDER . $image_name;

        if(storage::disk('local')->exists($image_path)){
            Storage::disk('local')->delete($image_path);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::where('id', $id);
        $this->deleteImage($post->image);
        $post->delete();

        return redirect()->route('post.show', $id);
    }
}
