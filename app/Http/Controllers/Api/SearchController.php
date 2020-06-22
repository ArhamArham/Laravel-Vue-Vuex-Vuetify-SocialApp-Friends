<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;
class SearchController extends Controller
{
    public function search( Request $request)
    {
 
        $searchterm = $request->input('query');
 
        $user=User::where('name','LIKE','%'.$searchterm.'%')->orWhere('email','LIKE','%'.$searchterm.'%')->with('profile')->get();
        return $user;
    }
}
