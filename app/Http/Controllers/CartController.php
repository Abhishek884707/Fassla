<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CartController extends Controller
{
    function __construct(){
        $this->middleware('auth:api' , ['except' => []]);
    }

    function addToCart(Request $request){
        $validate = Validator::make($request->all(),[
            'userid' => 'required',
            'productid' => 'required',
            'price' => 'required'
        ]);

        if($validate->fails()){
            return response()->json([$validate->errors()]);
        }

        $cartitems = Cart::where(['userid'=>$request->userid,'productid'=>$request->productid])->first();

        if(! $cartitems){
            $subtotal = $request->quantity * $request->price;
            // return Cart::insert([$request->all()]);
            $cart = new Cart();
            $cart->userid = $request->userid;
            $cart->productid = $request->productid;
            $cart->quantity = $request->quantity;
            $cart->subtotal = $subtotal;
            $cart->save();
            return response()->json("added in cart successfully",200);
        }

        return response()->json("Already Added.");

    }

    function getCartItems($id){
        $items =
        // Cart::join('products','products.id','=','cart.productid')
        Product::join('cart','products.id','=','cart.productid')
        ->where('cart.userid',$id)
        ->select('products.id','products.sub_categories_id','products.name','products.image','products.price','cart.subtotal','cart.quantity')
        ->get();

        return response()->json(["products"=>$items]);
    }

    function updateCartItems(Request $request){

        if($request->quantity == 0){
            return Cart::where(['userid'=>$request->userid,'productid'=>$request->productid])->delete();
        }else{
            $subtotal = $request->quantity * $request->price;
            return Cart::where(['userid'=>$request->userid,'productid'=>$request->productid])->update([
                'quantity'=>$request->quantity,
                'subtotal'=>$subtotal
            ]);
        }
    }

    function deleteCartItems(Request $request){
        return Cart::where(['userid'=>$request->userid,'productid'=>$request->productid])->delete();
    }
}
