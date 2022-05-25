<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Policy;

class PolicyController extends Controller
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
        $policy = Policy::get_all_policy();
        $faq = Policy::get_all_faqs();
        
        return view('policy', compact('policy', 'faq'));
    }

    public function upload_policy(Request $request) {
        $data['status'] = 'success';
        $data['msg'] = '';

        $result = Policy::save_policy($request);

        if($result) {
            $data['msg'] = "Successfully save policy!";
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'Save policy failed!';
        }

        return response()->json($data);
    }

    public function edit_policy(Request $request) {
        $policy = Policy::get_policy($request->pid);
        $data['policy'] = $policy[0];
        
        return response()->json($data);
    }

    public function delete_policy(Request $request) {
        $data['status'] = 'success';
        $data['msg'] = '';

        $result = Policy::delete_policy($request->pid);

        if($result) {
            $data['msg'] = "Successfully delete policy!";
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'Delete policy failed!';
        }

        return response()->json($data);
    }

    public function add_faq(Request $request) {
        $data['status'] = 'success';
        $data['msg'] = '';

        $result = Policy::add_faq($request);

        if($result) {
            $data['msg'] = "Successfully add new faq!";
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'add faq failed!';
        }

        return response()->json($data);
    }

    public function edit_faq(Request $request) {
        $faq = Policy::get_faq($request->fid);
        $data['faq'] = $faq[0];
        
        return response()->json($data);
    }

    public function delete_faq(Request $request) {
        $data['status'] = 'success';
        $data['msg'] = '';

        $result = Policy::delete_faq($request->fid);

        if($result) {
            $data['msg'] = "Successfully delete faq!";
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'Delete faq failed!';
        }

        return response()->json($data);
    }
}