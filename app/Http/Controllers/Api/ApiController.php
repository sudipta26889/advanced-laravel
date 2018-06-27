<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use League\OAuth2\Server\AuthorizationServer;
use Laravel\Passport\PersonalAccessTokenResult;
use App\Helpers\Helper;

class ApiController extends Controller {
    /*
     * HTTP Status Codes
     * Ref: http://www.restapitutorial.com/httpstatuscodes.html
     */
    public $successStatus = 200;
    public $createdStatus = 201;
    public $acceptedStatus = 202;
    public $noContentStatus = 204;
    public $notModifiedStatus = 304;
    public $unauthorisedStatus = 401;
    public $forbiddenStatus = 403;
    public $notFoundStatus = 404;
    public $conflictStatus = 409;
    public $badRequest = 400;
    public $internalServerErrorStatus = 500;
    protected $server;


    public function __construct(Request $request, AuthorizationServer $server) {
        parent::__construct($request);
        $this->server = $server;
    }

    public function returnApiRespose($success=true, $data, $responseStatusCode, $message='') {
        $response = array(
            'success' => false,
            'errors' => $data,
            'message' => $message
        );
        if ($success) {
            $response = array(
                'success' => true,
                'data' => $data,
                'message' => $message
            );
        }
        return response()->json($response, $responseStatusCode);
    }

    public function successResponse($data, $message='', $type='') {
        $responseStatusCode = $this->defaultSuccessfulStatus;
        switch ($type){
            case 'success':
                $responseStatusCode = $this->successStatus;
                break;
            case 'created':
                $responseStatusCode = $this->createdStatus;
                break;
            case 'accepted':
                $responseStatusCode = $this->acceptedStatus;
                break;
            case 'no_content_found':
                $responseStatusCode = $this->noContentStatus;
                break;
            case 'from_cache':
                $responseStatusCode = $this->notModifiedStatus;
                break;
            default:
                $responseStatusCode = $this->defaultSuccessfulStatus;
        }
        return $this->returnApiRespose(true, $data, $responseStatusCode, $message);
    }

    public function errorResponse($error, $message='', $type='') {
        if (empty($error)) {
            $error = new \ArrayObject();
        }
        $responseStatusCode = $this->defaultUnsuccessfulStatus;
        switch ($type){
            case 'unauthorised':
                $responseStatusCode = $this->unauthorisedStatus;
                break;
            case 'bad_request':
                $responseStatusCode = $this->badRequest;
                break;
            case 'forbidden':
                $responseStatusCode = $this->forbiddenStatus;
                break;
            case 'not_found':
                $responseStatusCode = $this->notFoundStatus;
                break;
            case 'conflict':
                $responseStatusCode = $this->conflictStatus;
                break;
            case 'internal_server_error':
                $responseStatusCode = $this->internalServerErrorStatus;
                break;
            default:
                $responseStatusCode = $this->defaultUnsuccessfulStatus;
        }
        return $this->returnApiRespose(false, $error, $responseStatusCode, $message);
    }

    public function makeAuthUserData($user, $request) {
        $data = array();
        if ($user) {
            $user_details = array(
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'phone_country_code' => $user->phone_country_code,
                'picture' => Helper::getImageWithUrl($request, $user->picture),
                'dob' => $user->dob,
                'gender' => $user->gender,
                'entity_id' => $user->entity_id, 
            );

            $data = $user_details;
        }
        return $data;
    }

}