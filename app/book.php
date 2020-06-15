<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Bridge\User;

class book extends Model
{
    /**
     * protected fillable feilds
     * 
     */
    protected $fillable = [
        'name', 'image', 'price', 'Availability', 'Description', 'Ratings', 'Reviews' ,'author_id'
    ];


    /**
     * create the listbooks 
     * 
     * @param $data
     * @return Illuminate\Http\Response Books
     */
        public function CreateBooks($data) {
            
        }
    /**
     * create the function get list of books
     * 
     * @return Illuminate\Http\Response ListofBooks
     */
    public function getListBooks() {

        Cache::forget('books' . Auth::user()->id);
        $books = Cache::remember('books' . Auth::user()->id, (30), function () {
            $bb = Book::with('books')->where('user_id', Auth::user()->id)->get();
            return $bb;
        });
        return $notes;
    }
}
