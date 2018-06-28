<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use App\Models\VerifyPhone;
use Illuminate\Support\Facades\Auth;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use League\OAuth2\Server\AuthorizationServer;
use Validator;
use App\Helpers\Helper;

class UserController extends ApiController
{
    public function __construct(Request $request, AuthorizationServer $server) {
        parent::__construct($request, $server);
    }
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        $data = null;
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
            'entity_id' => 'required|integer|exists:entities,id',
            'oauth_client_name' => 'required|string'
        ]);
        if ($validator->fails()) {
            $msg = "Enter valid inputs.";
            $error = $validator->errors();
            return parent::errorResponse($error, $msg, 'unauthorised');
        }

        $this->parseRequest($request);

        if(Auth::attempt(['email' => request('email'), 'password' => request('password'), 'entity_id' => request('entity_id')])){
            $user = Auth::user();
            if (!empty($user->api_access_token) && $this->validateToken($request, $user->api_access_token)) {
                $data['auth']['user'] = parent::makeAuthUserData($user, $request);
                $data['auth']['token']['access_token'] = $user->api_access_token;
                $data['auth']['token']['refresh_token'] = $user->api_refresh_token;
            }else{
                $serverResponse = $this->createUserAccessToken($user, request('password'));
                $user->api_access_token = $serverResponse['access_token'];
                $user->api_refresh_token = $serverResponse['refresh_token'];
                $user->save();
                $data['auth']['user'] = parent::makeAuthUserData($user, $request);
                $data['auth']['token']['access_token'] = $serverResponse['access_token'];
                $data['auth']['token']['refresh_token'] = $serverResponse['refresh_token'];
            }
            $msg = "Login successful.";
            return parent::successResponse($data, $msg, 'accepted');
        }
        else{
            $msg = "Incorrect email id or password.";
            $error = array(
                'incorrect_credential' => [$msg]
            );
            return parent::errorResponse($error, $msg, 'unauthorised');
        }
    }

    protected function createUserAccessToken($user, $password) {
        $client_id = $this->defaultClientId;
        $client_secret = $this->defaultClientSecret;
        $serverRequest = (new ServerRequest)->withParsedBody([
            'grant_type' => 'password',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'username' => $user->email,
            'password' => $password,
            'scope' => '*',
            'entity_id' => $this->entity->id
        ]);

        $serverResponse = json_decode($this->server->respondToAccessTokenRequest(
            $serverRequest, new Response
        )->getBody()->__toString(), true);

        return $serverResponse;
    }

    protected function userRefreshToken($refreshToken) {
        $client_id = $this->defaultClientId;
        $client_secret = $this->defaultClientSecret;
        $serverRequest = (new ServerRequest)->withParsedBody([
            'grant_type' => 'refresh_token',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $refreshToken,
            'scope' => '*',
            'entity_id' => $this->entity->id
        ]);
        try {
            $serverResponse = json_decode($this->server->respondToAccessTokenRequest(
                $serverRequest, new Response
            )->getBody()->__toString(), true);
            return $serverResponse;
        } catch (Exception $e) {
            return null;
        }
        
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $data = null;
        $this->parseRequest($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'entity_id' => 'required|integer|exists:entities,id',
            'tnc_accepted' => 'required|boolean',
            'phone' => 'required|integer|digits:10',
            'phone_country_code' => 'required|integer|max:9999',
            'gender' => 'string|in:male,female',
            'dob' => 'date'
        ]);
        $input = $request->all();
        if ($validator->fails()) {
            $msg = "Enter valid inputs.";
            $error = $validator->errors();
            return parent::errorResponse($error, $msg, 'unauthorised');
        }else{
            if (User::where(['email' => $input['email'], 'entity_id' => $input['entity_id']])->exists()) {
                $msg = "Enter valid inputs.";
                $error = array(
                    'email' => ["The email has already been taken."]
                );
                return parent::errorResponse($error, $msg, 'unauthorised');
            }
            if (User::where(['phone' => $input['phone'], 'entity_id' => $input['entity_id']])->exists()) {
                $msg = "Enter valid inputs.";
                $error = array(
                    'phone' => ["The phone has already been taken."]
                );
                return parent::errorResponse($error, $msg, 'unauthorised');
            }
        }
        $rawPassword = $input['password'];
        $input['password'] = bcrypt($rawPassword);
        $user = User::create($input);
        if ($user) {
            $user->assignRole('user');
            $serverResponse = $this->createUserAccessToken($user, $rawPassword);
            $user->api_access_token = $serverResponse['access_token'];
            $user->api_refresh_token = $serverResponse['refresh_token'];
            $user->save();
            $data['auth']['user'] = parent::makeAuthUserData($user, $request);
            $data['auth']['token']['access_token'] = $serverResponse['access_token'];
            $data['auth']['token']['refresh_token'] = $serverResponse['refresh_token'];
            $msg = "Signup successful.";
            return parent::successResponse($data, $msg, 'accepted');
        } else {
            $msg = "Can't Register. Please try again later.";
            $error = array(
                'signup' => [$msg]
            );
            return parent::errorResponse($error, $msg, 'unauthorised');
        }
    }
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request) {
        $data = null;
        $this->parseRequest($request);
        $authUser = parent::makeAuthUserData(Auth::user(), $request);
        $requestUser = $request->user();
        $data = array(
            'authUser' => $authUser, 
            'requestUser' => $requestUser
        );
        $msg = "Got your details.";
        return parent::successResponse($data, $msg, 'success');
    }

    public function validateToken(Request $request, $token) {
        // $client = new \GuzzleHttp\Client([
        //     'base_uri' => $request->getSchemeAndhttpHost(),
        //     'headers' => array(
        //         "Accept: application/json",
        //         "Authorization: Bearer ".$token,
        //         "Cache-Control: no-cache"
        //     )
        // ]);
        // $a = $client->get('/api/validate-token')->getBody()->getContents();
        // dd($a);
        // $link = $request->getSchemeAndhttpHost()."/api/validate-token";
        // $fp = fopen($link, "r");
        // dd($fp);
        // $response = stream_get_contents($fp);
        // dd($response);
        return false;
    }

    public function googleSignIn(Request $request)
    {
        $data = null;
        $this->parseRequest($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'gplus_response' => 'required',
            'entity_id' => 'required|integer|exists:entities,id',
            'tnc_accepted' => 'required|boolean',
            'gender' => 'string|in:male,female',
            'dob' => 'date'
        ]);
        $input = $request->all();
        $defaultPassword = "pass123";
        $input['password'] = bcrypt($defaultPassword);
        $input['set_password_now'] = true;
        $input['gplus_data'] = $input['gplus_response'];
        $gplusToken = $input['gplus_data']['token'];
        $input['gplus_data']['token_for_login'] = $gplusToken; // Overwrite $input['gplus_data']['token_for_login'] so that token always stays within token_for_login key of the json. This will help in search. NEVER DELETE THIS LINE.
        if ($validator->fails()) {
            $msg = "Please check your inputs.";
            $error = $validator->errors();
            return parent::errorResponse($error, $msg, 'unauthorised');
        }else{
            $user = null;
            if (!User::where(['gplus_data->token_for_login' => $input['gplus_data']['token_for_login'], 'entity_id' => $input['entity_id']])->exists() && !User::where(['email' => $input['email'], 'entity_id' => $input['entity_id']])->exists()) {
                // Create User Now
                $user = User::create($input);
                $user->assignRole('user');
                $serverResponse = $this->createUserAccessToken($user, $defaultPassword);
                $user->api_access_token = $serverResponse['access_token'];
                $user->api_refresh_token = $serverResponse['refresh_token'];
                $user->save();
            } else {
                $user = User::where(function ($query) use ($input) {
                    $query->where('gplus_data->token_for_login', $input['gplus_data']['token_for_login'])
                        ->where('entity_id', $input['entity_id']);
                })->orWhere(function($query) use ($input) {
                    $query->where('email', $input['email'])
                        ->where('entity_id', $input['entity_id']); 
                })->first();
            }
            if ($user) {
                $data['auth']['user'] = parent::makeAuthUserData($user, $request);
                $data['auth']['token']['access_token'] = $user->api_access_token;
                $data['auth']['token']['refresh_token'] = $user->api_refresh_token;
                $msg = "Google Signin successful.";
                return parent::successResponse($data, $msg, 'accepted');
            } else {
                $msg = "Can't Register. Please try again later.";
                $error = array(
                    'google_signin' => [$msg]
                );
                return parent::errorResponse($error, $msg, 'unauthorised');
            }
        }
    }
    public function refreshToken(Request $request) {
        $data = null;
        $msg = "";
        $this->parseRequest($request);
        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required'
        ]);
        $input = $request->all();
        $serverResponse = $this->userRefreshToken($input['refresh_token']);
        if (isset($serverResponse['access_token']) && isset($serverResponse['refresh_token'])) {
            if (User::where('api_refresh_token', $input['refresh_token'])->exists()) {
                $user = User::where('api_refresh_token', $input['refresh_token'])->first();
                $user->api_access_token = $serverResponse['access_token'];
                $user->api_refresh_token = $serverResponse['refresh_token'];
                $user->save();
            }
            $data['access_token'] = $serverResponse['access_token'];
            $data['refresh_token'] = $serverResponse['refresh_token'];
            $msg = "Token Refreshed";
            return parent::successResponse($data, $msg, 'accepted');
        }
        $msg = "Can't Refresh token now. Please try again later.";
        $error = array(
            'refresh_token' => [$msg]
        );
        return parent::errorResponse($error, $msg, 'unauthorised');
    }

    public function sendOtp(Request $request) {
        $data = null;
        $msg = "";
        $this->parseRequest($request);
        $validator = Validator::make($request->all(), [
            'phone' => 'required|integer|digits:10',
            'phone_country_code' => 'required|integer|max:9999',
            'entity_id' => 'required|integer|exists:entities,id',
        ]);
        $input = $request->all();
        if ($validator->fails()) {
            $msg = "Please check your inputs.";
            $error = $validator->errors();
            return parent::errorResponse($error, $msg, 'unauthorised');
        }else{
            $uname = env('SMSGATEWAY_UNAME', "inst-BBMHQ");
            $pwd = env('SMSGATEWAY_PASSWD', "bbmhq123");
            $sender = env('SMSGATEWAY_SENDER', "BBMDFO");
            $to = $input['phone_country_code'].$input['phone'];
            $message = "";
            $whereField = [
                'phone' => $input['phone'], 
                'phone_country_code' => $input['phone_country_code'], 
                'entity_id' => $input['entity_id']
            ];
            if (VerifyPhone::where($whereField)->exists()) {
                $verifyPhone = VerifyPhone::where($whereField)->first();
                if ($verifyPhone->phone_verified) {
                    $msg = "Already Verified";
                    $data['otp_sent_to'] = $to;
                    $data['otp_message'] = $msg;
                    return parent::successResponse($data, $msg, 'accepted');
                }
                $otp = $verifyPhone->otp;
                $otpMessage = "Your ".$this->entity->name." Otp is ".$otp;
                $message = urlencode($otpMessage);
            }else{
                $otp = rand ( 1000 , 9999 );
                $otpMessage = "Your ".$this->entity->name." Otp is ".$otp;
                $message = urlencode($otpMessage);

                $verifyPhone = new VerifyPhone();
                $verifyPhone->phone = $input['phone'];
                $verifyPhone->phone_country_code = $input['phone_country_code'];
                $verifyPhone->otp = $otp;
                $verifyPhone->phone_verified = false;
                $verifyPhone->entity_id = $input['entity_id'];
                $verifyPhone->save();
            }
            $link = "http://103.16.101.52/sendsms/bulksms?username=".$uname."&password=".$pwd."&type=0&destination=".$to."&source=".$sender."&message=".$message;
            $fp = fopen($link, "r");
            $response = stream_get_contents($fp);
            $data['otp_sent_to'] = $to;
            $data['otp_message'] = $message;
            $msg = "Otp Sent";
            return parent::successResponse($data, $msg, 'accepted');
        }
    }

    public function verifyOtp(Request $request) {
        $data = null;
        $msg = "";
        $this->parseRequest($request);
        $validator = Validator::make($request->all(), [
            'phone' => 'required|integer|digits:10',
            'phone_country_code' => 'required|integer|max:9999',
            'otp' => 'required|integer|digits:4',
            'entity_id' => 'required|integer|exists:entities,id',
        ]);
        $input = $request->all();
        if ($validator->fails()) {
            $msg = "Please check your inputs.";
            $error = $validator->errors();
            return parent::errorResponse($error, $msg, 'unauthorised');
        }else{
            $whereField = [
                'phone' => $input['phone'], 
                'phone_country_code' => $input['phone_country_code'], 
                'otp' => $input['otp'], 
                'entity_id' => $input['entity_id']
            ];
            if (VerifyPhone::where($whereField)->exists()) {
                $verifyPhone = VerifyPhone::where($whereField)->first();
                $verifyPhone->otp = '';
                $verifyPhone->phone_verified = true;
                $verifyPhone->save();
                $data['phone'] = $input['phone'];
                $data['phone_country_code'] = $input['phone_country_code'];
                $data['phone_verified'] = $verifyPhone->phone_verified;
                $msg = "Otp Verified";
                $whereField = [
                    'phone' => $input['phone'], 
                    'phone_country_code' => $input['phone_country_code'], 
                    'entity_id' => $input['entity_id']
                ];
                if (User::where($whereField)->exists()) {
                    $user = User::where($whereField)->first();
                    $user->phone_verified = true;
                    $user->save();
                }
                return parent::successResponse($data, $msg, 'accepted');
            }else{
                $msg = "Otp didnot match. Can not verify phone number.";
                $error = array(
                    'refresh_token' => [$msg]
                );
                return parent::errorResponse($error, $msg, 'unauthorised');
            }
        }
    }
}
