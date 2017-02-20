<?php

namespace Unified\Models;

use Eloquent;
use Unified\Http\Helpers\QueryParameters;

/**
 * Alarm rule type model.
 */
final class AlarmRuleType extends Eloquent {
    use QueryTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_alarm_rule_type';
    
    /**
     * Returns list of alarm rule types matching provided query parameters
     * 
     * @param QueryParameters $config            
     */
    public static function getAlarmRuleTypes(QueryParameters $config) {
        return self::getRecords($config, get_called_class(), 'alarmRuleTypes');
    }
}
