<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

class PageController extends Controller {

	public function __construct(Request $request) {
        parent::__construct($request);
    }

    public function apiPage(Request $request) {
    	$this->parseRequest($request);
        return $this->returnView('website.apiPage');
    }

    public function landingPage(Request $request) {
        return $this->switchPage($request);
    }

    public function switchPage(Request $request) {
        $this->parseRequest($request);
        $siteName = $this->entity->name;
        $returnWithData = array(
            'siteName' => $siteName
        );
        return $this->returnView('welcome', $returnWithData);
    }

    public function changeSite(Request $request) {
    	$entity_id = $request->entity_id;

    	$entity = Entity::first();
    	if(Entity::find($entity_id)->exists()) {
    		$entity = Entity::find($entity_id);
    	}
    	if (Auth::check()) {
    		$user = Auth::user();
        	$entity = $user->entity;
    	}
    	$request->attributes->set('entity_id', $entity->id);
    	session(['entity_id'=>$entity->id]);
    	$this->parseRequest($request);
    	return redirect()->route('landing');
    }

    public function changeLanguage(Request $request) {
    	$lang_id = $request->lang_id;
    	$language = Language::first();
    	if(Language::find($lang_id)->exists()) {
    		$language = Language::find($lang_id);
    	}
    	$request->attributes->set('lang_id', $language->id);
    	session(['lang_id'=>$language->id]);
    	$this->parseRequest($request);
    	return redirect()->route('landing');
    }
}
