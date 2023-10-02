<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller {

    public function save(CommentRequest $request) {
        if ($data = $request->validated()) {
            $comment = new Comment();
            $comment->fill($data)->save();
        }
    }

    public function list($name = 'created_at', $dir = 'desc') {
        $sorting = ['user_name', 'email', 'created_at'];
        if(!in_array($name, $sorting)) {
            $name = 'created_at';
        }
        $dir = $dir == 'desc' ? 'asc' : 'desc';
        $pagination = Comment::where('parent_id', '=', null)->paginate(25)->setPath('/sort/'.$name.'/'.$dir);
        $sorted = $pagination->sortBy([[$name, $dir]])->values()->all();
        return view('home', ["comments" => $sorted, "dir" => $dir, "pagination" => $pagination]);
    }

    public function replies(Comment $id) {
        return view('replies', ["comment" => $id]);
    }

}
