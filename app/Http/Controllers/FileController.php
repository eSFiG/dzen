<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller {
    public function store(Request $request) {
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $file->move(public_path('files'),$fileName);
        $fileUpload = new File();
        $fileUpload->filename = $fileName;
        $hashname = md5($fileName . time());
        $fileUpload->hashname = $hashname;
        $fileUpload->save();
        return response()->json(['id'=>$fileUpload->id]);
    }

    public function destroy(Request $request) {
        $filename =  $request->get('filename');
        File::where('filename', $filename)->delete();
        $path=public_path().'/files/'.$filename;
        if (file_exists($path)) {
            unlink($path);
        }
        return $filename;
    }
}
