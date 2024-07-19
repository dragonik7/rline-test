<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{

    use HasFactory;
    protected $fillable = [
        'name',
        'size',
        'uploaded_at',
        'is_public',
        'user_id',
        'directory_id',
        'unique_link',
        'path'
    ];
    protected $casts = [
        'uploaded_at' => 'timestamp',
    ];

    protected function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function directory()
    {
        return $this->belongsTo(Directory::class);
    }
}
