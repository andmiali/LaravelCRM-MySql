<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class First extends Model
{
    use HasFactory;
    protected $table = 'first';
    protected $fillable = ['content', 'image'];
}
