<?php

namespace App\Http\Controllers;

use App\Models\Building\Building;
use App\Models\ModelHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModelHistoryController extends Controller
{
    /**
     * Display all history logs for all models with pagination and search functionality.
     */
    public function index(Request $request)
    {
        // Fetch search query from the request
        $search = $request->input('search');

        // Build query for history logs
        $query = ModelHistory::query();

        // If there's a search term, apply filters to the query
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('model_id', '=',  $search)
                    ->orWhere('model_type', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Paginate the results (e.g., 10 per page)
        $history = $query->paginate(10);

        // Transform the old and new data for each log
        foreach ($history as $log) {
            $oldData = is_string($log->old_data) ? json_decode($log->old_data, true) : $log->old_data;
            $newData = is_string($log->new_data) ? json_decode($log->new_data, true) : $log->new_data;

            // Check if the old_data contains 'user_id' and replace with the user's name
            if (isset($oldData['user_id'])) {
                $user = User::find($oldData['user_id']);
                $oldData['user'] = $user ? $user->name : 'Unknown';
                unset($oldData['user_id']);
            }

            // Check if the new_data contains 'user_id' and replace with the user's name
            if (isset($newData['user_id'])) {
                $user = User::find($newData['user_id']);
                $newData['user'] = $user ? $user->name : 'Unknown';
                unset($newData['user_id']);
            }

            // Check if the old_data contains 'contract_building_id' and replace with building number
            if (isset($oldData['contract_building_id'])) {
                $building = Building::find($oldData['contract_building_id']);
                if ($building) {
                    $oldData['building_number'] = $building->building_number;
                    unset($oldData['contract_building_id']);
                }
            }

            // Check if the new_data contains 'contract_building_id' and replace with building number
            if (isset($newData['contract_building_id'])) {
                $building = Building::find($newData['contract_building_id']);
                if ($building) {
                    $newData['building_number'] = $building->building_number;
                    unset($newData['contract_building_id']);
                }
            }

            // Update the log with the transformed data (encoded as JSON)
            $log->old_data = json_encode($oldData, JSON_PRETTY_PRINT);
            $log->new_data = json_encode($newData, JSON_PRETTY_PRINT);
            $log->save();
        }

        // Pass the transformed history logs to the view with pagination
        return view('model_history.index', compact('history', 'search'));
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
