<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'user_id', 'category_id', 'reply_count', 'view_count', 'last_reply_user_id', 'order', 'excerpt', 'slug'];

    // 模型关联 topic --> category 一对一
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // 模型关联 topic --> user 一对一
    public function User() {
        return $this->belongsTo(User::class);
    }

}