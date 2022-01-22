<?php

namespace App\Http\Triats;

trait ProductTrait{

    public function search($query){
        $search_query = $query ;
                $product =product::where('name','Like','%'. $search_query.'%')
                ->orWhere('category','Like','%'. $search_query.'%')
                ->orWhere('price','Like','%'. $search_query.'%')->get();
                return response()->json($product);
    }






}