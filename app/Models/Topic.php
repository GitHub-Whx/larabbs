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

    // 使用了 laravel 的本地作用域技术
    // 只需简单在对应 Eloquent 模型方法前加上一个 scope 前缀，
    // 作用域总是返回 查询构建器

    // 不同的排序处理
    public function scopeWithOrder($query, $order) {
        // 不同的排序，使用不同的数据的读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;

            default:
               $query->recentReplied();
                break;
        }
    }

    // 按最后回复排序
    public function scopeRecentReplied($query) {
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    // 按最新发布(创建时间)排序
    public function scopeRecent($query) {
        return $query->orderBy('created_at', 'desc');
    }

}