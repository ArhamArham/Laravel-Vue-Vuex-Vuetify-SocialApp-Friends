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
 
        $searchResults = (new Search())
                    ->registerModel(User::class, function(ModelSearchAspect $modelSearchAspect){
                        $modelSearchAspect
                           ->addSearchableAttribute('name') 
                           ->with('profile');
                    })->perform($searchterm);
 		return $searchResults;
                    
    }
}
