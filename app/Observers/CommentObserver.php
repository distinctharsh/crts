<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    public function created(Comment $comment)
    {
        if (auth()->check()) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($comment)
                ->withProperties(['comment' => $comment->comment])
                ->log('Comment created');
        }
    }

    public function updated(Comment $comment)
    {
        if (auth()->check()) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($comment)
                ->withProperties(['comment' => $comment->comment])
                ->log('Comment updated');
        }
    }
} 