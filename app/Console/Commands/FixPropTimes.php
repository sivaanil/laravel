<?php

namespace Unified\Console\Commands;

use App;
use Illuminate\Console\Command;

/**
 * CLI handler to fix property time clustering as related to bug 5695
 * This should be run every 30 minutes via the scheduler.
 *
 * @author Anthony Levensalor <anthony.levensalor@csquaredsystems.com>
 */
class FixPropTimes extends Command
{

    /**
     * @var String 
     */
    protected $name = 'csquared:FixPropTimes';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {

        $service = App::make('\Unified\Services\FixPropTimes');
        if ($service->process()) {
            $this->info('Fixed Prop times');
        } else {
            $this->info('Unable to fix prop times');
        }
    }

}


