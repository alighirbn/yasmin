<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use App\Models\Building\Building;
use App\Models\Building\Classification;
use App\Models\Building\DefaultValue;
use App\Models\Contract\Contract;
use Carbon\Carbon;
use Illuminate\Http\Request;


class MapController extends Controller
{

    public function contract()
    {

        // Fetch all non-terminated contracts with their building and payment data
        $contracts = Contract::with(['building', 'payments'])
            ->whereNotIn('stage', ['terminated'])
            ->get();

        // Get the total number of buildings
        $totalBuildings = Building::count();

        // Get the count of buildings with contracts
        $contractCount = $contracts->count();

        // Calculate the percentage of buildings with contracts
        $percentageContracts = $totalBuildings > 0 ? ($contractCount / $totalBuildings) * 100 : 0;

        // Calculate the number of buildings without active contracts
        // Include buildings with no contracts or only terminated contracts
        $buildingsWithoutContracts = Building::where(function ($query) {
            $query->doesntHave('contract') // Buildings with no contracts
                ->orWhereDoesntHave('contract', function ($subQuery) {
                    $subQuery->whereNotIn('stage', ['terminated']); // Exclude buildings with non-terminated contracts
                });
        })->count();

        // Count contracts with payments
        $contractsWithPaymentsCount = $contracts->filter(function ($contract) {
            return $contract->payments->isNotEmpty();
        })->count();

        // Count contracts that have at least one payment with installments
        $contractsWithInstallmentsCount = $contracts->filter(function ($contract) {
            return $contract->payments->contains(function ($payment) {
                return $payment->contract_installment_id !== null;
            });
        })->count();

        return view('map.contract', compact([
            'contracts',
            'contractCount',
            'totalBuildings',
            'percentageContracts',
            'buildingsWithoutContracts',
            'contractsWithPaymentsCount',
            'contractsWithInstallmentsCount' // <- new variable
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

    public function classification()
    {
        $buildings = Building::all();
        $classifications = Classification::all();

        // Define colors for classifications
        $classificationColors = [
            1 => 'rgba(255, 0, 0, 0.7)',   // Red
            2 => 'rgba(0, 255, 0, 0.7)',   // Green
            3 => 'rgba(0, 0, 255, 0.7)',   // Blue
            4 => 'rgba(255, 255, 0, 0.7)', // Yellow
            5 => 'rgba(255, 0, 255, 0.7)', // Purple
            6 => 'rgba(0, 255, 255, 0.7)'  // Cyan (New)
        ];

        return view('map.classification', compact('buildings', 'classifications', 'classificationColors'));
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
        // Exclude buildings that are marked as hidden and have either no contracts or only terminated contracts
        $buildings = Building::where('hidden', false)
            ->where(function ($query) {
                $query->doesntHave('contract') // Buildings with no contracts
                    ->orWhereDoesntHave('contract', function ($subQuery) {
                        $subQuery->whereNotIn('stage', ['terminated']); // Exclude buildings with non-terminated contracts
                    });
            })->get();

        // Pass the buildings to the view
        return view('map.empty', compact(['buildings']));
    }


    public function due(Request $request)
    {
        $daysBeforeDue = $request->input('days_before_due', 0); // Default to 0 days if not provided
        $dueDate = Carbon::today()->subDays($daysBeforeDue); // Calculate the due date

        $contracts = Contract::with(['building'])
            ->whereHas('unpaidInstallments', function ($query) use ($dueDate) {
                $query->where('installment_date', '<=', $dueDate);
            })
            ->whereNotIn('stage', ['terminated'])
            ->get();

        return view('map.due', compact(['contracts']));
    }
}
