<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\StoreRequest;
use App\Http\Requests\Company\UpdateRequest;
use App\Http\Resources\Company\CompanyCollection;
use App\Http\Resources\Company\CompanyResource;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $items = new CompanyCollection(Company::all());

        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Company\StoreRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $item = Company::create($request->validated());

        return response()->json($item, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Company $company)
    {
        $company->load('employees');
        return response()->json(new CompanyResource($company));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Company\UpdateRequest  $request
     * @param  \App\Models\Company  $company
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Company $company)
    {
        $company->update($request->validated());

        return response()->json($company, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json([], 204);
    }
}
