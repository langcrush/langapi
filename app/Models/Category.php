<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];
    // To disable timestamps
    // public $timestamps = false;

    public function words(): HasMany
    {
        return $this->hasMany(Word::class);
    }

    public function stats(): HasMany
    {
        return $this->hasMany(Stat::class);
    }
}
