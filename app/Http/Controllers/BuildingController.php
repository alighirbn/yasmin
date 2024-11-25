<?php

namespace App\Http\Controllers;

use App\DataTables\BuildingDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\BuildingRequest;
use App\Models\Building\Building;
use App\Models\Building\Building_Category;
use App\Models\Building\Building_Type;
use App\Models\Building\Classification;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BuildingDataTable $dataTable)
    {
        return $dataTable->render('building.building.index');
    }
    public function updateCoordinates(Request $request, $id)
    {
        $building = Building::findOrFail($id);

        // Validate the request
        $validated = $request->validate([
            'building_map_x' => 'required|numeric',
            'building_map_y' => 'required|numeric',
        ]);

        // Update the building coordinates
        $building->building_map_x = $validated['building_map_x'];
        $building->building_map_y = $validated['building_map_y'];
        $building->save();

        // Return a JSON response
        return response()->json(['success' => true]);
    }

    public function toggleVisibility(Request $request, $id)
    {
        // Find the building by its ID
        $building = Building::findOrFail($id);

        // Toggle the 'hidden' attribute
        $building->hidden = !$building->hidden;

        // Save the updated building
        $building->save();

        // Optionally return a response
        return response()->json(['success' => true, 'hidden' => $building->hidden]);
    }

    public function ajaxUpdateClassification(Request $request)
    {
        $building = Building::find($request->building_id);
        $building->classification_id = $request->classification_id;
        $building->save();

        // Define your classification colors
        $classificationColors = [
            1 => 'rgba(255, 0, 0, 0.7)',   // Red
            2 => 'rgba(0, 255, 0, 0.7)',   // Green
            3 => 'rgba(0, 0, 255, 0.7)',   // Blue
            4 => 'rgba(255, 255, 0, 0.7)', // Yellow
            5 => 'rgba(255, 0, 255, 0.7)'  // Purple
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Classification updated successfully.',
            'building_id' => $building->id,
            'color' => $classificationColors[$request->classification_id] ?? 'rgba(0, 0, 0, 0.5)'
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $building_categorys = Building_Category::all();
        $building_types = Building_Type::all();
        $classifications = Classification::all();
        return view('building.building.create', compact(['building_categorys', 'building_types', 'classifications']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BuildingRequest $request)
    {
        Building::create($request->validated());

        //inform the user
        return redirect()->route('building.index')
            ->with('success', 'تمت أضافة البناية بنجاح ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $building = Building::where('url_address', '=', $url_address)->first();
        if (isset($building)) {
            return view('building.building.show', compact('building'));
        } else {
            $ip = $this->getIPAddress();
            return view('building.building.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {
        $building_categorys = Building_Category::all();
        $building_types = Building_Type::all();
        $classifications = Classification::all();

        $building = Building::where('url_address', '=', $url_address)->first();
        if (isset($building)) {
            return view('building.building.edit', compact('building', 'building_categorys', 'building_types', 'classifications'));
        } else {
            $ip = $this->getIPAddress();
            return view('building.building.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BuildingRequest $request, string $url_address)
    {
        // insert the user input into model and lareval insert it into the database.
        Building::where('url_address', $url_address)->update($request->validated());

        //inform the user
        return redirect()->route('building.index')
            ->with('success', 'تمت تعديل بيانات البناية بنجاح ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $affected = Building::where('url_address', $url_address)->delete();
        return redirect()->route('building.index')
            ->with('success', 'تمت حذف بيانات البناية بنجاح ');
    }

    public function getIPAddress()
    {
        //whether ip is from the share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from the proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from the remote address
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
