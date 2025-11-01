<?php

namespace App\Http\Controllers;

use App\Models\Contract\Contract;
use App\Models\Payment\Payment;
use App\Models\Payment\Service;
use App\Models\Contract\Contract_Installment;
use App\Models\Cash\Expense;
use App\Models\Cash\Income;
use App\Models\Cash\Cash_Account;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Date ranges
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // ============== CONTRACTS ==============
        $contractsToday = Contract::whereDate('contract_date', $today)
            ->whereNotIn('stage', ['terminated'])
            ->count();

        $contractsWeek = Contract::whereBetween('contract_date', [$startOfWeek, $endOfWeek])
            ->whereNotIn('stage', ['terminated'])
            ->count();

        $contractsMonth = Contract::whereBetween('contract_date', [$startOfMonth, $endOfMonth])
            ->whereNotIn('stage', ['terminated'])
            ->count();

        $contractAmountMonth = Contract::whereBetween('contract_date', [$startOfMonth, $endOfMonth])
            ->whereNotIn('stage', ['terminated'])
            ->sum('contract_amount');

        $totalActiveContracts = Contract::whereNotIn('stage', ['terminated'])->count();

        // Contract by stage
        $contractsByStage = Contract::select('stage', DB::raw('count(*) as total'))
            ->whereNotIn('stage', ['terminated'])
            ->groupBy('stage')
            ->get();

        // ============== PAYMENTS ==============
        $paymentsToday = Payment::whereDate('payment_date', $today)
            ->where('approved', true)
            ->count();

        $paymentsTodayAmount = Payment::whereDate('payment_date', $today)
            ->where('approved', true)
            ->sum('payment_amount');

        $paymentsWeek = Payment::whereBetween('payment_date', [$startOfWeek, $endOfWeek])
            ->where('approved', true)
            ->count();

        $paymentsWeekAmount = Payment::whereBetween('payment_date', [$startOfWeek, $endOfWeek])
            ->where('approved', true)
            ->sum('payment_amount');

        $paymentsMonth = Payment::whereBetween('payment_date', [$startOfMonth, $endOfMonth])
            ->where('approved', true)
            ->count();

        $paymentsMonthAmount = Payment::whereBetween('payment_date', [$startOfMonth, $endOfMonth])
            ->where('approved', true)
            ->sum('payment_amount');

        $pendingPayments = Payment::where('approved', false)->count();

        // ============== SERVICES ==============
        $servicesToday = Service::whereDate('service_date', $today)->count();
        $servicesWeek = Service::whereBetween('service_date', [$startOfWeek, $endOfWeek])->count();
        $servicesMonth = Service::whereBetween('service_date', [$startOfMonth, $endOfMonth])->count();

        $servicesAmountMonth = Service::whereBetween('service_date', [$startOfMonth, $endOfMonth])
            ->sum('service_amount');

        // ============== DUE INSTALLMENTS ==============
        $dueInstallmentsToday = Contract_Installment::whereDate('installment_date', '<=', $today)
            ->where(function ($query) {
                $query->whereRaw('paid_amount < installment_amount')
                    ->orWhere('paid_amount', 0);
            })
            ->whereHas('contract', function ($q) {
                $q->whereNotIn('stage', ['terminated']);
            })
            ->count();

        $dueInstallmentsTodayAmount = Contract_Installment::whereDate('installment_date', '<=', $today)
            ->where(function ($query) {
                $query->whereRaw('paid_amount < installment_amount')
                    ->orWhere('paid_amount', 0);
            })
            ->whereHas('contract', function ($q) {
                $q->whereNotIn('stage', ['terminated']);
            })
            ->sum(DB::raw('installment_amount - paid_amount'));

        $dueInstallmentsWeek = Contract_Installment::whereDate('installment_date', '<=', $endOfWeek)
            ->where(function ($query) {
                $query->whereRaw('paid_amount < installment_amount')
                    ->orWhere('paid_amount', 0);
            })
            ->whereHas('contract', function ($q) {
                $q->whereNotIn('stage', ['terminated']);
            })
            ->count();

        // ============== EXPENSES & INCOME ==============
        $expensesToday = Expense::whereDate('expense_date', $today)
            ->where('approved', true)
            ->sum('expense_amount');

        $expensesWeek = Expense::whereBetween('expense_date', [$startOfWeek, $endOfWeek])
            ->where('approved', true)
            ->sum('expense_amount');

        $expensesMonth = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->where('approved', true)
            ->sum('expense_amount');

        $incomeToday = Income::whereDate('income_date', $today)
            ->where('approved', true)
            ->sum('income_amount');

        $incomeWeek = Income::whereBetween('income_date', [$startOfWeek, $endOfWeek])
            ->where('approved', true)
            ->sum('income_amount');

        $incomeMonth = Income::whereBetween('income_date', [$startOfMonth, $endOfMonth])
            ->where('approved', true)
            ->sum('income_amount');

        // ============== CASH ACCOUNTS ==============
        $cashAccounts = Cash_Account::all();
        $totalCashBalance = $cashAccounts->sum('balance');

        // ============== EMPLOYEES ==============
        $totalEmployees = Employee::where('status', 'active')->count();

        // ============== CHART DATA ==============
        // Last 7 days payments
        $last7DaysPayments = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $amount = Payment::whereDate('payment_date', $date)
                ->where('approved', true)
                ->sum('payment_amount');
            $last7DaysPayments[] = [
                'date' => $date->format('M d'),
                'amount' => $amount
            ];
        }

        // Last 7 days contracts
        $last7DaysContracts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Contract::whereDate('contract_date', $date)
                ->whereNotIn('stage', ['terminated'])
                ->count();
            $last7DaysContracts[] = [
                'date' => $date->format('M d'),
                'count' => $count
            ];
        }

        // Monthly comparison (current vs previous month)
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $previousMonthPayments = Payment::whereBetween('payment_date', [$previousMonthStart, $previousMonthEnd])
            ->where('approved', true)
            ->sum('payment_amount');

        $previousMonthContracts = Contract::whereBetween('contract_date', [$previousMonthStart, $previousMonthEnd])
            ->whereNotIn('stage', ['terminated'])
            ->count();

        // Recent activities
        $recentPayments = Payment::with(['contract.customer', 'contract.building'])
            ->where('approved', true)
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();

        $recentContracts = Contract::with(['customer', 'building'])
            ->whereNotIn('stage', ['terminated'])
            ->orderBy('contract_date', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            // Contracts
            'contractsToday',
            'contractsWeek',
            'contractsMonth',
            'contractAmountMonth',
            'totalActiveContracts',
            'contractsByStage',
            // Payments
            'paymentsToday',
            'paymentsTodayAmount',
            'paymentsWeek',
            'paymentsWeekAmount',
            'paymentsMonth',
            'paymentsMonthAmount',
            'pendingPayments',
            // Services
            'servicesToday',
            'servicesWeek',
            'servicesMonth',
            'servicesAmountMonth',
            // Due Installments
            'dueInstallmentsToday',
            'dueInstallmentsTodayAmount',
            'dueInstallmentsWeek',
            // Expenses & Income
            'expensesToday',
            'expensesWeek',
            'expensesMonth',
            'incomeToday',
            'incomeWeek',
            'incomeMonth',
            // Cash & Employees
            'cashAccounts',
            'totalCashBalance',
            'totalEmployees',
            // Chart data
            'last7DaysPayments',
            'last7DaysContracts',
            'previousMonthPayments',
            'previousMonthContracts',
            // Recent activities
            'recentPayments',
            'recentContracts'
        ));
    }
}
