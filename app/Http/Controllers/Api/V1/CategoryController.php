<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Response;
use App\Http\Resources\CategoryResource;
use Illuminate\Validation\Rule;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategoryResource::collection(Category::orderBy('id','DESC')->paginate(10));
    }

    // check title validation
    public function checkTitle(Request $request){
        $validators = Validator::make($request->all(),[
            'title'=>'required|unique:categories',
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    // check slug validation
    public function checkSlug(Request $request){
        $validators = Validator::make($request->all(),[
            'slug'=>'required|unique:categories'
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validators=Validator::make($request->all(),[
            'title'=>'required|unique:categories',
            'slug'=>'required|unique:categories'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $category=new Category();
            $category->title=$request->title;
            $category->slug=strtolower(implode('-',explode(' ',$request->slug)));
            $category->save();
            return Response::json(['success'=>'Category created successfully !']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {       
        if(Category::where('id',$id)->first()){
            return new CategoryResource(Category::findOrFail($id));
        }else{
            return Response::json(['error'=>'Category not found!']);
        }
    }

    /*
    * check edit title validation
    */
    public function checkEditTitle(Request $request){
        $validators = Validator::make($request->all(),[
            'title'=>['required',Rule::unique('categories')->ignore($request->id)]
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    /** 
     * check edit slug validation
     */
    public function checkEditSlug(Request $request){
        $validators = Validator::make($request->all(),[
            'slug'=>['required',Rule::unique('categories')->ignore($request->id)]
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validators=Validator::make($request->all(),[
            'title'=>['required',Rule::unique('categories')->ignore($request->id)],
            'slug'=>['required',Rule::unique('categories')->ignore($request->id)]
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $category=Category::findOrFail($request->id);
            $category->title=$request->title;
            $category->slug=strtolower(implode('-',explode(' ',$request->slug)));
            $category->save();
            return Response::json(['success'=>'Category updated successfully !']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try{
            $category=Category::where('id',$request->id)->first();
            if($category){
                $category->delete();
                return Response::json(['success'=>'Category removed successfully !']);
            }else{
                return Response::json(['error'=>'Category not found!']);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'Category belongs to an article.So you cann\'t delete this category!']);
        }  
    }

    /**
     * Search category by keyword 
     */
    public function searchCategory(Request $request){
        $categories=Category::where('title','LIKE','%'.$request->keyword.'%')->orWhere('slug','LIKE','%'.$request->keyword.'%')->get();
        if(count($categories)==0){
            return Response::json(['message'=>'No category match found !']);
        }else{
            return Response::json($categories);
        }
    }
}
