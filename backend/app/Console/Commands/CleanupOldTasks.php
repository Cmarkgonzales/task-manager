<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanupOldTasks extends Command
{
    protected $signature = 'tasks:cleanup';
    protected $description = 'Delete tasks older than 30 days and log the deletions';

    public function handle()
    {
        $thresholdDate = Carbon::now()->subDays(30);

        $oldTasks = Task::where('created_at', '<', $thresholdDate)->get();

        if ($oldTasks->isEmpty()) {
            $this->info('No tasks to delete.');
            return;
        }

        foreach ($oldTasks as $task) {
            Log::info("Deleted Task [ID: {$task->id}] owned by User [ID: {$task->user_id}], Title: {$task->title}, Created At: {$task->created_at}");
            $task->delete();
        }

        $this->info('Deleted ' . $oldTasks->count() . ' tasks older than 30 days.');
    }
}
