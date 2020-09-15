<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'book_name', 'book_author','book_description','user_id',
    ];
    public function user(){
        return $this->belongsTo("App\User");
    }
}
