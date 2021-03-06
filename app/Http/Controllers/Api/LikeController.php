<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $likes = ProductUser::where('product_id', $id)->get();
        $inc = 0;
        $i = 0;
        foreach ($likes as $like) {
            if ($like->user_id == Auth::user()->id) {
                if ($like->is_like == false) {
                    if ($inc == 0) {
                        $inc = 1;
                        $product->increment('sum_like');
                        $like->is_like = true;
                        $product->save();
                        $like->save();
                    } }
                        $like->is_like = true;
                        $like->save();
                        $i = 1;

            }
        }
        if ($i == 0) {
            $pro = new ProductUser();
            $pro->user_id = Auth::user()->id;
            $pro->is_like = true;
            $pro->product_id = $id;
            $product->increment('sum_like');
            $pro->save();
        }
        return ['sucess add like', 'product' => $product];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
