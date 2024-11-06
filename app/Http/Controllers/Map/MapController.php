<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use App\Models\Building\Building;
use App\Models\Building\DefaultValue;
use App\Models\Contract\Contract;
use Carbon\Carbon;
use Illuminate\Http\Request;


class MapController extends Controller
{

    public function contract()
    {
        // Fetch all contracts with their building data
        $contracts = Contract::with(['building'])->get();

        // Get the total number of buildings
        $totalBuildings = Building::count();

        // Get the count of buildings with contracts
        $contractCount = $contracts->count();

        // Calculate the percentage of buildings with contracts
        $percentageContracts = $totalBuildings > 0 ? ($contractCount / $totalBuildings) * 100 : 0;

        // Calculate the number of buildings without contracts
        $buildingsWithContracts = $contracts->pluck('building_id')->toArray();
        $buildingsWithoutContracts = Building::doesntHave('contract')->count();

        return view('map.contract', compact([
            'contracts',
            'contractCount',
            'totalBuildings',
            'percentageContracts',
            'buildingsWithoutContracts'
        ]));
    }


    public function map()
    {

        return view('map.map');
    }

    public function edit()
    {
        $buildings = Building::all();
        return view('map.edit', compact('buildings'));
    }

    public function hidden()
    {
        $buildings = Building::all();
        return view('map.hidden', compact('buildings'));
    }

    public function draw()
    {
        $buildings = Building::all();
        return view('map.draw', compact('buildings'));
    }

    public function empty()
    {
        // Exclude buildings that are marked as hidden
        $buildings = Building::where('hidden', false)->doesntHave('contract')->get();
        // Pass the buildings and price per meter to the view
        return view('map.empty', compact(['buildings']));
    }

    public function due(Request $request)
    {
        $daysBeforeDue = $request->input('days_before_due', 0); // Default to 0 days if not provided
        $dueDate = Carbon::today()->subDays($daysBeforeDue); // Calculate the due date

        $contracts = Contract::with(['building'])->whereHas('unpaidInstallments', function ($query) use ($dueDate) {
            $query->where('installment_date', '<=', $dueDate);
        })->get();

        return view('map.due', compact(['contracts']));
    }
}
