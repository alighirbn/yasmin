<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::orderBy('id', 'DESC')->paginate(20);
        return view('user.index', compact('users'))->with('i', ($request->input('page', 1) - 1) * 20);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        $departments = Department::all();
        return view('user.create', compact(['roles', 'departments']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles_name' => ['array'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'Status' => $request->Status,
            'url_address' => $this->get_random_string(60),
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole(collect($request->role_name));

        return redirect()->route('user.index')
            ->with('success', 'تمت أضافة المستخدم بنجاح ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $user = User::where('url_address', '=', $url_address)->first();
        if (isset($user)) {
            return view('user.show', compact('user'));
        } else {
            $ip = $this->getIPAddress();
            return view('user.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {
        $user = User::where('url_address', '=', $url_address)->first();
        if (isset($user)) {
            $departments = Department::all();
            $roles = Role::pluck('name', 'name')->all();
            $userRole = $user->roles->pluck('name', 'name')->all();
            return view('user.edit', compact(['user', 'departments', 'roles', 'userRole']));
        } else {
            $ip = $this->getIPAddress();
            return view('user.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $url_address)
    {
        $user = User::where('url_address', $url_address)->first();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'Status' => 'required',
            'department_id' => 'required',
            'password' => 'same:password_confirmation',
            'role_name' => 'required'
        ]);

        if (!empty($request->password)) {
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = $request->except('password');
        }

        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $user->id)->delete();

        $user->assignRole(collect($request->role_name));

        return redirect()->route('user.index')
            ->with('success', 'تمت تحديث بيانات المستخدم بنجاح ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $user = User::where('url_address', $url_address)->first();
        $affected = User::where('url_address', $url_address)->delete();
        DB::table('model_has_roles')->where('model_id', $user->id)->delete();
        return redirect()->route('user.index')
            ->with('success', 'تمت حذف بيانات المستخدم بنجاح ');
    }


    function get_random_string($length)
    {
        $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $text = "";
        $length = rand(22, $length);

        for ($i = 0; $i < $length; $i++) {
            $random = rand(0, 61);
            $text .= $array[$random];
        }
        return $text;
    }

    function getIPAddress()
    {
        //whether ip is from the share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from the proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from the remote address
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
