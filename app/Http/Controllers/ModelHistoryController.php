<?php

namespace App\Http\Controllers;


use App\Models\ModelHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModelHistoryController extends Controller
{
    /**
     * Display all history logs for all models with pagination and search functionality.
     */


    public function index(Request $request)
    {
        // Fetch search query, date, and user filter from the request
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $userId = $request->input('user_id');

        // Get all users for the user filter dropdown
        $users = User::all();

        // Build query for history logs
        $query = ModelHistory::query();

        // If there's a search term, apply filters to the query
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('model_id', '=',  $search)
                    ->orWhere('model_type', 'like', '%' . $search . '%')
                    ->orWhere('note', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter by date range (ensure date comparison ignores time)
        if ($startDate && $endDate) {
            // Convert dates to Carbon instances, start of day for startDate, and end of day for endDate
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            // Only filter by start date (start of the day)
            $startDate = Carbon::parse($startDate)->startOfDay();
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            // Only filter by end date (end of the day)
            $endDate = Carbon::parse($endDate)->endOfDay();
            $query->where('created_at', '<=', $endDate);
        }

        // Filter by user ID
        if ($userId) {
            $query->whereHas('user', function ($q) use ($userId) {
                $q->where('id', $userId);
            });
        }

        // Paginate the results (e.g., 30 per page)
        $history = $query->paginate(30);

        // Pass the transformed history logs to the view with pagination
        return view('model_history.index', compact('history', 'search', 'startDate', 'endDate', 'userId', 'users'));
    }


    /**
     * Display history logs for the current user across all models with pagination.
     */
    public function userHistory(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');

        $query = ModelHistory::where('user_id', $userId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', '%' . $search . '%')
                    ->orWhere('model_type', 'like', '%' . $search . '%');
            });
        }

        $history = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('model_history.user_history', compact('history', 'search'));
    }
}
