<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatAttachment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'file',
        'message_id'
    ];
}
