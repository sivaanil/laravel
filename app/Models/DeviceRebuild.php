<?php namespace Unified\Models;

use Eloquent;

class DeviceRebuild extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_rebuild';

    protected $fillable = [''];

    public $timestamps = false;
}
