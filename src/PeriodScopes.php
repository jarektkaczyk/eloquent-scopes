<?php

namespace Sofa\EloquentScopes;

/**
 * @method \Illuminate\Database\Eloquent\Builder nextYear($column = null)    Next Year query scope
 * @method \Illuminate\Database\Eloquent\Builder thisYear($column = null)    This Year query scope
 * @method \Illuminate\Database\Eloquent\Builder lastYear($column = null)    Last Year query scope
 * @method \Illuminate\Database\Eloquent\Builder nextMonth($column = null)   Next Month query scope
 * @method \Illuminate\Database\Eloquent\Builder thisMonth($column = null)   This Month query scope
 * @method \Illuminate\Database\Eloquent\Builder lastMonth($column = null)   Last Month query scope
 * @method \Illuminate\Database\Eloquent\Builder tomorrow($column = null)    Tomorrow query scope
 * @method \Illuminate\Database\Eloquent\Builder today($column = null)       Today query scope
 * @method \Illuminate\Database\Eloquent\Builder yesterday($column = null)   Yesterday query scope
 * @method \Illuminate\Database\Eloquent\Builder nextHour($column = null)    Next Hour query scope
 * @method \Illuminate\Database\Eloquent\Builder thisHour($column = null)    This Hour query scope
 * @method \Illuminate\Database\Eloquent\Builder lastHour($column = null)    Last Hour query scope
 * @method \Illuminate\Database\Eloquent\Builder nextMinute($column = null)  Next Minute query scope
 * @method \Illuminate\Database\Eloquent\Builder thisMinute($column = null)  This Minute query scope
 * @method \Illuminate\Database\Eloquent\Builder lastMinute($column = null)  Last Minute query scope
 * @method \Illuminate\Database\Eloquent\Builder periods(string $unit, integer $periods, $column = null, boolean $includeCurrent = false)  Last/next preiods query scope
 */
trait PeriodScopes
{
    /**
     * Boot scope on the model.
     *
     * @return void
     */
    public static function bootPeriodScopes()
    {
        static::addGlobalScope(new Scopes\Period);
    }

    /**
     * Get column used by period (last/this/next...) methods with fallback to created_at timestamp.
     *
     * @return string
     */
    public static function getPeriodColumnName()
    {
        return (defined('static::PERIOD_COLUMN')) ? static::PERIOD_COLUMN : static::CREATED_AT;
    }
}
