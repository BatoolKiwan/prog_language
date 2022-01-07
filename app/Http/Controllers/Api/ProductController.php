<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function index()
    {
        //return "fgfh";
        return Product::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        if($request->expirat_date <= Carbon::now()->format('Y-m-d'))
        {return "this product has finished expirat_date";}

        $product = new Product();
        $product->name=$request->name;
        $product->information_comm = $request->information_comm;
        $product->quentity = $request->quentity;
        if ($request->has('image_upload')) {
            $image = $request->image_upload;
            $path = $image->store('product-images', 'public');
            $product->image = $path;
        } else {
            $product->image = $request->image;
        }
        $product->category_id = $request->category_id;
        $product->expirat_date = $request->expirat_date;
        $product->regular_price = $request->regular_price;
        if (Carbon::createFromFormat('Y-m-d', $request->expirat_date)->subDays(30) >= Carbon::now()) {
            $product->price = $request->regular_price - ($request->regular_price * 30 / 100);
        } elseif (Carbon::createFromFormat('Y-m-d', $request->expirat_date)->subDays(15) >= Carbon::now()) {
            $product->price = $request->regular_price - ($request->regular_price * 50 / 100);
        } else $product->price = $request->regular_price - ($request->regular_price * 70 / 100);

        $product->save();

        $prod_user= new ProductUser();
            $prod_user->user_id =Auth::user()->id;
             $prod_user->product_id=$product->id;
             $prod_user->is_user=true;
        $prod_user->save();
        return ['product' => $product,'product_users'=>$prod_user];
    }
    public function comment(Request $request,$id){
       $prod_user= new ProductUser();
          $prod_user->user_id  = Auth::user()->id;
          $prod_user->product_id=  $id;
          $prod_user->comment   = $request->comment;
          $prod_user->save();

       return response()->json('success comment');
    }
    public function showComment($id)
    {
        $comments=ProductUser::where('product_id',$id)->get();
        return ["comments"=>$comments];
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product=Product::findOrFail($id);
        if ($product->expirat_date <= Carbon::now()->format('Y-m-d')) {
            $product->delete();
            return "this product has finshed Expriate Date";
        }
        $product->increment('views');

        return $product;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {

        if($request->expirat_date <= Carbon::now()->format('Y-m-d'))
        {return "this product has finished expirat_date";}
        $product=Product::findOrFail($id);
        if(Auth::user()->id !=$product->user_id) return "the permission deined";
        $product->name = $request->name;
        //$product->slug = Str::slug($request->name, '-');
        $product->information_comm = $request->information_comm;
        $product->quentity = $request->quentity;
        if ($request->has('image_upload')) {
            $image = $request->image_upload;
            $path = $image->store('product-images', 'public');
            $product->image = $path;
        } else {
            $product->image = $request->image;
        }
        $product->category_id = $request->category_id;
       // $product->user_id=Auth::user()->id;
        if (Carbon::createFromFormat('Y-m-d', $product->expirat_date)->subDays(30) >= Carbon::now()) {
            $product->price = $request->price - ($request->price * 30 / 100);
        } elseif (Carbon::createFromFormat('Y-m-d', $product->expirat_date)->subDays(15) >= Carbon::now()) {
            $product->price = $request->price - ($request->price * 50 / 100);
        } else  $product->price = $request->price - ($request->price * 70 / 100);

        $product->save();
        return ['product' => $product];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::findOrFail($id);
        if(Auth::user()->id==$product->user_id){
            $product->delete();
            return "has delete";
        }
        return "the permission deined";
    }

    public function search(Request $request)
    {
        $input =  $request->input;
        $search =  $request->search;
        if ($input == null) {
            return;
        }
        if ($search == "name" or $search == null) {
            $product = Product::where('name', 'like', '%' . $input . '%')->get();
            return  ['product' => $product];
        }
        if ($search == "category") {
            $category = Category::where('name', 'like', '%' . $input . '%')->first()->id;
            $product = Product::where('category_id',$category)->get();
            return  ['products' => $product];
        }

        if ($search === "expirat_date") {

            $product = Product::where("expirat_date", $input)->get();
            return  ['products' => $product];
        }
    }
    public function sort(Request $request){
         $sort=$request->sort;
         return Product::orderBy($sort,'desc')->get();
    }
}
