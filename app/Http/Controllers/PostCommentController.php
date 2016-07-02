<?php 

namespace App\Http\Controllers;

use App\Post;
use App\Comment;

use Illuminate\Http\Request;

class PostCommentController extends Controller{

	public function __construct(){
		
		$this->middleware('oauth', ['except' => ['index', 'show']]);
		$this->middleware('authorize:' . __CLASS__, ['except' => ['index', 'show', 'store']]);
	}

	public function index($post_id){

		$post = Post::find($post_id);

		if(!$post){
			return $this->error("The post with {$post_id} doesn't exist", 404);
		}

		$comments = $post->comments;
		return $this->success($comments, 200);
	}

	public function store(Request $request, $post_id){
		
		$post = Post::find($post_id);

		if(!$post){
			return $this->error("The post with {$post_id} doesn't exist", 404);
		}

		$this->validateRequest($request);

		$comment = Comment::create([
				'content' => $request->get('content'),
				'user_id'=> $this->getUserId(),
				'post_id'=> $post_id
			]);

		return $this->success("The comment with id {$comment->id} has been created and assigned to the post with id {$post_id}", 201);
	}

	public function update(Request $request, $post_id, $comment_id){

		$comment 	= Comment::find($comment_id);
		$post 		= Post::find($post_id);

		if(!$comment || !$post){
			return $this->error("The comment with {$comment_id} or the post with id {$post_id} doesn't exist", 404);
		}

		$this->validateRequest($request);

		$comment->content 		= $request->get('content');
		$comment->user_id 		= $this->getUserId();
		$comment->post_id 		= $post_id;

		$comment->save();

		return $this->success("The comment with with id {$comment->id} has been updated", 200);
	}

	public function destroy($post_id, $comment_id){
		
		$comment 	= Comment::find($comment_id);
		$post 		= Post::find($post_id);

		if(!$comment || !$post){
			return $this->error("The comment with {$comment_id} or the post with id {$post_id} doesn't exist", 404);
		}

		if(!$post->comments()->find($comment_id)){
			return $this->error("The comment with id {$comment_id} isn't assigned to the post with id {$post_id}", 409);
		}

		$comment->delete();

		return $this->success("The comment with id {$comment_id} has been removed of the post {$post_id}", 200);
	}

	public function validateRequest(Request $request){

		$rules = [
			'content' => 'required'
		];

		$this->validate($request, $rules);
	}

	public function isAuthorized(Request $request){

		$resource  = "comments";
		$comment   = Comment::find($this->getArgs($request)["comment_id"]);

		return $this->authorizeUser($request, $resource, $comment);
	}
}