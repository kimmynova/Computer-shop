<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $role = Role::with('permissions');
        if ($request->has('search') && !empty($request->search)) {
            $role = $role->where('name', 'like', '%' . $request->search . '%');
        }

        $role = $role->paginate(10);
        if ($role->isEmpty()) {
            return response()->json([
                "message" => "role not found",
            ], 404);
        }
        return response()->json([
            'roles' => $role,

        ], 200);
    }
    public function show($id)
    {

        return response()->json([
            'roles' => role::with('permissions')->find($id),

        ], 200);
    }
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        if (auth('api')->user()->hasRole('admin')) {
            $role = new Role();
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }
            $role->fill($request->all())->save();

            return response()->json([
                'message' => 'Role created successfully',
                "roles" => $role,
            ], 201);
        }
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        if (auth('api')->user()->hasRole('admin')) {
            $role = Role::findById($request->id);
            $role->fill($request->all())->save();

            return response()->json([
                'message' => 'Role update successfully ',
                'roles' => $role
            ], 404);
        } else {
            return response()->json([
                'message' => 'Role update failed',
            ], 403);
        }
    }
    public function delete($id)
    {
        if (auth('api')->user()->hasRole('admin')) {
            Role::destroy($id);
            // $role = Role::orderBy('id', 'desc')->first();
            $role = Role::all();


            return response()->json([
                "message" => "Role deleted successfully",
                'roles' => $role,
            ], 201);
        } else {
            return response()->json([
                "message" => "Role deleted failed",
            ], 403);
        }
    }
}
