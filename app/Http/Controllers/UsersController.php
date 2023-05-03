<?php

namespace App\Http\Controllers;

use App\Models\Announces;
use App\Models\Parents;
use App\Models\Price;
use App\Models\Staff;
use App\Models\Students;
use App\Models\Teacher;
use App\Models\User;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $student = Students::where('status', 'ACTIVE')->count();
        $parent = Parents::count();
        $teacher = Teacher::count();
        $announces = Announces::orderBy('id', 'DESC')->first();
        $data = (object)([
            'student' => $student,
            'parent' => $parent,
            'teacher' => $teacher,
            'announces' => $announces,
        ]);

        return view('dashboard.index', compact('data'));
    }

    public function viewLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // return $request;
        // return bcrypt('teacher');
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);
        // return Auth::guard('staff')->attempt(['username' => $request->email, 'password' => $request->password]);
        try {
            if ($request->type == 'teacher') {
                if (Auth::guard('teacher')->attempt(['username' => $request->email, 'password' => $request->password])) {
                    // if successful, then redirect to their intended location
                    return redirect()->intended('/dashboard');
                } else {
                    return redirect()->intended('/');
                }
            } else {
                if (Auth::guard('staff')->attempt(['username' => $request->email, 'password' => $request->password])) {
                    // if successful, then redirect to their intended location
                    return redirect('/dashboard')->with('message', 'Need to follow up');
                } else {
                    return redirect()->intended('/');
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            return $th;
        }
    }

    public function logout()
    {
        if (Auth::guard('teacher')->check()) {
            Auth::guard('teacher')->logout();
            return redirect('/');
        } else if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
            return redirect('/');
        }
    }

    public function profile()
    {
        try {
            $data = [];
            if (Auth::guard('teacher')->check()) {
                $data = Teacher::where('id', Auth::guard('teacher')->user()->id)->first();
            } else if (Auth::guard('staff')->check()) {
                $data = Staff::where('id', Auth::guard('staff')->user()->id)->first();
            }

            // return $data;
            return view('user', compact('data'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {
        // return $request;
        $this->validate($request, [
            'username' => 'required',
            'name' => 'required'
        ]);
        try {
            $input = ([
                'name' => $request->name,
                'username' => $request->username,
            ]);
            if ($request->password) {
                $input['password'] = bcrypt($request->password);
            }
            if (Auth::guard('teacher')->check()) {
                Teacher::where('id', $user)->update($input);
                return redirect('/user')->with('status', 'Success update profile');
            } else {
                Staff::where('id', $user)->update($input);
                return redirect('/user')->with('status', 'Success update profile');
            }
        } catch (\Throwable $th) {
            return $th;
            return redirect('/user')->with('error', 'Success update profile');
        }
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function print()
    {
        return view('report.print');
    }
}
