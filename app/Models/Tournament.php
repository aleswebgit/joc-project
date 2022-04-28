<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
       'title',
       'slug',
       'description',
       'award',
       'date',
       'plataform',
       'category_id'
    ];
    
    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
