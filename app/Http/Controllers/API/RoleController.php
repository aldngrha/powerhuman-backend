<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 10);
        $name = $request->input('name');
        $with_responsibilities = $request->input('with_responsibilities', false);

        $roleQuery = Role::query();

        if ($id) {
            $role = $roleQuery->with("responsibilities")->find($id);

            if ($role) {
                return ResponseFormatter::success($role, "Role found");
            }

            return ResponseFormatter::error('Role not found', 404);
        }

        $roles = $roleQuery->where("company_id", $request->company_id);

        if ($name) {
            $roles->where('name', 'like', '%' . $name . '%');
        }

        if ($with_responsibilities) {
            $roles->with("responsibilities");
        }

        return ResponseFormatter::success($roles->paginate($limit), 'Role found');
    }

    public function create(RoleRequest $request)
    {
        try {

            //create role
            $role = Role::create([
                "name" => $request->name,
                "company_id" => $request->company_id
            ]);

            if (!$role) {
                throw new Exception("Role not created");
            }

            return ResponseFormatter::success($role, "Role created");
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(RoleRequest $request, $id) {
        try {
            $role = Role::find($id);

            if(!$role) {
                throw new Exception("Role not found");
            }

            $role->update([
                "name" => $request->name,
                "company_id" => $request->company_id,
            ]);

            return ResponseFormatter::success($role, "Role updated");
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $role = Role::find($id);

            if (!$role) {
                throw new Exception("Role not found");
            }

            $role->delete();

            return ResponseFormatter::success(null, "Role deleted");
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
