<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    use HasFactory;

    protected $casts = [
        'values' => 'array',
    ];

    protected $fillable = ['author_id', 'status', 'values', 'form_id'];

    public function form(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
