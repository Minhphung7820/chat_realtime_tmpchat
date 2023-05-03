<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $table = "conversations";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'created_at',
        'updated_at'
    ];
    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_user', 'user_id', 'conversation_id');
    }
    public function getMessagesBetweenUsers(User $user1, User $user2, $min = null)
    {
        if ($min !== null) {
            return $this->messages()
                ->whereHas('user', function ($query) use ($user1, $user2) {
                    $query->whereIn('id', [$user1->id, $user2->id]);
                })
                ->where('id', '<', $min)
                ->orderBy('created_at', 'desc')
                ->take(15)
                ->get();
        } else {
            return $this->messages()
                ->whereHas('user', function ($query) use ($user1, $user2) {
                    $query->whereIn('id', [$user1->id, $user2->id]);
                })
                ->orderBy('created_at', 'desc')
                ->take(15)
                ->get();
        }
    }
}
