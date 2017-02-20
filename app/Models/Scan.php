<?php
namespace Unified\Models;

use Eloquent;

class Scan extends Eloquent {


    protected $table = "css_networking_scan";

    public $timestamps = false;

    /**
     * Set the scanning flag to 0 for any device with scanning = 1
     */
    public static function clearScanningFlags() {
        self::where('scanning', '=', 1)->update(['scanning' => 0]);
    }

}
