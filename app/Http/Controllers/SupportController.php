<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Support;
use Auth;

class SupportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $supports = Support::get_supports();
        
        return view('support', compact('supports'));
    }

    public function send_support(Request $request) {
        $data['status'] = 'error';

        $signed_user_id = Auth::user()->id;

        $result = Support::save_support($request, $signed_user_id);

        if($result) {
            $data['status'] = 'success';
        }

        return response()->json($data);
    }

    public function resolve(Request $request) {
        $data['status'] = 'error';

        $sid = $request->sid;

        $result = Support::set_resolved($sid);

        if($result) {
            $data['status'] = 'success';
        }

        return response()->json($data);
    }

    public function del_support(Request $request) {
        $data['status'] = 'error';

        $sid = $request->sid;

        $result = Support::del_support($sid);

        if($result) {
            $data['status'] = 'success';
        }

        return response()->json($data);
    }
}