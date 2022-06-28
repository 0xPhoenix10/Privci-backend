<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    public static function get_supports() {
        $result = DB::table('supports')
                        ->select('*')
                        ->where('is_delete', 'N')
                        ->orderBy('id', 'desc')
                        ->get()->toArray();
        
        return $result;
    }

    public static function get_one($sid) {
        $result = DB::table('supports')
                        ->select('*')
                        ->where('is_delete', 'N')
                        ->where('id', $sid)
                        ->get()->toArray();
        
        return $result;
    }

    public static function save_support($request, $user_id) {
        $result = DB::table('supports')->insert([
            'subject' => $request->subject,
            'detail' => $request->detail,
            'reg_date' => date('Y.m.d'),
            'user_id' => $user_id
        ]);

        return $result;
    }

    public static function set_resolved($sid) {
        $result = DB::table('supports')
                        ->where('id', $sid)
                        ->update(['status' => 'Y']);
        
        return $result;
    }

    public static function set_ping($sid) {
        $result = DB::table('supports')
                        ->where('id', $sid)
                        ->update(['is_ping' => 'Y']);
        
        return $result;
    }

    public static function del_support($sid) {
        $result = DB::table('supports')
                        ->where('id', $sid)
                        ->update(['is_delete' => 'Y']);
        
        return $result;
    }
}