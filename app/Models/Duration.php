<?php namespace Unified\Models;

use Eloquent;

class Duration extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_durations';

    public static function getMaxStopScanDuration()
    {
        $max = Duration::where('active', '=', '1')->where('duration_type', '=', 'Ignore_Alarm')->take(1)->get();
        if (count($max) > 0) {
            $maxDuration = $max[0]->duration_limit;

            if ($maxDuration == null) {
                $time = - 1;
            } else {
                $time = Duration::sec2dhm($maxDuration);
            }
        }

        return $time;
    }

    //formatted representation of a second value
    public static function sec2dhm($sec)
    {
        $time['max'] = $sec;
        if ($sec == 1966081804) {
            //Indefinatly
            $time['max'] = - 1;
            $time['extra'] = array('onclick' => "calc();");
            $time['months'] = array('val' => 0, 'disabled' => '');
            $time['weeks'] = array('val' => 0, 'disabled' => '');
            $time['days'] = array('val' => 0, 'disabled' => '');
            $time['hours'] = array('val' => 0, 'disabled' => '');
            $time['minutes'] = array('val' => 0, 'disabled' => '');
            $time['maxDisp'] = "Indefinatly";
        } else {
            $time['extra'] = array('onclick' => "calc();", 'disabled' => 'dosabled');
            $time['months'] = array('val' => 0, 'disabled' => '');
            $time['weeks'] = array('val' => 0, 'disabled' => '');
            $time['days'] = array('val' => floor($sec / 86400), 'disabled' => '');
            $time['hours'] = array('val' => floor((($sec % 86400) / 3600)), 'disabled' => '');
            $time['minutes'] = array('val' => floor(($sec % 3600) / 60), 'disabled' => '');
            $time['maxDisp'] = $time['days']['val'] . " days " . $time['hours']['val'] . ":" . str_pad($time['minutes']['val'],
                    2, "0", STR_PAD_LEFT) . " hours";
        }

        return $time;
    }
}
