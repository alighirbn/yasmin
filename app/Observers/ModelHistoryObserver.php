<?php

namespace App\Observers;

use App\Models\ModelHistory;

class ModelHistoryObserver
{
    // Capture creation
    public function creating($model)
    {
        ModelHistory::create([
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'action' => 'add',
            'new_data' => $model->getAttributes(),  // Store full attributes as "new_data"
            'user_id' => auth()->id(),
        ]);
    }

    // Capture updates (only store the changes, not the entire data)
    public function updating($model)
    {
        // Get the changes
        $changes = [];
        foreach ($model->getDirty() as $key => $value) {
            $changes[$key] = [
                'old' => $model->getOriginal($key),
                'new' => $value
            ];
        }

        // Store only the changes made, not the entire model data
        ModelHistory::create([
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'action' => 'edit',
            'old_data' => $changes, // Store the specific changes made
            'new_data' => $model->getDirty(),  // Store the new (dirty) data
            'user_id' => auth()->id(),
        ]);
    }

    // Capture deletions
    public function deleting($model)
    {
        ModelHistory::create([
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'action' => 'delete',
            'old_data' => $model->getAttributes(),  // Store full attributes as "old_data"
            'user_id' => auth()->id(),
        ]);
    }
}
