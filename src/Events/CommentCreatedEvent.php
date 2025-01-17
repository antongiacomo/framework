<?php

namespace Taskday\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Taskday\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class CommentCreatedEvent implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * The card that was updated.
     *
     * @var int
     */
    public $commentId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($commentId)
    {
        $this->commentId = $commentId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("App.Models.Comments.{$this->cardId}.Events");
    }

    /**
     * Get the data to broadcast.
     * @return (Model|Collection<mixed, Builder>|Builder|null)[]
     */
    public function broadcastWith()
    {
        return [
            'comment' => Comment::with('creator')->find($this->cardId)
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     * @return string
     */
    public function broadcastAs()
    {
        return 'CommentCreatedEvent';
    }
}
