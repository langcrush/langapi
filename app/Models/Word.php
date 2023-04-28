<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'translation',
        'category_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
