<?php

namespace Unified\Models;

use Eloquent;

class GuacamoleConnectionPermission extends Eloquent {
    protected $table = "guacamole_connection_permission";

    protected $fillable = ['user_id','connection_id','permission'];

    public $timestamps = false;

}
