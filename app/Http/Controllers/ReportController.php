<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Building\Building_Category;
use App\Models\Cash\Expense;
use App\Models\Contract\Contract;
use App\Models\Customer\Customer;
use App\Models\Payment\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    public function due_installments(Request $request)
    {
        // Get the building_block filter value from the request
        $buildingBlock = $request->input('block_number');

        // Fetch due installments with an optional building_block filter
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
                'buildings.block_number', // Add this field if needed in the select
                DB::raw('COUNT(contract_installments.id) as due_installments_count'),
                DB::raw('SUM(contract_installments.installment_amount) as total_due_amount')
            )
            ->where('contract_installments.installment_date', '<', Carbon::now())
            ->where(function ($query) {
                $query->whereNull('payments.id')
                    ->orWhere('payments.approved', false);
            });

        // Apply building_block filter if provided
        if ($buildingBlock) {
            $dueInstallments->where('buildings.block_number', $buildingBlock);
        }

        $dueInstallments = $dueInstallments
            ->groupBy('contracts.id', 'customers.customer_full_name', 'customers.customer_phone', 'buildings.block_number', 'buildings.block_number')
            ->orderBy('due_installments_count', 'desc')
            ->orderBy('total_due_amount', 'desc')
            ->get();

        // Prepare the report
        $report = [
            'due_installments' => $dueInstallments,
        ];

        $block_numbers = DB::table('buildings')->distinct()->pluck('block_number');
        // Return the view with the report data
        return view('report.due_installments', compact('report', 'block_numbers'));
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
    public function general_report(Request $request)
    {
        // Fetch all customers to populate the filter dropdown
        $customers = Customer::all();

        // Validate the contract_customer_id and date range if provided
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'contract_customer_id' => 'nullable|exists:customers,id', // Validate the customer ID
        ]);

        // Retrieve the contract_customer_id and date range from the request
        $contractCustomerId = $request->input('contract_customer_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch contracts and payments, but only fetch expenses if no customer filter is applied
        $contracts = Contract::query();
        $payments = Payment::query()->where('approved', true); // Only approved payments
        $expenses = null; // Start with no expenses

        // Apply date filters if dates are provided
        if ($startDate && $endDate) {
            // Apply date filters to contracts and payments
            $contracts->whereBetween('contract_date', [$startDate, $endDate]);
            $payments->whereBetween('payment_date', [$startDate, $endDate]);

            // Only apply date filter to expenses if no customer filter is applied
            if (!$contractCustomerId) {
                $expenses = Expense::query()->where('approved', true)->whereBetween('expense_date', [$startDate, $endDate]);
            }
        }

        // Apply customer ID filter if provided (use the correct column name)
        if ($contractCustomerId) {
            $contracts->where('contract_customer_id', $contractCustomerId); // Correct column name
            $payments->whereHas('contract', function ($query) use ($contractCustomerId) {
                $query->where('contract_customer_id', $contractCustomerId); // Correct column name
            });
            // Do not include expenses if customer filter is applied
            $expenses = null;
        } else {
            // If no customer filter, include expenses and apply date filter if needed
            if (!$expenses) {
                $expenses = Expense::query()->where('approved', true);
            }
        }

        // Get the data
        $contracts = $contracts->get();
        $payments = $payments->get();
        // Only fetch expenses if not filtered by customer and date filter is applied
        $expenses = $expenses ? $expenses->get() : [];

        // Calculate totals
        $totalContractAmount = $contracts->sum('contract_amount');
        $totalPaymentAmount = $payments->sum('payment_amount');
        $totalExpenseAmount = $expenses ? $expenses->sum('expense_amount') : 0;

        // Pass the customers and other variables to the view
        return view('report.general_report', compact('contracts', 'payments', 'expenses', 'totalContractAmount', 'totalPaymentAmount', 'totalExpenseAmount', 'customers'));
    }
}
