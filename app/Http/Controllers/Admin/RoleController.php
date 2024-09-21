<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Roles\RolesCollection;
use App\Jobs\ActivityHistoryJob;
use Illuminate\Http\Request;
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
        return new RolesCollection(Role::all());
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

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
