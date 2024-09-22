<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Roles\PermissionResource;
use App\Http\Resources\Roles\RolesResource;
use App\Jobs\ActivityHistoryJob;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use UA;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getRoles(Request $request)
    {

        // $this->authorize('view', ActivityHistory::class);
        $roles = Role::with('permissions')->withCount('users')->get(); // Eager load 'permissions'
        return RolesResource::collection($roles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        // $this->authorize('view', ActivityHistory::class);
        $request->validate([
            'name' => 'required|unique:roles,name,except,id',
            'description' => 'sometimes|string|max:255',
            'color' => ['required', 'regex:/^(?:[0-9a-f]{3}){1,2}$/i'],
            'text_color'
            => ['required', 'regex:/^(?:[0-9a-f]{3}){1,2}$/i'],
        ]);


        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
            'text_color' => $request->text_color,
        ]);
        // log activity
        $agent = UA::parse($request->server('HTTP_USER_AGENT'));
        ActivityHistoryJob::dispatch(
            data: [
                'model' => 'roles',
                'action' => 'create',
                'data' => ['role' => $role],
                'user_id' => $request->user()->id,
            ],
            platform: $agent->os->family,
            browser: $agent->ua->family,
        );


        return response()->json(['success' => 'Role created successfully', 'role' => $role], 200);
    }

    public function getPermissions(Request $request)
    {
        // return all permissions
        // $this->authorize('view', ActivityHistory::class);
        return response()->json(['permissions' => PermissionResource::collection(Permission::all())], 200);
    }

    public function delete(Request $request)
    {
        // $this->authorize('view', ActivityHistory::class);
        $request->validate([
            'id' => 'required|exists:roles',
        ]);
        $role = Role::find($request->id);
        if ($role->name === 'Super Admin') {
            return response()->json(['error' => 'Super Admin role cannot be deleted'], 400);
        }

        if ($role->users->count() > 0) {
            return response()->json(['error' => 'Role has users'], 400);
        }
        $role->permissions()->detach();
        $role->delete();
        // log activity
        $agent = UA::parse($request->server('HTTP_USER_AGENT'));
        ActivityHistoryJob::dispatch(
            data: [
                'model' => 'roles',
                'action' => 'delete',
                'data' => ['role' => $role],
                'user_id' => $request->user()->id,
            ],
            platform: $agent->os->family,
            browser: $agent->ua->family,
        );
        return response()->json(['success' => 'Role deleted successfully'], 200);
    }
}
