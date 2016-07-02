<?php 

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\User;
use Gate;

class Controller extends BaseController{

    /**
     * Return a JSON response for success.
     *
     * @param  array  $data
     * @param  string $code
     * @return \Illuminate\Http\JsonResponse
     */
	public function success($data, $code){
		return response()->json(['data' => $data], $code);
	}

    /**
     * Return a JSON response for error.
     *
     * @param  array  $message
     * @param  string $code
     * @return \Illuminate\Http\JsonResponse
     */
	public function error($message, $code){
		return response()->json(['message' => $message], $code);
	}

    /**
     * Check if the user is authorized to perform a given action on a resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array $resource
     * @param  mixed|array $arguments
     * @return boolean
     * @see    https://lumen.laravel.com/docs/authorization 
     */
    protected function authorizeUser(Request $request, $resource, $arguments = []){
    	
    	$user 	 = User::find($this->getUserId());
    	$action	 = $this->getAction($request); 

        // The ability string must match the string defined in App\Providers\AuthServiceProvider\ability()
        $ability = "{$action}-{$resource}";

    	// return $this->authorizeForUser($user, "{$action}-{$resource}", $data);
    	return Gate::forUser($user)->allows($ability, $arguments);
    }

    /**
     * Check if user is authorized.
     *
     * This method will be called by "Authorize" Middleware for every controller.
     * Controller that needs to be authorized must override this method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isAuthorized(Request $request){
        return false;
    }

    /**
     * Get current authorized user id.
     * This method should be called only after validating the access token using OAuthMiddleware Middleware.
     *
     * @return boolean
     */
    protected function getUserId(){
    	return \LucaDegasperi\OAuth2Server\Facades\Authorizer::getResourceOwnerId();
    }

    /**
     * Get the requested action method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getAction(Request $request){
        return explode('@', $request->route()[1]["uses"], 2)[1];
    }

    /**
     * Get the parameters in route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getArgs(Request $request){
        return $request->route()[2];
    }
}
