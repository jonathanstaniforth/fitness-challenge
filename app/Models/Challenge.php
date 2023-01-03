<?php

namespace App\Models;

use App\Exceptions\ChallengeException;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     */
    protected array $casts = ['finished_at' => 'datetime',];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['chat_id', 'finished_at', 'metric'];

    /**
     * Indicates if the model should be timestamped.
     */
    protected bool $timestamps = false;

    /**
     * Get the posts for the challenge.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the users for the challenge.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Return where the challenge has finished.
     */
    public function finished(): bool
    {
        return $this->finished_at <= now('UTC');
    }

    /**
     * Cancel the challenge.
     * 
     * The challenge will be deleted from the database as part of cancelling.
     * 
     * @throws ChallengeException If the challenge has finished.
     */
    public function cancel(): void
    {
        if ($this->finished())
        {
            throw new ChallengeException(__('challenge.cancel.finished'));
        }

        $this->delete();
    }
}
