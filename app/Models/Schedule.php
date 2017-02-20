<?php

namespace Unified\Models;

use Eloquent;

class Schedule extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'data_scheduled_tasks';
    public $timestamps = false;

}
