<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\CategoryCollection;
use App\Models\Category;
use App\Rules\UniqueCategoryTitleRule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Validator;
use Response;

class CategoryController extends Controller
{
    /**
    * Display a listing of the resource
    * @OA\Get(
    *      path="/api/categories",
    *      operationId="categoryList",
    *      tags={"categories"},
    *      summary="Get all categories.",
    *      description="Get all categories.",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\CategoryResource")
    *       ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */
    public function index()
    {
        return CategoryResource::collection(Category::orderBy('id','DESC')->paginate(10));
    }

    /**
    * Store a newly created resource in storage.
    * @OA\Post(
    *      path="/api/category/store",
    *      operationId="storeCategory",
    *      tags={"categories"},
    *      summary="Add a category.",
    *      description="Add a category.",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\CategoryResource")
    *       ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */
    public function store(Request $request)
    {

        // Validate input data
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', new UniqueCategoryTitleRule],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Create new category object and save it to the database
        $title = $request->title;
        $category = new Category();
        $category->title = $title;
        $category->slug = strtolower(implode('_', explode(' ', $category->title)));
        $category->save();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Category created successfully',
                'category' => $category,
            ]
        );
    }

    /**
    * Display the specified resource.
    * @OA\Get(
    *      path="/category/{id}/show",
    *      operationId="categoryDetail",
    *      tags={"categories"},
    *      summary="Get category.",
    *      description="Get category.",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\CategoryResource")
    *       ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */
    public function show(string $id)
    {
        return new CategoryResource(Category::findOrFail($id));
    }

    /**
    * Update the specified resource in storage.
    * @OA\Post(
    *      path="/category/{id}/update",
    *      operationId="categoryUpdate",
    *      tags={"categories"},
    *      summary="Update a category.",
    *      description="Update a category.",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\CategoryResource")
    *       ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validatedData = $request->validate([
            'title' => ['required', 'string', new UniqueCategoryTitleRule],
            'slug' => 'string',
        ]);

        $category->title = $validatedData['title'];
        $category->slug = strtolower(implode('_',explode(' ',$category->title)));
        $category->save();

        return response()->json(['message' => 'Category updated successfully']);
    }

    /**
    * Remove the specified resource from storage.
    * @OA\Post(
    *      path="/category/{id}/destroy",
    *      operationId="categoryDestroy",
    *      tags={"categories"},
    *      summary="Delete a category.",
    *      description="Delete a category.",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\CategoryResource")
    *       ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */
    public function destroy(string $id)
    {
        try{
            $category=Category::where('id', $id)->first();
            if($category){
                $category->delete();
                return Response::json(['success'=>'Category removed successfully !'], 200);
            }else{
                return Response::json(['error'=>'Category not found!'], 404);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'Category belongs to an article.So you cann\'t delete this category!']);
        }  
    }

    /**
    * Search category by keyword
    * @OA\Get(
    *      path="/category/{keyword}/search",
    *      operationId="categorySearch",
    *      tags={"categories"},
    *      summary="Search categories.",
    *      description="Search categories.",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\CategoryResource")
    *       ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */
    public function searchCategory(Request $request) {
        $categories=Category::where('title','LIKE','%'.$request->keyword.'%')
        ->orWhere('slug','LIKE','%'.$request->keyword.'%')
        ->get();
        if(count($categories)==0){
            return Response::json(['message'=>'No category match found !']);
        }else{
            return Response::json($categories);
        }
    }
}
