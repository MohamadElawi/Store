<?php

namespace App\Http\Controllers\product;

use App\User;
use Carbon\Carbon;
use App\Models\comment;
use App\Models\product;
use App\Models\category;
use Illuminate\Http\Request;
use App\Events\productViewer;
use App\Http\Triats\GeneralTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\validator;

class productController extends Controller
{

    use GeneralTrait;
    public function AddProduct(Request $request){
        //validate 

        $rules =[
            'name'=>'required'|'min:10',
            'address'=>'required',
            'price'=>'required',
            'category'=>'required',
            'count'=>'required',
        ];
        $validator =validator::make($request->all(),$rules);
        if(!$validator){
           $code= $this->returnCodeAccordingToInput($validator);
           return $this->returnValidationError($code,$validator);
        }

        $DBphoto =$this->savaPhoto($request->photo , 'images');

        // save 
        
        product::create ([
            'name'=>$request->name,
            'address'=>$request->address,
            'price'=>$request->price,
            'category'=>$request->category,
            'count'=>$request->count,
            'photo'=>$DBphoto,
            'expiration'=>$request->expiration,
            'user_id'=>$user =Auth::guard('user-api')->user()->id,



        ]);

        return $this->returnSuccessMessage('saved successfully');
    }


     public function GetAllProduct(){
         $allProduct = product::select('id','name','photo')->get();
         if(!$allProduct)
             return $this->returnError('soory , product not found');
         return $this->returnData('product', $allProduct,"معلومات المنتج");
     }

     public function GetProductDetails($product_id){
        $current =new Carbon();
        $productDetails=product::find($product_id);
        if(!$productDetails)
           return $this->returnError('E001','product not found');   
        $dateExpir = new Carbon($productDetails->expiration);
        $dateDiscount30 =$dateExpir->copy()->subDays('30');
        $dateDiscount50 =$dateExpir->copy()->subDays('15');
        $dateDiscount70 =$dateExpir->copy()->subDays('7');
       
        if($current->lt($dateExpir)){
            if($current > $dateDiscount70){
                $newPrice =($productDetails->price)-( $productDetails->price * 0.70) ;
                $productDetails->update(['newPrice'=> $newPrice]);
            }
                
            elseif($current > $dateDiscount50){
                $newPrice = ($productDetails->price)-( $productDetails->price * 0.50) ;
                $productDetails->update(['newPrice'=> $newPrice]);  
            }
            else{
                $newPrice = ($productDetails->price)-( $productDetails->price * 0.30) ;
                $productDetails->update(['newPrice'=> $newPrice]);
            }
                
        }else{
            $productDetails->delete();
            return $this->returnError('E005','product Deleted Becuase ');
        }


         $productDetails=product::with('comments')->find($product_id);
         if(!$productDetails)
            return $this->returnError('E002','product not found');

        $productDetails->increment('views');
          return $this->returnData('product Details' ,$productDetails);
     }


    
    public function GetAllProductwithUser(){
        $product = product::with(['user'=>function ($q){
            $q->select('id','name');
        }])->get();
        return $this->returnData('user', $product);
    }

    public function GetAllProductByUserIdByGetMethod(Request $request){
        $product = product::with(['user'=>function ($q){
            $q->select('id','name');
        }])->find($request->user_id);
        return $this->returnData('user', $product);
    }

    public function GetAllProductByUserIdByPostMethod(Request $request){
        $product = product::with(['user'=>function ($q){
            $q->select('id','name');
        }])->find($request->user_id);
        return $this->returnData('user', $product);
    }

    public function getproduct($user_id){
        $user = User::find($user_id);
        $products =$user->products;
        return $this->returnData('product',$products);
    }

    public function GetUserProducts(){
        $user_id = Auth::guard('user-api')->user()->id;
        $user =user::find($user_id);
        if(!$user)
            return $this->returnError('Some things went wrongs');
        $products =$user->products;
        return $this->returnData('products',$products);
    }

    public function GetProductUser($product_id){
        $product = product::find($product_id);
        $user = $product -> user ;
        return $this->returnData('user',$user);
    }

    public function DeleteProduct($product_id){
        $product = product::find($product_id);
        if(!$product)
                return $this->returnError('E001','product not found');
        $owner = $product->user_id ;
        $user =Auth::guard('user-api')->user()->id; 
           if($owner == $user){
               $product ->delete();
               return $this->returnSuccessMessage('deleted successfully');
           }else
              return $this->returnError('001','sorry You do not have to delete the product ');
         
        }

        public function editProduct(Request $request,$product_id){
            $product = product::find($product_id);
            if(!$product)
                return $this->returnError('E001','product not found');
            $owner = $product->user_id ;
            $user =Auth::guard('user-api')->user()->id; 
               if($owner == $user){
                   $product ->update($request->all());
                   return $this->returnSuccessMessage('updated successfully');
               }else
                  return $this->returnError('001','sorry You do not have to edit the product ');
             
            }

            public function search(Request $request){
                $search_query = $request->search ;
                $product =product::where('name','Like','%'.$search_query.'%')
                ->orWhere('category','Like','%'.$search_query.'%')
                ->orWhere('price','<=',$search_query)->select(['id','name','category','price','photo'])->get();
                return response()->json($product);
            } 

             public function save(Request $request,$folder){
                   return $this->savaPhoto($request->photo , '$folder');
              }

          
            public function GetProductOfCategory($category){
                $products =product::where('category',$category)->select('id','name')->get();
                return $this->returnData('products',$products);
            }

            public function sort($Sort_By){
                $products =product::select(['id','name','category','price','photo'])->get()->SortBy($Sort_By);
                return $this->returnData('products',$products);
            }
            
}
