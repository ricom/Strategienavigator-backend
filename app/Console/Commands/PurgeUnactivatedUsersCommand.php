<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PurgeUnactivatedUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purge:unactivated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all user accounts which haven\'t activated their accounts for a long time';

    /**
     * Setzt Zeitpunkt fest vor den inaktiven User gelöscht werden.
     *
     * @var Carbon
     */
    public static $purgedBefore;

    /**
     * Alle nicht aktivierte nutzer, welche vor dem übergebenen Datum ihre letzte Aktivität hatten, werden gelöscht
     * @param Carbon $date Muss in der Vergangenheit liegen
     */
    public static function userPurgedBefore(Carbon $date)
    {
        if ($date->isPast())
            static::$purgedBefore = $date;
    }

    /**
     * Gibt den Wert von $purgedBefore oder den Zeitpunkt vor 2 Wochen zurück
     * @return Carbon
     * @see $purgedBefore
     */
    public static function getUserPurgedBeforeTime()
    {
        return static::$purgedBefore ?: Carbon::now()->subWeeks(2);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $count = User::whereAnonymous(false)
            ->whereEmail(null)
            ->where('created_at', '<', static::getUserPurgedBeforeTime())
            ->forceDelete();
        // Verification table is deleted by onDelete Cascade.
        $this->output->success("Deleted " . $count . " rows!");
        return Command::SUCCESS;
    }
}
