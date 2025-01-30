<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = (new User)->getAllUsers();
        return view('backend.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validateUserDetails($request);
        (new User)->storeUser($request);
        return redirect()->back()->with('message', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = (new User)->getUserById($id);
        return view('backend.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = (new User)->getUserById($id);
        return view('backend.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $filteredData = $this->validateUserDetails($request);
        (new User)->updateUser($filteredData, $id);
        return redirect()->route('user.show', $id)->with('message', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Prevent logged in user to delete itself
//        if(auth()->user()->id == $id){
//            return redirect()->back()->with('error', 'You cannot delete yourself!');
//        }

        // Admin can only delete a user
        (new User)->deleteUserById($id);
        return redirect()->route('user.index')->with('message', 'User deleted successfully!');
    }

    public function validateUserDetails(Request $request){

        if ($request->isMethod('POST')) {
            $this->validate($request,[
                'name' => 'required|max:255|min:3',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6',
                'visible_password' => 'required|min:6|same:password',
                'occupation' => 'max:255',
                'address' => 'required|max:255',
                'phone' => 'min:5|max:20',
            ]);
        }

        if ($request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $validatedData = $this->validate($request,[
                'name' => 'bail|required|max:255|min:3',
                'email' => 'bail|required|email|max:255|unique:users,id',
                'occupation' => 'max:255',
                'address' => 'max:255',
                'bio' => 'max:255',
                'phone' => 'min:5|max:20',
            ]);

            if (isset($request->password)){
                $this->validate($request, [
                    'password' => 'required|min:6',
                    'visible_password' => 'required|min:6|same:password',
                ]);
                $validatedData['password'] = $request->password;
                $validatedData['visible_password'] = $request->visible_password;
            }

            // Filter out null values from the validated data
            return $filteredData = array_filter($validatedData, function($value) {
                return !is_null($value);
            });
        }
    }
}
