<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Directory extends Model
{

    use HasFactory;
    protected $fillable = [
        'name',
        'user_id',
        'parent_id',
    ];

    protected function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function parent()
    {
        return $this->belongsTo(Directory::class, 'parent_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'directory_id');
    }
}
