<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Session;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        $result = $this->check_user_role();

        if($result > 0) {
            $users = $model->get_all_users();

            return view('users.index', compact('users'));
        } else {
            return abort(404);
        }
    }

    public function edit(Request $request, $id = 0) {
        $result = $this->check_user_role();
        $success = false;

        if($result > 0) {
        } else {
            return abort(404);
        }

        if(isset($request->userid)) {
            if($request->userid > 0) {
                $user = User::findOrFail($request->userid);
            }
            if($request->userid > 0) {
                $validator = Validator::make($request->all(), [
                    'username' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
                    // 'password' => ['required', 'string', 'min:6'],
                ]);
    
                // if fails redirects back with errors
                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
    
                $user->fill([
                    'name' => $request->username,
                    'email' => $request->email
                ]);
    
                $user->save();
    
                $success = true;
            } else {
                $validator = Validator::make($request->all(), [
                    'username' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:6'],
                ]);
    
                // if fails redirects back with errors
                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
    
                User::create([
                    'name' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);
    
                $success = true;
            }
        }

        if($id > 0) {
            $user = User::get_user($id);
            $user = $user[0];

            return view('users.edit', compact('user', 'success'));
        } else {
            return view('users.edit', compact('success'));
        }
    }

    public function delete($userid) {
        $user = User::findOrFail($userid);

        if($userid == 1) {
            $msg = "You can't delete admin user!";
            $status = "error";
        } else {
            $user->delete();
            $msg = "Successfully deleted!";
            $status = "success";
        }

        $users = User::get_all_users();

        return view('users.index', compact('users', 'msg', 'status'));
    }

    protected function check_user_role() {
        if(auth()->user()->id == 1) {
            return 1;
        } else {
            return 0;
        }
    }
}