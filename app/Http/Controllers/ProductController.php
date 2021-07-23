<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    function __construct(){
        $this->middleware('auth:api' , ['except' => ['categoryList','List','subCatListItems','detail','subCatList','AllList','SearchList']]);
    }


    function addCategory(Request $request){
        $cat = new Category();

        $cat->categories = $request->name;
        $cat->image = $request->file('image')->store('FasslaImage/Categoriesimg');
        $cat->status = 1;
        $cat->save();

        return $cat;
    }

    function categoryList(){
        $categories = Category::select('id','categories as categories_name','image','status')->get();
        return response([
            'categories'=> $categories,
        ]);

    }

    function addSubCategory(Request $request){
        $sub_cat = new SubCategory();
        $sub_cat->sub_categories = $request->title;
        $sub_cat->categories_id = $request->catid;
        $sub_cat->status = 1;
        $sub_cat->save();

        return $sub_cat;
    }

    function addProduct(Request $request){
        $prod = new Product();
        $prod->categories_id = $request->catid;
        $prod->sub_categories_id = $request->subcat;
        $prod->name = $request->title;
        if($request->fixed != null){
            // $prod->price = ['fixed'=>$request->fixed];
            $prod->price = $request->fixed;
        }
        else if($request->min != null && $request->max != null){
            $prod->price = ['min' => $request->min , 'max' => $request->max];
        }
        // $prod->quantity = $request->qty;
        $images = [];
        $image1 = '';
        $image2 = '';
        $image3 = '';
        $image4 = '';
        if($request->catid == 4){
            if($request->hasFile('image1')){
                $image1 = $request->file('image1')->store('FasslaImage/produstsimg');
                array_push($images,"image1");
            }
            if($request->hasFile('image2')){
                $image2 = $request->file('image2')->store('FasslaImage/produstsimg');
                array_push($images,"image2");
            }
            if($request->hasFile('image3')){
                $image3  = $request->file('image3')->store('FasslaImage/produstsimg');
                array_push($images,"image3");
            }
            if($request->hasFile('image4')){
                $image4  = $request->file('image4')->store('FasslaImage/produstsimg');
                array_push($images,"image4");
            }

            // $prod->image = [
            //     'image1' => $request->file('image1')->store('FasslaImage/produstsimg'),
            //     'image2' => $request->file('image2')->store('FasslaImage/produstsimg'),
            //     'image3' => $request->file('image3')->store('FasslaImage/produstsimg'),
            //     'image4' => $request->file('image4')->store('FasslaImage/produstsimg'),
            // ];
            // dd($images);
            // dd(compact($images));
            $prod->image = compact($images);

        }
        else{
            if($request->hasFile('file')){
                $prod->image = $request->file('file')->store('FasslaImage/produstsimg');
            }
        }
        $prod->short_desc = $request->shortd;
        $prod->best_seller = $request->seller_id;
        $prod->meta_keyword = $request->mkey;
        $prod->quantity = 1;
        $prod->status = 0;


        // $prod->mrp = $request->mrp;
        // $prod->qty_value = $request->qtyvalue;
        // $prod->description = $request->desc;
        // $prod->best_seller = $request->bests;
        // $prod->meta_title = $request->mtitle;
        // $prod->meta_desc = $request->mdesc;
        // $prod->meta_keyword = $request->mkey;
        $prod->save();

        return response()->json(['product'=>$prod]);
    }


    function List($id){

        $products = Product::join('categories','products.categories_id','=','categories.id')
        ->join('sub_categories','products.sub_categories_id','=','sub_categories.id')->where(['products.categories_id'=>$id , 'products.status' => 1])
        ->select('products.id','products.categories_id','categories','products.sub_categories_id','sub_categories.sub_categories','products.name','products.image','products.price','products.quantity','short_desc','products.meta_keyword')
        // ->orderby('id','desc')
        ->get();
        return response([
            'products' => json_decode($products),
        ]);
    }

    function AllList(){

        $products = Product::join('categories','products.categories_id','=','categories.id')
        ->join('sub_categories','products.sub_categories_id','=','sub_categories.id')
        ->select('products.id','products.categories_id','categories','products.sub_categories_id','sub_categories.sub_categories','products.name','products.meta_keyword')
        // ->orderby('id','desc')
        ->get();
        return response([
            'products' => json_decode($products),
        ]);
    }

    function SearchList(Request $request){
        $products = Product::join('categories','products.categories_id','=','categories.id')
        ->join('sub_categories','products.sub_categories_id','=','sub_categories.id')
        ->where('products.meta_keyword','like',"%{$request->search}%")
        ->orwhere("categories.categories","like","%{$request->search}%")
        ->select('products.id','products.categories_id','categories','products.sub_categories_id','sub_categories.sub_categories','products.name','products.image','products.price','products.quantity','short_desc','products.meta_keyword')->get();
        return response([
            'products' => json_decode($products),
        ]);
    }

    function subcatList($id){
        $subcat = SubCategory::where('categories_id',$id)->select('id','categories_id','sub_categories')->get();
        return response()->json([
            'subcategory' => $subcat
        ]);

    }

    function subCatListItems($id){
        $products = Product::join('categories','products.categories_id','=','categories.id')
        ->join('sub_categories','products.sub_categories_id','=','sub_categories.id')->where('products.sub_categories_id',$id)
        ->select('products.id','products.categories_id','categories','products.sub_categories_id','sub_categories.sub_categories','products.name','products.image','products.price','products.quantity','short_desc','products.meta_keyword')
        // ->orderby('id','desc')
        ->get();
        return response()->json([
            'products' => $products,
        ]);
    }

    function detail($id){
        $prod = Product::join('categories','products.categories_id','=','categories.id')
        ->join('sub_categories','products.sub_categories_id','=','sub_categories.id')->where('products.id',$id)
        ->select('products.id','products.categories_id','categories','products.sub_categories_id','sub_categories.sub_categories','products.name','products.image','products.price','products.quantity','short_desc','products.meta_keyword')
        // ->orderby('id','desc')
        ->get();
        return response()->json([
            'products' => $prod,
        ]);

    }

    function deleteProduct(Request $request){
        $result = Product::where('id',$request->id)->delete();
        if($result){
            return response()->json("Product removed Successfully.");
        }
        return response("Something Wrong.");
    }

    function editProduct($id){
        $prod = Product::join('categories','products.categories_id','=','categories.id')
        ->join('sub_categories','products.sub_categories_id','=','sub_categories.id')->where('products.id',$id)
        ->select('products.id','products.categories_id','categories','products.sub_categories_id','sub_categories.sub_categories','products.name','products.image','products.price','products.quantity','short_desc','products.meta_keyword')
        // ->orderby('id','desc')
        ->get();
        return response()->json([
            'products' => $prod,
        ]);
    }

    function updateProduct(Request $request){

        // $prod = new Product();
        $prod = Product::where('id',$request->id)->first();
        // dd($request->all());
        // $prod->name = $request->name;
        $prod->categories_id = $request->catid;
        $prod->sub_categories_id = $request->subcat;
        $prod->name = $request->title;
        if($request->fixed != null){
            $prod->price = ['fixed'=>$request->fixed];
        }
        else if($request->min != null && $request->max != null){
            $prod->price = ['min' => $request->min , 'max' => $request->max];
        }
        // $prod->quantity = $request->qty;
        if($request->catid == 4){
            $prod->image = [
                'image1' => $request->file('image1')->store('FasslaImage/produstsimg'),
                'image2' => $request->file('image2')->store('FasslaImage/produstsimg'),
                'image3' => $request->file('image3')->store('FasslaImage/produstsimg'),
                'image4' => $request->file('image4')->store('FasslaImage/produstsimg'),
            ];
        }
        else{
            $img_arr = explode('/',$prod->image);
            Storage::delete(['/FasslaImage/produstsimg/'. $img_arr[2]]);
            $prod->image = $request->file('file')->store('FasslaImage/produstsimg');
        }
        $prod->short_desc = $request->shortd;
        // $prod->best_seller = $request->seller_id;


        // $prod->mrp = $request->mrp;
        // $prod->qty_value = $request->qtyvalue;
        // $prod->description = $request->desc;
        // $prod->best_seller = $request->bests;
        // $prod->meta_title = $request->mtitle;
        // $prod->meta_desc = $request->mdesc;
        // $prod->meta_keyword = $request->mkey;
        $prod->save();

        return response()->json($prod);
    }

    function sellerProdList($id){
        $prod = Product::join('categories','products.categories_id','=','categories.id')
        ->join('sub_categories','products.sub_categories_id','=','sub_categories.id')->where('products.best_seller',$id)
        ->select('products.id','products.categories_id','categories','products.sub_categories_id','sub_categories.sub_categories','products.name','products.image','products.price','products.quantity','short_desc','products.meta_keyword','products.status')
        // ->orderby('id','desc')
        ->get();
        return response()->json([
            'products' => $prod,
        ]);
    }

    function searchItems(Request $request){

    }
}
