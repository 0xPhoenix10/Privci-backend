<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    public static function save_policy($request) {
        if($request->pid == 0) {
            $result = DB::table('protection_policy')->insert([
                'title' => $request->title,
                'content' => $request->content,
                'link' => isset($request->link) ? $request->link : ''
            ]);
        } else {
            $result = DB::table('protection_policy')
                            ->where('id', $request->pid)
                            ->update([
                                'title' => $request->title,
                                'content' => $request->content,
                                'link' => isset($request->link) ? $request->link : ''
                            ]);
        }
        
        return $result;
    }

    public static function delete_policy($pid) {
        $result = DB::table('protection_policy')
                        ->where('id', $pid)
                        ->update(['is_delete' => 'Y']);
        
        return $result;
    }

    public static function get_all_policy() {
        $result = DB::table('protection_policy')
                        ->select('*')
                        ->where('is_delete', 'N')
                        ->get()->toArray();
        
        return $result;
    }

    public static function get_policy($pid) {
        $result = DB::table('protection_policy')
                        ->select('*')
                        ->where('id', $pid)
                        ->get()->toArray();
        
        return $result;
    }

    public static function get_all_faqs() {
        $result = DB::table('faq')
                        ->select('*')
                        ->where('is_delete', 'N')
                        ->get()->toArray();
        
        return $result;
    }

    public static function add_faq($request) {
        if($request->fid == 0) {
            $result = DB::table('faq')->insert([
                'question' => $request->question,
                'answer' => $request->answer
            ]);
        } else {
            $result = DB::table('faq')
                            ->where('id', $request->fid)
                            ->update([
                                'question' => $request->question,
                                'answer' => $request->answer
                            ]);
        }
        
        return $result;
    }

    public static function get_faq($fid) {
        $result = DB::table('faq')
                        ->select('*')
                        ->where('id', $fid)
                        ->get()->toArray();
        
        return $result;
    }

    public static function delete_faq($fid) {
        $result = DB::table('faq')
                        ->where('id', $fid)
                        ->update(['is_delete' => 'Y']);
        
        return $result;
    }
}