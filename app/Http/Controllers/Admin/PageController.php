<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Helpers\Helper;

class PageController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->middleware('role:admin');
    }

    public function adminDashboardPage() {
        return view('admin.dashboard');
    }

    public function adminEditProfilePage() {
        $user = Auth::user();
        return view('admin.edit_profile')->with('user', $user);
    }


}
