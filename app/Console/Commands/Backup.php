<?php

namespace Unified\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Unified\Models\NetworkTreeMap;
use Unified\Models\UnifiedConfig;

class Backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csquared:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executes the backup shell script.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$this->call('down');
        //sleep(300);
        $result = $this->backup();
        //$this->call('csquared:upgrade');
        $this->resetFlags();
        $this->resetPermissions();

        return $result;
    }

    private function backup()
    {

        NetworkTreeMap::whereIn('id', [1, 2])->update(['deleted' => 0]);

        $date = Carbon::now()->format('Ymd_H-i-s');

        return exec('/bin/sh /var/www/' . env('CSWAPI_ENV') . '/cron/backup/backup_cswapi.sh ' . env('DB_ROOT_PASSWORD') . ' ' . env('DB_ROOT_USERNAME') . ' ' . env('DB_DATABASE') . ' > /var/log/siteportal/backup/backup_' . env('CSWAPI_ENV') . "_" . $date . '.log 2>&1');

    }

    private function resetFlags()
    {
        $unifiedConfig = new UnifiedConfig();
        $unifiedConfig->setUnifiedConfig("force_update", null);
        $unifiedConfig->setUnifiedConfig("process_update", null);
    }

    private function resetPermissions()
    {
        // due to a recent change in cswapi backup_cswapi.sh which rotates logs and causes the directory to be recreated nightly with wrong permissions
        return exec('chmod -R 777 /var/log/siteportal');
    }
}
