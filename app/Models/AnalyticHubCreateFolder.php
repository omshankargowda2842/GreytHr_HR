<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticHubCreateFolder extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'files'];

    protected $casts = [
        'files' => 'array',
    ];
}