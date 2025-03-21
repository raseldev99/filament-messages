<?php

namespace Raseldev99\FilamentMessages\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Raseldev99\FilamentMessages\Models\Inbox;

trait HasFilamentMessages
{
    /**
     * Retrieves all conversations for the current user.
     *
     * @return Builder
     */
    public function allConversations(): Builder
    {
        return Inbox::whereJsonContains('user_ids', $this->id)->orderBy('updated_at', 'desc');
    }
}
