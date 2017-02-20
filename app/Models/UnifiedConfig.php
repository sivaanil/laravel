<?php namespace Unified\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class UnifiedConfig extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'unified_config';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    public function getUnifiedConfig($name)
    {
        return DB::table('unified_config')->where('name', $name)->value('value');

    }

    public function setUnifiedConfig($name, $value)
    {
        return DB::table('unified_config')->where('name', $name)->update(['value' => $value]);

    }
}
