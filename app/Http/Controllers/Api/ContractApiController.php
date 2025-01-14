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

    public function newsfeed()
    {
        // Sample news feed data with real test image URLs
        $newsItems = [
            [
                'text' => 'خبر 1: تم إطلاق مشروع جديد في المنطقة الشمالية.',
                'image' => 'https://images.unsplash.com/photo-1633114128174-2f8aa49759b0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80', // Real image URL
            ],
            [
                'text' => 'خبر 2: خصم 10% على العقود الموقعة هذا الشهر.',
                'image' => 'https://images.unsplash.com/photo-1695653422902-1bea566871c6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80', // Real image URL
            ],
            [
                'text' => 'خبر 3: ورشة عمل حول الاستثمار العقاري يوم الجمعة.',
                'image' => 'https://images.unsplash.com/photo-1695653422542-7d0b5b3b9d1f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80', // Real image URL
            ],
            [
                'text' => 'خبر 4: تم تسليم المبنى رقم 5 بالكامل.',
                'image' => 'https://images.unsplash.com/photo-1695653422902-1bea566871c6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80', // Real image URL
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $newsItems,
        ], 200);
    }
}
