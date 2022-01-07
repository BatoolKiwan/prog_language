<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');//اذا مسجل دخول يعمل يلي بدو ياه
    }

    public function index()
    {
        return Category::all();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $category=new Category();
        $category->name=$request->name;
        $category->slug=Str::slug($request->name,'-');
        $category->save();
        return ['message'=> 'category created','data'=> $category];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
      /*   $request->validate([
            'name' => 'required|min:5|max:255',
        ]); */
        $category->name=$request->name;
        $category->slug=Str::slug($request->name,'-');
        $category->save();
        return ['message'=> 'category updated','data'=> $category];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return ['message'=>'category deleted'];
    }
}
