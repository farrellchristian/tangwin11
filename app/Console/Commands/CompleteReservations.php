<?php
namespace App\Console\Commands;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CompleteReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:complete-reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis mengubah status reservasi yang sudah terlewati menjadi completed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now         = Carbon::now();
        $today       = $now->toDateString();
        $currentTime = $now->toTimeString();

        $updatedCount = Reservation::whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($today, $currentTime) {
                $query->where('booking_date', '<', $today)
                    ->orWhere(function ($q) use ($today, $currentTime) {
                        $q->where('booking_date', $today)
                            ->where('booking_time', '<=', $currentTime);
                    });
            })
            ->update(['status' => 'completed']);

        $this->info("Berhasil memperbarui {$updatedCount} reservasi menjadi 'completed'.");
    }
}
