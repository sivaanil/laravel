<?php

namespace Unified\Models;

use Eloquent;

class GuacamoleUser extends Eloquent {

    protected $table = 'guacamole_user';

    protected $fillable = ['username','password_hash','password_salt','disabled','expired','access_window_start','access_window_end','valid_from','valid_until','timezone'];

    public $timestamps = false;
}
