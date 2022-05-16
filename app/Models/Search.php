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
}