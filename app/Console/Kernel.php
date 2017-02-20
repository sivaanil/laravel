<?php

namespace Unified\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;

class Kernel extends ConsoleKernel
{

    /**
     * Create a new HTTP kernel instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @param  \Illuminate\Routing\Router                   $router
     *
     * @return void
     */
    public function __construct(Application $app, Dispatcher $events)
    {
        parent::__construct($app, $events);

        // replace the log config with our own
        array_walk($this->bootstrappers, function (&$bootstrapper) {
            if ($bootstrapper === 'Illuminate\Foundation\Bootstrap\ConfigureLogging') {
                $bootstrapper = 'Unified\Config\ConfigureLogging';
            }
        });
    }

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //'Unified\Console\Commands\Inspire',
        'Unified\Console\Commands\Backup',
        'Unified\Console\Commands\GeneratorTest',
        'Unified\Console\Commands\Test\GetTimeTest',
        'Unified\Console\Commands\QueueScheduledTasks',
        //'Unified\Console\Commands\BuildInfo',
        //'Unified\Console\Commands\BuildQueuedDevices',
        //'Unified\Console\Commands\BuildQueuedDevicesError',
        //'Unified\Console\Commands\CameraStill',
        //'Unified\Console\Commands\CheckNotificationHours',
        //'Unified\Console\Commands\CleanupScanImages',
        'Unified\Console\Commands\ClearStuckScans',
        //'Unified\Console\Commands\Heartbeat',
        //'Unified\Console\Commands\ProcessDelayedAlarms',
        //'Unified\Console\Commands\ProcessLogout',
        //'Unified\Console\Commands\ProcessMail',
        //'Unified\Console\Commands\ProcessPhones',
        //'Unified\Console\Commands\ProcessPop3',
        //'Unified\Console\Commands\ProcessReports',
        'Unified\Console\Commands\ProcessSnmpQueue',
        'Unified\Console\Commands\ProcessSnmpTrap',
        //'Unified\Console\Commands\ProcessTickets',
        'Unified\Console\Commands\Rollback',
        'Unified\Console\Commands\ScanDevices',
        //'Unified\Console\Commands\SendNotifications',
        'Unified\Console\Commands\ServiceRestart',
        'Unified\Console\Commands\SystemMonitor',
        'Unified\Console\Commands\SystemStats',
        'Unified\Console\Commands\TimezoneMonitor',
        'Unified\Console\Commands\TrapControl',
        //'Unified\Console\Commands\Upgrade',
        'Unified\Console\Commands\UpgradeCheck',
        'Unified\Console\Commands\Browser\SetUrl',
        'Unified\Console\Commands\Browser\KillVnc',
        'Unified\Console\Commands\Browser\KillAllVnc',
        'Unified\Console\Commands\Browser\ResetGuest',
        'Unified\Console\Commands\Browser\GarbageCollect',
        'Unified\Console\Commands\OnReboot',
        'Unified\Console\Commands\FixPropTimes',
        'Unified\Console\Commands\ScanSelf',
        'Unified\Console\Commands\SetTimezone',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //todo Check Crons
        If (env('C2_SERVER_TYPE') == 'sitegate') {
            $schedule->command('csquared:backup')->dailyAt(env('BACKUP_TIME'));  // Runs as root
            //$schedule->command('csquared:build-queued-devices')->cron('*/15 * * * *');
            //$schedule->command('csquared:build-queued-error')->cron('*/15 * * * *');
            //$schedule->command('csquared:check-upgrade')->daily(); // Runs as root
            //$schedule->command('csquared:cleanup-scan-images')->everyMinute();
            //$schedule->command('csquared:heartbeat')->cron('*/15 * * * *'); // Runs as root
            //$schedule->command('csquared:info')->hourly();
            //$schedule->command('csquared:notification-hours')->twiceDaily();
            //$schedule->command('csquared:process-delayed-alarms')->everyMinute();
            //$schedule->command('csquared:process-logout')->everyMinute();
            //$schedule->command('csquared:process-mail')->everyMinute();
            //$schedule->command('csquared:process-phones')->everyMinute();
            //$schedule->command('csquared:process-pop3')->everyMinute();
            $schedule->command('csquared:process-snmp-queue')->everyMinute();
            $schedule->command('csquared:process-snmp-trap')->everyMinute();
            //$schedule->command('csquared:process-tickets')->everyMinute();
            $schedule->command('csquared:scan-devices')->everyMinute();
            //$schedule->command('csquared:send-notifications')->everyMinute();
            $schedule->command('csquared:service-restart')->dailyAt('06:30'); // Runs as root
            $schedule->command('csquared:stuck-scans')->cron('0 */2 * * *');  // ??????
            //$schedule->command('csquared:sysmon')->everyMinute();             // Runs as root
            $schedule->command('csquared:sysstat')->everyMinute();            // Runs as root
            //$schedule->command('csquared:take-still')->everyFiveMinutes();
            $schedule->command('csquared:timezone-monitor')->dailyAt('02:30'); // Runs as root
            $schedule->command('csquared:trap-control')->everyMinute();       // Runs as root
            $schedule->command('csquared:resetguest')->dailyAt('04:25');       // Runs as root
            $schedule->command('csquared:guac-gc')->everyMinute();
            $schedule->command('csquared:FixPropTimes')->everyThirtyMinutes();
            $schedule->command('csquared:on-reboot')->everyMinute();
            $schedule->command('csquared:scan-self')->hourly();
            $schedule->command('csquared:QueueScheduledTasks')->cron('* * * * *');   // Run constantly
        } else {

        }
        //$schedule->command('inspire')->hourly();
    }

}
