<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public static function get_settings() {
        $result = DB::table('settings')
                        ->select('*')
                        ->where('main_organisation', 'privci.com')
                        ->get()->toArray();
        
        return $result;
    }

    public static function update_notification_email($email) {
        $result = DB::table('settings')
                        ->where('main_organisation', 'privci.com')
                        ->update(['notification_email' => $email]);
        
        return $result;
    }

    public static function update_notification_status($status) {
        $status = $status == 'True' ? 'False' : 'True';
        $result = DB::table('settings')
                        ->where('main_organisation', 'privci.com')
                        ->update(['notification' => $status]);
        
        return $result;
    }
}