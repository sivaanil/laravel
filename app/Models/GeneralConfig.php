<?php

namespace Unified\Models;

use Eloquent;

class GeneralConfig extends Eloquent {

    protected $table = 'css_general_config';

    public $timestamps = false;

    /**
     * Reset all config settings that begin with 'processins_'
     * to 0
     */
    public static function resetProcessFlags() {
       self::where('setting_name', 'like', 'processing_%')->update(['var1' => 0]);
    }
}
