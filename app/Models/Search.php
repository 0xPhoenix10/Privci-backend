<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    public static function get_by_email($email) {
        $domains = DB::table('organisation')
                        ->select('monitoring_domain')
                        ->where('colleague_emails', 'like', '%' . $email . '%')
                        ->where('main_organisation', 'privci.com')
                        ->orderBy('monitoring_domain', 'asc')
                        ->get()->toArray();

        $breachs = array();
        foreach($domains as $domain) {
            $result = DB::table('monitored_domain_stats')
                            ->select('*')
                            ->where('monitoring_domain','LIKE','%'.trim($domain->monitoring_domain).'%')
                            ->get()->toArray();

            array_push($breachs, $result);
        }

        return $breachs;
    }

    public static function get_all_domains() {
        $result = DB::table('monitored_domain_stats')
                        ->select('monitoring_domain')
                        ->orderBy('monitoring_domain', 'asc')
                        ->get()->toArray();
        
        return $result;
    }

    public static function get_domain_detail($domain) {
        $result = DB::table('monitored_domain_stats')
                        ->select('*')
                        ->where('monitoring_domain', 'like', '%' . $domain . '%')
                        ->get()->toArray();
        
        return $result;
    }

    public static function get_domain_emails($domain) {
        $result = DB::table('organisation')
                        ->select('colleague_emails')
                        ->where('monitoring_domain', 'like', '%' . $domain . '%')
                        ->get()->toArray();
        
        return $result;
    }

    public static function get_domains_by_order($request) {
        $result = DB::table('monitored_domain_stats')
                        ->select('monitoring_domain')
                        ->orderBy($request->type, $request->order)
                        ->get()->toArray();
        
        return $result;
    }

    public static function get_domains_by_keyword($request) {
        if($request->type == 'domain') {
            $result = DB::table('monitored_domain_stats')
                        ->select('monitoring_domain')
                        ->where('monitoring_domain', 'like', '%' . $request->keyword . '%')
                        ->get()->toArray();
        } else {
            $result = DB::table('organisation')
                        ->select('monitoring_domain')
                        ->where('colleague_emails', 'like', '%' . $request->keyword . '%')
                        ->get()->toArray();
        }
        
        return $result;
    }
}