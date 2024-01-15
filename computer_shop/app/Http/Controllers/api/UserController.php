<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $user=User::all();
        // return response()->json([
        //     'users'=>$user,
        // ]);
        $users = User::with('roles');
        if ($request->has('search') && !empty($request->search)) {
            $users = $users->where('name', 'like', '%' . $request->search . '%');
        }
        $users = $users->paginate(10);
        return response()->json([
            'users' => $users,

        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //!
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth('api')->user()->hasRole('admin')) {
            // $request->validate([
            //     'name' => 'required|string|max:200',
            //     'username' => 'required|string|max:200',
            //     'roles' => 'required|string|max:20',
            //     'email' => 'required|string|email|max:255|unique:' . User::class,
            //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // ]);
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'roles' => 'required|string|max:255',
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($request->id)],
            ]);
            // $user = new User();
            // $user->fill($request->role);
            // $user->assignRole($request->role);
            $roles = is_array($request->roles) ? $request->roles : explode(',', $request->roles);
            $user = new User([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->save();
            $user->assignRole($roles);
            return response()->json([
                "message" => "User created successfully",
                'user' => $user
            ], 201);
        } else
            return response()->json([
                'message' => "You are not allowed to action"
            ], 403);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'users' => User::with('roles')->find($id),

        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->json([
            'users' => User::with('roles')->find($id),
            'admin' => Role::whereNotIn('name', ['admin'])->pluck('name'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:200',
    //         'username' => 'required|string|max:200',
    //         'roles' => 'required|string|max:20',
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:200', Rule::unique(User::class)->ignore($request->id)],
    //     ]);
    //     $user = User::findOrFail($request->$id);
    //     $user->fill($request->all())->save();

    //     if (!$user->hasRole($request->role))
    //         $user->syncRole($request->role);


    //     return response([
    //         'message' => 'user have been updated successfully!',
    //         'user' => $user
    //     ], 201);
    // }
    public function update(Request $request, string $id)
{
    $request->validate([
        'name' => 'required|string|max:200',
        'username' => 'required|string|max:200',
        'roles' => 'required|string|max:20',
        'email' => ['required', 'string', 'lowercase', 'email', 'max:200', Rule::unique(User::class)->ignore($id)],
    ]);

    try {
        // Attempt to find the user by ID
        $user = User::findOrFail($id);
        $roles =Role::findOrFail($id);
        // Update user attributes
        $user->fill($request->all())->save();

        // Check and sync roles if necessary
        if (!$user->hasRole($request->roles)) {
            $user->syncRoles([$request->roles]);
        }

        return response([
            'message' => 'User updated successfully!',
            'users' => $user,
            'roles'=>$roles
        ], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
        return response(['message' => 'User not found'], 404);
    }
}




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(User::with('roles')->find($id)){
            User::destroy($id);
            return response()->json([
                "message" => "User deleted successfully",
            ], 201);
        }else{
        }return response()->json([
                "message" => "User not found"
            ], 404);

    }
}
