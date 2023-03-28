<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Http\Resources\Api\V1\CommentResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Response;
use Validator;
use Auth;

class CommentController extends Controller
{
    /**
    * Display a listing of the resource.
    * @OA\Get(
    *      path="/api/comments",
    *      operationId="commentList",
    *      tags={"comments"},
    *      summary="Get all comments by auth user.",
    *      description="Get all comments by auth user.",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\CommentResource")
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
        return CommentResource::collection(Comment::where('user_id',Auth::user()->id)
        ->orderBy('id','DESC')
        ->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
    * @OA\Post(
    *      path="/api/comment/store",
    *      operationId="commentStore",
    *      tags={"comments"},
    *      summary="Add comment as Auth user..",
    *      description="Add comment as auth user.",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\CommentResource")
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
        $validators=Validator::make($request->all(),[
            'comment'=>'required',
            'post'=>'required'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $comment = new Comment();
            $comment->comment = $request->comment;
            $comment->user_id = Auth::user()->id;
            $comment->post_id = $request->post;
            $comment->save();
            return Response::json(['success'=>'Comment created successfully !']);
        }
    }

    /**
    * Display the specified resource.
    * @OA\Get(
    *      path="/api/comment/{id}/show",
    *      operationId="commentDetails",
    *      tags={"comments"},
    *      summary="Get comment details.",
    *      description="Get comment details.",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\CommentResource")
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
        return new CommentResource(Comment::findOrFail($id));
    }

    /**
    * Update the specified resource in storage.
    * @OA\Post(
    *      path="/api/comment/{id}/update",
    *      operationId="commentUpdate",
    *      tags={"comments"},
    *      summary="Update comment",
    *      description="Update comment",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\CommentResource")
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
    public function update(Request $request, string $id)
    {
        $validators=Validator::make($request->all(),[
            'comment'=>'required',
            'post'=>'required'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $comment=Comment::where('id', $id)
            ->where('user_id', Auth::user()
            ->id)
            ->first();
            if($comment){
                $comment->comment=$request->comment;
                $comment->user_id=Auth::user()->id;
                $comment->post_id=$request->post;
                $comment->save();
                return Response::json(['success'=>'Comment updated successfully !']);
            }else{
                return Response::json(['error'=>'Comment not found !']);
            }            
        }
    }

    /**
    * Remove the specified resource from storage.
    * @OA\Post(
    *      path="/api/comment/{id}/destroy",
    *      operationId="commentDestroy",
    *      tags={"comments"},
    *      summary="Delete comment",
    *      description="Delete Comment..",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\CommentResource")
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
            $comment=Comment::where('id',$id)->where('user_id',Auth::user()->id)->first();
            if($comment){
                $comment->delete();
                return Response::json(['success'=>'Comment removed successfully !']);
            }else{
                return Response::json(['error'=>'Comment not found!']);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'Comment belongs to user/article.So you cann\'t delete this comment!']);
        }        
    }
}
