<?php

namespace App\Observers;

use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    // 方法名 saving 即为监听的事件
    public function saving(Topic $topic) {
        // excerpt 是 topic 模型的一个字段
        // meke_excerpt 自定义的辅助方法 在 bootsrap/helpers.php
        $topic->excerpt = make_excerpt($topic->body);
    }
}