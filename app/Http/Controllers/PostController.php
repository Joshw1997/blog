<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;


class PostController extends Controller
{

// 7 components you should stick to - index, show, create, store, edit, updates, destroy

    public function index()
    {
        //$posts = Post::latest();

        // if (request('search')){
        //     $posts
        //         ->where('title', 'like', '%' . request('search') . '%')
        //         ->orWhere('body', 'like', '%' . request('search') . '%');
        // }
    
        return view('posts.index', [
            'posts' => Post::latest()->filter(
                request([ 'search', 'category', 'author'])
            )->paginate(3)->withQueryString()
            // simplePaginate(3) - previous and next
        ]);
    }

    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post
        ]);
    }
}
