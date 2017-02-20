<?php

namespace Unified\Models;

use Eloquent;

class GuacamoleConnection extends Eloquent {

    protected $table = 'guacamole_connection';

    protected $fillable = ['connection_name','parent_id','protocol','max_connections','max_connections_per_user'];

    public $timestamps = false;
}
