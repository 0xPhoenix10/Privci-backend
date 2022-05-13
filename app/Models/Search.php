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
        
        // foreach($domains as $domain) {
            
        // }
        return $domains;
    }
}