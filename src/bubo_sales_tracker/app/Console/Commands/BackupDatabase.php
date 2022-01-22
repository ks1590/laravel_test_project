<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $process = Process::fromShellCommandline("mysqldump -h ".Config::get('database.connections.mysql.host')." -P ".Config::get('database.connections.mysql.port')." --protocol=tcp -u".Config::get('database.connections.mysql.username')." -p".Config::get('database.connections.mysql.password')." ".Config::get('database.connections.mysql.database')." | gzip > ".storage_path('app/public/backups/'.Carbon::now()->format('Ymd_Hi').'.dump.gz'));
        $process->setTimeout(1800);

        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $files = Storage::disk('public')->files('backups');
        if (count($files) > 14) {
            Storage::disk('public')->delete($files[0]);
        }
    }
}
