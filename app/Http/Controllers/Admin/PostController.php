<?php

namespace App\Http\Controllers\Admin;


use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Response;

class PostController extends Controller
{
   /* public function __construct()
    {
        $this->authorizeResource(Post::class);
    }*/

   public function index()
   {
       $posts = Post::query()
           ->with('author')
           ->unless(auth()->user()->isAdmin(), function ($q){
               $q->where('user_id', auth()->id());
           })
           ->paginate();
       //$posts = Post::where('user_id', auth()->id())->paginate();
       return view('admin.posts.index', compact('posts'));
   }

   public function create()
   {
       $this->authorize('create', Post::class);

   }

   public function store(Request $request)
   {
       $this->authorize('create', Post::class);
       $request->user()->posts()->create([
           'title' => $request->title,
       ]);

      /* Post::create([
           'user_id' => $request->user()->id,
           'title' => $request->title,
       ]);*/


       return new Response('Post created', 201);
   }

   public function edit(Post $post)
   {
       $this->authorize('update', $post);

       return 'Editar post';
   }

    public function update(Post $post, UpdatePostRequest $request)
    {
      //  $this->authorize('update', $post);
        $post->update([
            'title' => $request->title,
        ]);

        return 'Post updated!';
    }
}
