<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\Entity;
use App\Models\Language;

class Controller extends BaseController
{
    use Helper, AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $entity, $language;

    /*
     * Default setting
     */
    protected $defaultSuccessfulStatus = 200;
    protected $defaultUnsuccessfulStatus = 400;
    protected $defaultClientId;
    protected $defaultClientSecret;

	public function __construct(Request $request) {
		$this->parseRequest($request);
        $this->defaultClientId = \Config::get("credentials.OAUTH_CLIENT");
        $this->defaultClientSecret = \Config::get("credentials.OAUTH_CLIENT_SECRET");
    }

    protected function parseRequest(Request $request) {
    	$this->entity = Entity::find($request->get('entity_id'));
		$this->language = Language::find($request->get('lang_id'));
    }

    protected function returnView($viewName='welcome', $withData=array()) {
    	$withData['entity'] = $this->entity;
        $withData['lang'] = $this->language;
        return view($viewName, $withData);
    	// return Helper::returnView($this->entity, $this->language, $viewName, $withData);
    }

}
