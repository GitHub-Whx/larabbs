<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    // 模型关联
    public function topic() {
        return $this->belongsTo(Topic::class);;
    }

    // 模型关联
    public function user() {
        return $this->belongsTo(User::class);
    }
}
