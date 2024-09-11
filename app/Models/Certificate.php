<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'card_id',
        'card_info'
    ];

    protected $casts = [
      'card_info' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
