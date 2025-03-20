<?php

namespace Raseldev99\FilamentMessages\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Inbox extends Model
{
    use SoftDeletes;

    protected $table = 'fm_inboxes';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'user_ids'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_ids' => 'array',
        ];
    }

    /**
     * Accessor for the title of the inbox. If the inbox is created by
     * a user, the title will be the name of the user. If the inbox is created
     * by a system, the title should be set while creating the inbox.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function inboxTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->title ?? collect($this->user_ids)->filter(fn ($userId) => $userId != Auth::id())->map(function ($userId) {
                return \App\Models\User::find($userId)?->name;
            })->values()->implode(', ')
        );
    }

    /**
     * Retrieves an attribute representing all messages associated with the inbox.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\Raseldev99\FilamentMessages\Models\Message>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Retrieves the latest message in the inbox.
     *
     * This method fetches the most recent message associated with the inbox by
     * ordering the messages in descending order of creation.
     *
     * @return \Raseldev99\FilamentMessages\Models\Message|null
     */
    public function latestMessage(): Message | null
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Retrieves an attribute representing all users associated with the inbox,
     * excluding the current authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function otherUsers(): Attribute
    {
        return Attribute::make(
            get: fn () => \App\Models\User::whereIn('id', $this->user_ids)->whereNot('id', Auth::id())->get()
        );
    }
}
