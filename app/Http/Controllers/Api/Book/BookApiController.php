<?php

namespace App\Http\Controllers\Api\Book;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\ResourceLocation;
use App\Models\ResourceType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BookApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [];
        $data['ResourceLocation'] = ResourceLocation::all()->toArray();
        $data['ResourceTypes'] = ResourceType::with('resources.SubResources')->get()->toArray();

        // $data['ResourceTypes'] = ResourceType::with('resources.SubResources')->whereHas('resources', function(Builder $query) use ($locationId){
        //     if(! is_null($locationId) ){$query->where('resourceLocation', '=', $locationId);}
        // })->get()->toArray();

        // dd($data);
        if ($data != null) {
            return response()->json([
                "message" => "Reource Location and Type Found Successfully",
                "status" => "success",
                "data" => $data,
            ], 200);
        } else {
            return response()->json([
                "message" => "No Resource Location and Type found",
                "status" => "failed",
            ], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $resourceId, string $locationId = '0')
    public function show(string $locationId, string $resourceId = '0')
    {
        $data = [];
        $data['ResourceLocation'] = ResourceLocation::all()->toArray();
        // $data['ResourceTypes'] = ResourceType::with('resource')->get()->toArray();

        if (!is_null($locationId) && $locationId != 0) {
            $data['ResourceTypes'] = ResourceType::with('resources.SubResources')->whereHas('resources', function (Builder $query) use ($locationId) {
                $query->where('resourceLocation', '=', $locationId);
            })->get()->toArray();
        } else {
            $data['ResourceTypes'] = ResourceType::with('resources.SubResources')->get()->toArray();
        }
        if ($resourceId != 0)
            $data['Resources'] = Resource::with('SubResources', 'Bookings')->where('ID', '=', $resourceId)->get()->toArray();


        // $data['Bookings'] = Resource::with('Booking')->where('ID', '=', $id)->get()->toArray();
        // $data['ALL'] = Resource::with('SubResource','Booking')->where('ID', '=', $id)->get()->toArray();

        // dd($data);
        if ($data != null) {
            return response()->json([
                "message" => "Reource Location and Type Found Successfully",
                "status" => "success",
                "data" => $data,
            ], 200);
        } else {
            return response()->json([
                "message" => "No Resource Location and Type found",
                "status" => "failed",
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function getLocationResource(string $locationId, string $resourceId)
    {
    }
    public function getResource(Request $request, Resource $resource)
    {
        $data = [];
        $data = Resource::with('SubResource')->where("ID", "=", $resource->ID)->get()->toArray();
        if ($data != null) {
            return response()->json([
                "message" => "Reource Location and Type Found Successfully",
                "status" => "success",
                "data" => $data,
            ], 200);
        } else {
            return response()->json([
                "message" => "No Resource Location and Type found",
                "status" => "failed",
            ], 200);
        }
    }


    /**
     * END::CLASS
     */
}
