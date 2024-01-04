<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamRequest;
use App\Models\Team;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 10);
        $name = $request->input('name');

        $teamQuery = Team::query();

        if ($id) {
            $team = $teamQuery->find($id);

            if ($team) {
                return ResponseFormatter::success($team, "Team found");
            }

            return ResponseFormatter::error('Team not found', 404);
        }

        $teams = $teamQuery->where("company_id", $request->company_id);

        if ($name) {
            $teams->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success($teams->paginate($limit), 'Team found');
    }

    public function create(TeamRequest $request)
    {
        try {
            // upload logo
            if ($request->hasFile("icon")) {
                $path = $request->file("icon")->store("public/images");
            }

            //create team
            $team = Team::create([
                "name" => $request->name,
                "icon" => $path,
                "company_id" => $request->company_id
            ]);

            if (!$team) {
                throw new Exception("Team not created");
            }

            return ResponseFormatter::success($team, "Team created");
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(TeamRequest $request, $id) {
        try {
            $team = Team::find($id);

            if(!$team) {
                throw new Exception("Team not found");
            }

            if ($request->hasFile("icon")) {
                $path = $request->file("icon")->store("public/images");
            }

            $team->update([
                "name" => $request->name,
                "icon" => $path,
                "company_id" => $request->company_id,
            ]);

            return ResponseFormatter::success($team, "Team updated");
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $team = Team::find($id);

            if (!$team) {
                throw new Exception("Team not found");
            }

            $team->delete();

            return ResponseFormatter::success(_, "Team deleted");
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
