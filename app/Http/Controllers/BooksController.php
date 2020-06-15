<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Facades\App\Books;
use Illuminate\support\Collection;

class BooksController extends Controller
{
    //
    public function __construct()
    {}

    /**
     * create the function to book add 
     * 
     * @param $Request $Request
     * @return Illuminate\Http\Response create Book
     */
    public function create(Request $request) {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $book = Books::createBooks($data);
        return response()->json(['message' => 'Book added', 'id' =>$book->id], 201);
    }
}
