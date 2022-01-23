<?php

namespace App\Http\Controllers\product;

use App\Models\like;
use App\Models\comment;
use App\Models\product;
use Illuminate\Http\Request;
use App\Http\Triats\GeneralTrait;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;



class commentController extends Controller
{
    use GeneralTrait;
    public function AddComment(Request $request,$product_id){
        $product =product::find($product_id);
        if(!$product)
            return $this->returnError('E001','product not found');

        $owner =Auth::guard('user-api')->user()->id;
    
         comment::create([
            'body'=>$request->body,
            'product_id'=>$product_id,
            'user_id'=>$owner ,
        ]);
        return $this->returnSuccessMessage('Add comment successfully');

    }
    
    public function updateComment(Request $request,$comment_id){
        $comment = comment ::find($comment_id);
        if(!$comment)
            return $this->returnError('E001','comment not found');
        $comment ->update($request->all());
        return $this->returnSuccessMessage('updated successfully');

    }  

    public function deleteComment($comment_id){
        $comment = comment ::find($comment_id);
        if(!$comment)
            return $this->returnError('E001','comment not found');
        $comment ->delete();
        return $this->returnSuccessMessage('deleted successfully');

    }  

    public function GetAllComment($product_id){
        $product =product::find($product_id);
        if(!$product)
            return $this->returnError('E001','product Not Found');
        $allComment =$product->comments;
        return $this->returnData('comments',$allComment);
    }

    public function storeLike($product_id){
        $product =product::find($product_id);
         if(!$product)
             return $this->returnError('E001','product not found');
        $user =Auth::guard('user-api')->user()->id;
        $exist =$product->likes()->where('user_id', $user )->exists();
        if($exist){
            like::where('user_id',$user)->delete();
        }else{
            like::create([
                'product_id'=>$product_id,
                'user_id'=>$user,
            ]);
        }

            return response()->json(null);
        ########3##
    }
}
