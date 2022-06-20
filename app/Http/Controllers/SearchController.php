<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Search;

class SearchController extends Controller
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
        $data = NULL;
        $type = 'single';
        $list = '';

        return view('search', compact('data', 'type', 'list'));
    }

    /**
     * Search from email
     *
     * @return \Illuminate\View\View
     */
    public function search_email(Request $request) {
        if(is_null($request->multi_search)) {
            if($request->search_email_type == 1) {
                $email_list = array();
                array_push($email_list, $request->email); 
            } else {
                $email_list = json_decode($request->email);
            } 

            $data_email_list = array();
            $data_email_list['EmailSearchAPI'] = array(
                                                    'company_domain' => 'privci.com',
                                                    'email_list' => $email_list
                                                );
            
            $username = env('API_DEV_NAME');
            $key = env('API_KEY');
            
            $ch = curl_init("https://api.privci.com/enterprise/EmailSearch");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_email_list));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept-Charset: UTF-8')
            );
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $key);

            $data = curl_exec($ch);

            $curl_errno = curl_errno($ch);
            $curl_error = curl_error($ch);

            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($data, 0, $header_size);
            $body = substr($data, $header_size);
            curl_close($ch);

            $result['error'] = '';

            if ($curl_errno > 0) {
                $result['error'] = $curl_error;
            }

            $result['search_info'] = json_decode($body);
            
            return response()->json($result);
        } else {
            $email_list = $request->sel_email;

            return view('search', ['list' => json_encode($email_list), 'type' => 'multi']);
        }
    }

    /**
     * Search from domain
     *
     * @return \Illuminate\View\View
     */
    public function search_domain(Request $request) {
        $data_domain = array();
        $data_domain['DomainSearchAPI'] = array(
                                                'company_domain' => 'privci.com',
                                                'domain' => $request->domain
                                            );
        
        $username = env('API_DEV_NAME');
        $key = env('API_KEY');
        
        $ch = curl_init("https://api.privci.com/enterprise/DomainSearch");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_domain));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept-Charset: UTF-8')
        );
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $key);

        $data = curl_exec($ch);

        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
            
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($data, 0, $header_size);
        $body = substr($data, $header_size);
        curl_close($ch);
        
        $result['error'] = '';

        if ($curl_errno > 0) {
            $result['error'] = $curl_error;
        }

        $result['search_info'] = json_decode($body);
        
        return response()->json($result);
    }
}