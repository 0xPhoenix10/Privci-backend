<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Setting;

class AccountController extends Controller
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
        $settings = Setting::get_settings();
        $setting = $settings[0];

        return view('account', compact('setting'));
    }

    public function save_notification_email(Request $request) {
        $data['status'] = 'error';
        $result = Setting::update_notification_email($request->email);

        if($result) {
            $data['status'] = 'success';
        }

        return response()->json($data);
    }

    public function save_notification_status(Request $request) {
        $data['status'] = 'error';
        $result = Setting::update_notification_status($request->status);

        if($result) {
            $data['status'] = 'success';
        }

        return response()->json($data);
    }

    public function save_email_tracking(Request $request) {
        $data['status'] = 'error';
        $result = Setting::update_tracking_setting($request->tracking);

        if($result) {
            $data['status'] = 'success';
        }

        return response()->json($data);
    }
}