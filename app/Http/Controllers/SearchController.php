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
        return view('search');
    }

    /**
     * Search from email
     *
     * @return \Illuminate\View\View
     */
    public function search_email(Request $request) {
        $email_list = array();
        array_push($email_list, $request->email);

        // $result = Search::get_by_email($email_list);
        
        // $data['result'] = $result;
        // $email_list = ["chupkemi.chana@infosecafrica.co.uk"];
        $data_email_list = array();
        $data_email_list['EmailSearchAPI'] = array(
                                                'company_domain' => 'privci.com',
                                                'email_list' => $email_list
                                            );
        
        $username = "privci_dev";
        $key = "bhAAd231668uhgASt9eeeedd";
        
        $ch = curl_init("https://api.privci.com/enterprise/EmailSearch");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_email_list));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept-Charset: UTF-8')
        );
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $key);

        $data = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($data, 0, $header_size);
        $body = substr($data, $header_size);
        curl_close($ch);
        
        return response()->json(json_decode($body));
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
        
        $username = "privci_dev";
        $key = "bhAAd231668uhgASt9eeeedd";
        
        $ch = curl_init("https://api.privci.com/enterprise/DomainSearch");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_domain));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept-Charset: UTF-8')
        );
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $key);

        $data = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($data, 0, $header_size);
        $body = substr($data, $header_size);
        curl_close($ch);
        
        return response()->json(json_decode($body));
    }
}