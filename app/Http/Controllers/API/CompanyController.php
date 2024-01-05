<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 10);
        $name = $request->input('name');

        $companyQuery = Company::with(['users'])->whereHas("users", function ($query) {
            $query->where("user_id", Auth::id());
        });

        if ($id) {
            $company = $companyQuery->find($id);

            if ($company) {
                return ResponseFormatter::success($company, "Company found");
            }

            return ResponseFormatter::error('Company not found', 404);
        }

        $companies = $companyQuery;

        if ($name) {
            $companies->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success($companies->paginate($limit), 'Company found');
    }

    public function create(CompanyRequest $request)
    {
        try {
            // upload logo
            if ($request->hasFile("logo")) {
                $path = $request->file("logo")->store("public/images");
            }

            //create company
            $company = Company::create([
                "name" => $request->name,
                "logo" => $path
            ]);

            if (!$company) {
                throw new Exception("Company not created");
            }

            // attach company to user
            $user = User::find(Auth::id());
            $user->companies()->attach($company->id);

            // load users at company
            $company->load("users");

            return ResponseFormatter::success($company, "Company created");
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(CompanyRequest $request, $id) {
        try {
            $company = Company::find($id);

            if(!$company) {
                throw new Exception("Company not found");
            }

            if ($request->hasFile("logo")) {
                $path = $request->file("logo")->store("public/images");
            }

            $company->update([
                "name" => $request->name,
                "logo" => isset($path) ? $path : $company->logo,
            ]);

            return ResponseFormatter::success($company, "Company updated");
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
