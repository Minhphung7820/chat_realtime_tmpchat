<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupConversation extends Model
{
    use HasFactory;
    protected $table = "group_conversations";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
          'id',
          'leader_id',
          'created_at',
          'updated_at'
    ];
    public function messages()
    {
        return $this->hasMany(Message::class,'groupConversation_id','id');
    }
}
