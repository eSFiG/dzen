<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

    protected $fillable = [
        'user_name',
        'email',
        'parent_id',
        'text',
    ];

    public function replies() {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

}
