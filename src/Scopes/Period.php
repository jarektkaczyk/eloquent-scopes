<?php

namespace Sofa\EloquentScopes\Scopes;

use Carbon\Carbon;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class Period implements ScopeInterface
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $query, Model $model)
    {
        $this->extend($query, $model);
    }

    /**
     * Extend builder instance with this|last[Period] methods.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    protected function extend(Builder $query, Model $model)
    {
        $this->registerMacro($query, $model);

        $this->registerHelpers($query);
    }

    /**
     * Register periods macro.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Model   $model
     * @return void
     */
    protected function registerMacro(Builder $query, Model $model)
    {
        /**
         * Query scope periods - filter this or last/next N periods
         *
         * @param  \Illuminate\Database\Eloquent\Builder $query
         * @param  string  $unit                                 Period type [minute|hour|day|week|month|year]
         * @param  integer $periods                              Number of past periods
         * @param  string  $column                               Column to match against
         * @param  integer $includeCurrent                       Whether to include current period in filter (additionally)
         * @return \Illuminate\Database\Eloquent\Builder
         *
         * @throws \InvalidArgumentException
         */
        $macro = function (Builder $query, $unit, $periods, $column = null, $includeCurrent = false) use ($model) {
            if (!in_array($unit, ['minute', 'hour', 'day', 'week', 'month', 'year'])) {
                throw new InvalidArgumentException('Invalid period type');
            }

            // Developer may pass $includeCurrent instead of $column
            if (func_num_args() == 4 && is_bool($column)) {
                $includeCurrent = $column;

                $column = null;
            }

            $column = ($column) ?: $model->getPeriodColumnName();

            $range = $this->getPeriodRange($unit, $periods, $includeCurrent);

            return $query->whereBetween($column, $range);
        };

        $query->macro('periods', $macro);
    }

    /**
     * Get dates range for the where between clause.
     *
     * @param  string  $unit
     * @param  integer $periods
     * @param  boolean $includeCurrent
     * @return \Carbon\Carbon[]
     */
    protected function getPeriodRange($unit, $periods, $includeCurrent)
    {
        // Here we have 2 timestamps - one is closer to now, the other is further
        // from now. Depending on whether a developer wants to include current
        // period in the filter or not, let's parse the params accordingly.
        $future = ($periods >= 0);

        if ($includeCurrent) {
            $closerDate = Carbon::now();
        } else {
            $keyword = ($future) ? 'next' : 'last';

            $closerDate = Carbon::parse("{$keyword} {$unit}");
        }

        $furtherDate = Carbon::now()->{'add'.$unit}($periods);

        $range = [
            $this->adjustTimestamp($closerDate, $unit, !$future),
            $this->adjustTimestamp($furtherDate, $unit, $future),
        ];

        usort($range, function ($closer, $further) {
            return $closer->format('U') > $further->format('U');
        });

        return $range;
    }

    /**
     * Adjust timestamps to make them beginning or end of the period.
     *
     * @param  \Carbon\Carbon $timestamp
     * @param  string  $unit
     * @param  boolean $endOf
     * @return \Carbon\Carbon
     */
    protected function adjustTimestamp(Carbon $timestamp, $unit, $endOf = false)
    {
        $ending = ($endOf) ? 59 : 0;

        $method = ($endOf) ? 'endOf' : 'startOf';

        switch ($unit) {
            case 'minute':
                $timestamp->second($ending);
                break;

            case 'hour':
                $timestamp->minute($ending)->second($ending);
                break;

            default:
                $timestamp->{$method.ucfirst($unit)}();
        }

        return $timestamp;
    }

    /**
     * Register handy helper macros.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    protected function registerHelpers(Builder $query)
    {
        $query->macro('thisYear', function (Builder $query, $column = null) {
            return $query->thisPeriod('year', $column);
        });

        $query->macro('thisMonth', function (Builder $query, $column = null) {
            return $query->thisPeriod('month', $column);
        });

        $query->macro('thisWeek', function (Builder $query, $column = null) {
            return $query->thisPeriod('week', $column);
        });

        $query->macro('today', function (Builder $query, $column = null) {
            return $query->thisPeriod('day', $column);
        });

        $query->macro('thisHour', function (Builder $query, $column = null) {
            return $query->thisPeriod('hour', $column);
        });

        $query->macro('thisMinute', function (Builder $query, $column = null) {
            return $query->thisPeriod('minute', $column);
        });

        $query->macro('nextYear', function (Builder $query, $column = null) {
            return $query->nextPeriod('year', $column);
        });

        $query->macro('nextMonth', function (Builder $query, $column = null) {
            return $query->nextPeriod('month', $column);
        });

        $query->macro('nextWeek', function (Builder $query, $column = null) {
            return $query->nextPeriod('week', $column);
        });

        $query->macro('tomorrow', function (Builder $query, $column = null) {
            return $query->nextPeriod('day', $column);
        });

        $query->macro('nextHour', function (Builder $query, $column = null) {
            return $query->nextPeriod('hour', $column);
        });

        $query->macro('nextMinute', function (Builder $query, $column = null) {
            return $query->nextPeriod('minute', $column);
        });

        $query->macro('lastYear', function (Builder $query, $column = null) {
            return $query->lastPeriod('year', $column);
        });

        $query->macro('lastMonth', function (Builder $query, $column = null) {
            return $query->lastPeriod('month', $column);
        });

        $query->macro('lastWeek', function (Builder $query, $column = null) {
            return $query->lastPeriod('week', $column);
        });

        $query->macro('yesterday', function (Builder $query, $column = null) {
            return $query->lastPeriod('day', $column);
        });

        $query->macro('lastHour', function (Builder $query, $column = null) {
            return $query->lastPeriod('hour', $column);
        });

        $query->macro('lastMinute', function (Builder $query, $column = null) {
            return $query->lastPeriod('minute', $column);
        });

        $query->macro('nextPeriod', function (Builder $query, $unit, $column = null) {
            return $query->periods($unit, 1, $column, false);
        });

        $query->macro('thisPeriod', function (Builder $query, $unit, $column = null) {
            return $query->periods($unit, 0, $column, true);
        });

        $query->macro('lastPeriod', function (Builder $query, $unit, $column = null) {
            return $query->periods($unit, -1, $column, false);
        });
    }

    /**
     * Remove the scope from given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function remove(Builder $query, Model $model)
    {
        // We don't need it, just to satisfy the interface.
    }
}
