<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Requisition;
use Illuminate\Console\Command;
use App\Mail\BookReturnReminderMail;
use Illuminate\Support\Facades\Mail;

class SendBookReturnReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-book-return-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to users with books due tomorrow for return';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $requisitions = Requisition::whereDate('end_date', $tomorrow)
            ->whereNull('actual_return_date')
            ->with(['user', 'book'])
            ->get();



        foreach ($requisitions as $requisition) {
            if ($requisition->user && $requisition->user->email) {
                Mail::to($requisition->user->email)->send(new BookReturnReminderMail($requisition));
            }
        }


        $this->info('Reminders sent for books to be returned tomorrow.');
    }
}
