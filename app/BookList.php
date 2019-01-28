<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookList extends Model
{
    protected $fillable = [    
        'book_title',
        'book_author'
    ];
}
