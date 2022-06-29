<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Support;
use Auth, Mail;
use App\Mail\EmailSender;

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
            $this->send_email($request->subject, $request->detail, $request->cc);

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

    public function ping(Request $request) {
        $data['status'] = 'success';
        $support = Support::get_one($request->sid);

        $this->send_email($support[0]->subject, $support[0]->detail);
        Support::set_ping($request->sid);
        
        return response()->json($data);
    }

    private function send_email($subject, $detail, $cc = "false") {
        $emailParams = new \stdClass();
        $emailParams->senderName = Auth::user()->name;
        $emailParams->senderEmail = Auth::user()->email;
        $emailParams->receiverName = "Privci Support Team";
        $emailParams->receiverEmail = "enterprise@privci.com";
        $emailParams->subject = $subject;
        $emailParams->detail = $detail;

        if($cc == 'false') {
            Mail::to($emailParams->receiverEmail)->send(new EmailSender($emailParams));
        } else {
            $emailParams->is_cc = true;
            Mail::to($emailParams->receiverEmail)->cc([$emailParams->senderEmail])->send(new EmailSender($emailParams));
        }

        return true;
    }
}