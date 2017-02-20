<?php namespace Unified\Models;

use Eloquent;

class Group extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_group';

    public $timestamps = false;
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //we want to show the device's password
    //protected $hidden = array('password');
    
    // group __belongs_to__ Node
    public function networkTree()
    {
        return $this->belongsTo('NetworkTree');
    }

}
