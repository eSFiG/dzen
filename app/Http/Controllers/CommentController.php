<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\File;

class CommentController extends Controller {

    public function save(CommentRequest $request) {
        if ($data = $request->validated()) {
            $comment = new Comment();
            $comment->fill($data)->save();
            $files = File::whereIn('id', $data['ids'])->get();
            foreach ($files as $file) {
                $file->comment_id = $comment->id;
                $file->save();
            }
            $replies_count = 0;
            if($data['parent_id']) {
                $count = Comment::where('id', $data['parent_id'])->withCount('replies')->get();
                $replies_count = $count[0]->replies_count;
            }
            return response()->json(['data' => $comment, 'count' => $replies_count]);
        }
    }

    public function list($name = 'created_at', $dir = 'desc') {
        $sorting = ['user_name', 'email', 'created_at'];
        if(!in_array($name, $sorting)) {
            $name = 'created_at';
        }
        $dir_p = $dir;
        $dir = $dir == 'desc' ? 'asc' : 'desc';
        $pagination = Comment::where('parent_id', '=', null)->paginate(25)->setPath('/sort/'.$name.'/'.$dir_p);
        $sorted = $pagination->sortBy([[$name, $dir]])->values()->all();
        return view('home', ["comments" => $sorted, "dir" => $dir, "pagination" => $pagination]);
    }

    public function replies(Comment $id, $page) {
        return view('replies', ["comment" => $id, "page" => $page]);
    }

}
