<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = "messages";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
          'id',
          'user_id',
          'conversation_id',
          'groupConversation_id ',
          'message',
          'seen',
          'created_at',
          'updated_at'
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function conversation()
    {
        return $this->belongsTo(Conversation::class,'conversation_id','id');
    }
}
