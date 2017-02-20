<?php namespace Unified\Models;

use Eloquent;
use DB;

class DeviceScan extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_scan';

    public $timestamps = false;

    public function getProgress($scanId) {
        return DB::table('css_networking_scan')
                ->select('scanning', 'progress', 'success', 'message', 'process_id')
                ->where('id', $scanId)
                ->first();
    }

    public static function isScanning($deviceId) {
        $scanResult = DB::table('css_networking_scan')
            ->select('id')
            ->where('device_id', '=', $deviceId)
            ->where('scanning', '=', 1)
            ->whereNull('end_timestamp')
            ->orderBy('id', 'DESC')
            ->first();

        return $scanResult;
    }
}
