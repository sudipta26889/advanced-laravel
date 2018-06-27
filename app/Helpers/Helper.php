<?php
 
namespace App\Helpers;
use Common;
use Carbon\Carbon;

trait Helper {
	public static function now() {
        return Carbon::now();
    }

    public static function getImageWithUrl($request, $imagePath) {
    	if ($imagePath) {
    		return $request->getSchemeAndHttpHost().'/'.$imagePath;
    	}else{
    		return "";
    	}
    }
}

?>