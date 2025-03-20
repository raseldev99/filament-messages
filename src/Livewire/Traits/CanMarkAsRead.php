<?php

namespace Raseldev99\FilamentMessages\Livewire\Traits;

use Illuminate\Support\Facades\Auth;
use Raseldev99\FilamentMessages\Models\Message;

trait CanMarkAsRead
{
    /**
     * Marks all messages in the selected conversation as read by the current user.
     *
     * Iterates over each message in the selected conversation, checking if the
     * current user is not already in the `read_by` list, and updates the message
     * to include the current user in both the `read_by` and `read_at` attributes.
     *
     * @return void
     */
    public function markAsRead(): void
    {
        $this->selectedConversation?->messages()->each(function (Message $message) {
            $message->where(['inbox_id' => $message->inbox_id])->whereJsonDoesntContain('read_by', Auth::id())
                ->update([
                    'read_by' => [
                        ...$message->read_by,
                        Auth::id()
                    ],
                    'read_at' => [
                        ...$message->read_at,
                        now()
                    ]
                ]);
        });
    }
}
