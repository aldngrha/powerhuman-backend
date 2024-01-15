<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResponsibilityRequest;
use App\Models\Responsibility;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class ResponsibilityController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 10);
        $name = $request->input('name');

        $responsabilityQuery = Responsibility::query();

        if ($id) {
            $responsability = $responsabilityQuery->find($id);

            if ($responsability) {
                return ResponseFormatter::success($responsability, "Responsibility found");
            }

            return ResponseFormatter::error('Responsibility not found', 404);
        }

        $responsabilities = $responsabilityQuery->where("role_id", $request->role_id);

        if ($name) {
            $responsabilities->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success($responsabilities->paginate($limit), 'Responsibility found');
    }

    public function create(ResponsibilityRequest $request)
    {
        try {

            //create role
            $responsability = Responsibility::create([
                "name" => $request->name,
                "role_id" => $request->role_id
            ]);

            if (!$responsability) {
                throw new Exception("Responsibility not created");
            }

            return ResponseFormatter::success($responsability, "Responsibility created");
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $responsability = Responsibility::find($id);

            if (!$responsability) {
                throw new Exception("Responsibility not found");
            }

            $responsability->delete();

            return ResponseFormatter::success(null, "Responsibility deleted");
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
