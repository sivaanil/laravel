<?php

namespace Unified\Models;
use Eloquent;
use Unified\Http\Helpers\QueryParameters;

/**
 * Alarm severity model.
 */
final class AlarmSeverity extends Eloquent {
    use QueryTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_alarm_severity';

    /**
     * Returns list of alarm severities provided query parameters
     * @param QueryParameters $config
     * @return \Illuminate\Routing\Route
     */
    public static function getAlarmSeverities(QueryParameters $config) {
        return self::getRecords($config, get_called_class(), 'alarmSeverities');
    }
}
