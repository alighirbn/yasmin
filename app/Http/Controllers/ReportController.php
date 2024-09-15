<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Building\Building_Category;
use App\Models\Contract\Contract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function category()
    {
        // Fetch data
        $contractCountsByCategory = Building_Category::select('building_category.category_name', DB::raw('COUNT(contracts.id) as contract_count'))
            ->leftJoin('buildings', 'building_category.id', '=', 'buildings.building_category_id')
            ->leftJoin('contracts', 'buildings.id', '=', 'contracts.contract_building_id')
            ->groupBy('building_category.id', 'building_category.category_name')
            ->get();

        $remainingBuildingsByCategory = Building_Category::select('building_category.category_name', DB::raw('COUNT(buildings.id) as building_count'))
            ->leftJoin('buildings', 'building_category.id', '=', 'buildings.building_category_id')
            ->leftJoin('contracts', 'buildings.id', '=', 'contracts.contract_building_id')
            ->whereNull('contracts.id')
            ->groupBy('building_category.id', 'building_category.category_name')
            ->get();

        // Combine data into one collection
        $combinedData = $contractCountsByCategory->map(function ($item) use ($remainingBuildingsByCategory) {
            $remaining = $remainingBuildingsByCategory->firstWhere('category_name', $item->category_name);
            return [
                'category_name' => $item->category_name,
                'contract_count' => $item->contract_count,
                'building_count' => $remaining ? $remaining->building_count : 0
            ];
        });

        // Prepare the report
        $report = [
            'combined_data' => $combinedData,
        ];

        // Return the view with the report data
        return view('report.category', compact('report'));
    }

    public function due_installments()
    {
        // Fetch due installments with contract ID, customer name, customer phone, building number, and total due amount
        $dueInstallments = DB::table('contract_installments')
            ->join('contracts', 'contract_installments.contract_id', '=', 'contracts.id')
            ->join('customers', 'contracts.contract_customer_id', '=', 'customers.id')
            ->join('buildings', 'contracts.contract_building_id', '=', 'buildings.id')
            ->leftJoin('payments', 'contract_installments.id', '=', 'payments.contract_installment_id')
            ->select(
                'contracts.id as contract_id',
                'customers.customer_full_name',
                'contracts.url_address',
                'customers.customer_phone',
                'buildings.building_number',
                DB::raw('COUNT(contract_installments.id) as due_installments_count'),
                DB::raw('SUM(contract_installments.installment_amount) as total_due_amount')
            )
            ->where('contract_installments.installment_date', '<', Carbon::now())
            ->where(function ($query) {
                $query->whereNull('payments.id')
                    ->orWhere('payments.approved', false);
            })
            ->groupBy('contracts.id', 'customers.customer_full_name', 'customers.customer_phone', 'buildings.building_number')
            ->orderBy('due_installments_count', 'desc') // Sorting by due installments count in descending order
            ->orderBy('total_due_amount', 'desc') // Then sort by total due amount in descending order
            ->get();

        // Prepare the report
        $report = [
            'due_installments' => $dueInstallments,
        ];

        // Return the view with the report data
        return view('report.due_installments', compact('report'));
    }


    public function first_installment()
    {
        // Query for unpaid cash contracts (payment method 1: cash)
        $unpaidCashContracts = Contract::where('contract_payment_method_id', 1) // Cash payment method
            ->whereDoesntHave('payments', function ($query) {
                $query->where('approved', true);
            })->get();

        $unpaidFirstInstallmentContracts = Contract::where('contract_payment_method_id', 2) // Installment payment method
            ->whereHas('contract_installments', function ($query) {
                $query->whereHas('installment', function ($query) {
                    $query->where('installment_number', 1); // First installment
                })->whereDoesntHave('payment', function ($query) {
                    $query->where('approved', true); // Unpaid
                });
            })->get();



        // Return to a view with both tables
        return view('report.first_installment', compact('unpaidCashContracts', 'unpaidFirstInstallmentContracts'));
    }
}
