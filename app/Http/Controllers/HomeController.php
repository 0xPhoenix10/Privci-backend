<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Search;

class HomeController extends Controller
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
        $type = 'all';
        $domains = Search::get_all_domains();
        foreach($domains as $domain) {
            $first_domain_detail = Search::get_domain_detail($domain->monitoring_domain);
            $first_domain_emails = Search::get_domain_emails($domain->monitoring_domain);
            // $first_domain_detail = Search::get_domain_detail("1-800-Flowers.com");
            $domain_detail = $first_domain_detail[0];
            $domain_emails = explode(',', $first_domain_emails[0]->colleague_emails);
            $selected = $domain->monitoring_domain;

            break;
        }

        return view('home', compact('domains', 'domain_detail', 'domain_emails', 'type', 'selected'));
    }

    public function get_by_domain($key) {
        $type = "one";
        $domain = $key;

        $domains = Search::get_all_domains();
        $domain_detail = Search::get_domain_detail($domain);
        $domain_detail = $domain_detail[0];
        $domain_emails = Search::get_domain_emails($domain);
        if(!empty($domain_emails)) {
            $domain_emails = explode(',', $domain_emails[0]->colleague_emails);
        } else {
            $domain_emails = array();
        }
        
        $selected = $domain;

        return view('home', compact('domains', 'domain_detail', 'domain_emails', 'type', 'selected'));
    }
}