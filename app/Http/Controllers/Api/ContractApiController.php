<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract\Contract;


class ContractApiController extends Controller
{
    // Fetch all contracts
    public function index()
    {
        $contracts = Contract::with('customer', 'building', 'payments', 'contract_installments')->get();

        return response()->json([
            'success' => true,
            'data' => $contracts,
        ], 200);
    }
    // Fetch a specific contract by ID
    public function show($id)
    {
        $contract = Contract::with('customer', 'building')->find($id);

        if (!$contract) {
            return response()->json([
                'success' => false,
                'message' => 'Contract not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $contract,
        ], 200);
    }

    // Create a new contract
    public function store(Request $request)
    {
        $validated = $request->validate([
            'building_number' => 'required|string',
            'contract_amount' => 'required|numeric',
            'contract_date' => 'required|date',
            'customer_full_name' => 'required|string',
        ]);

        $contract = Contract::create([
            'contract_amount' => $validated['contract_amount'],
            'contract_date' => $validated['contract_date'],
            'contract_customer_id' => 1, // Replace with appropriate customer ID
            'contract_building_id' => 1, // Replace with appropriate building ID
            'url_address' => $validated['building_number'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contract created successfully',
            'data' => $contract,
        ], 201);
    }
}
