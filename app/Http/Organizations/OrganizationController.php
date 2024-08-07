<?php

namespace DDD\Http\Organizations;

use DDD\Domain\Organizations\Resources\OrganizationResource;
use DDD\Domain\Organizations\Organization;
use DDD\App\Controllers\Controller;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        return new OrganizationResource($organization);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Organization $organization, Request $request)
    {
        $organization->update($request->all());

        return new OrganizationResource($organization);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();

        return new OrganizationResource($organization);
    }
}
