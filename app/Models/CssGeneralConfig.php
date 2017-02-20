<?php

namespace Unified\Models;
use Eloquent;

/**
 * css_general_config model 
 */
class CssGeneralConfig extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_general_config';
    public $timestamps = false;

    public static function getEntry($settingName) {
        return CssGeneralConfig::where('setting_name', $settingName)->first();
    }

}
