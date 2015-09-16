<?php

namespace Sofa\EloquentScopes\Tests;

use Mockery as m;
use Carbon\Carbon;
use Sofa\EloquentScopes\PeriodScopes;
use Illuminate\Database\Eloquent\Model;

class PeriodScopesTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function setUp()
    {
        parent::setUp();
        Carbon::setTestNow(new Carbon('2010-11-11 12:25:30'));
    }

    /** @test */
    public function lastPeriodsWithCurrent()
    {
        $query = $this->getModel()->newQuery()->periods('year', -2, true);

        $range = [
            Carbon::now()->subYears(2)->startOfYear(),
            Carbon::now()->endOfYear(),
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function lastPeriods()
    {
        $query = $this->getModel()->periods('year', -2);

        $range = [
            Carbon::now()->subYears(2)->startOfYear(),
            Carbon::now()->subYear()->endOfYear()
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function nextPeriodsWithCurrent()
    {
        $query = $this->getModel()->periods('year', 3, null, true);

        $range = [
            Carbon::now()->startOfYear(),
            Carbon::now()->addYears(3)->endOfYear()
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function nextPeriods()
    {
        $query = $this->getModel()->periods('year', 3);

        $range = [
            Carbon::now()->addYear()->startOfYear(),
            Carbon::now()->addYears(3)->endOfYear()
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function thisYearWithColumnProvided()
    {
        $query = $this->getModel()->thisYear('another_column');

        $range = [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear()
        ];
        $this->assertEquals('select * from "users" where "another_column" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function thisYearWithColumnSpecified()
    {
        $query = $this->getModel('ModelStubWithColumn')->thisYear();

        $range = [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear()
        ];
        $this->assertEquals('select * from "users" where "custom_timestamp" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function nextYear()
    {
        $query = $this->getModel()->nextYear();

        $range = [
            Carbon::parse('+1 year')->startOfYear(),
            Carbon::parse('+1 year')->endOfYear()
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function nextMonth()
    {
        $query = $this->getModel()->nextMonth();

        $range = [
            Carbon::parse('+1 month')->startOfMonth(),
            Carbon::parse('+1 month')->endOfMonth()
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function tomorrow()
    {
        $query = $this->getModel()->tomorrow();

        $range = [
            Carbon::tomorrow(),
            Carbon::tomorrow()->endOfDay()
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function nextHour()
    {
        $query = $this->getModel()->nextHour();

        $range = [
            Carbon::parse('+1 hour')->minute(0)->second(0),
            Carbon::parse('+1 hour')->minute(59)->second(59)
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function nextMinute()
    {
        $query = $this->getModel()->nextMinute();

        $range = [
            Carbon::parse('+1 minute')->second(0),
            Carbon::parse('+1 minute')->second(59)
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function thisYear()
    {
        $query = $this->getModel()->thisYear();

        $range = [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear()
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function thisMonth()
    {
        $query = $this->getModel()->thisMonth();

        $range = [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function today()
    {
        $query = $this->getModel()->today();

        $range = [
            Carbon::today(),
            Carbon::today()->endOfDay()
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function thisHour()
    {
        $query = $this->getModel()->thisHour();

        $range = [
            Carbon::now()->minute(0)->second(0),
            Carbon::now()->minute(59)->second(59)
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function lastYear()
    {
        $query = $this->getModel()->lastYear();

        $range = [
            Carbon::parse('-1 year')->startOfYear(),
            Carbon::parse('-1 year')->endOfYear()
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function lastMonth()
    {
        $query = $this->getModel()->lastMonth();

        $range = [
            Carbon::parse('-1 month')->startOfMonth(),
            Carbon::parse('-1 month')->endOfMonth()
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function yesterday()
    {
        $query = $this->getModel()->yesterday();

        $range = [
            Carbon::yesterday(),
            Carbon::yesterday()->endOfDay()
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function lastHour()
    {
        $query = $this->getModel()->lastHour();

        $range = [
            Carbon::parse('-1 hour')->minute(0)->second(0),
            Carbon::parse('-1 hour')->minute(59)->second(59)
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function lastMinute()
    {
        $query = $this->getModel()->lastMinute();

        $range = [
            Carbon::parse('-1 minute')->second(0),
            Carbon::parse('-1 minute')->second(59)
        ];
        $this->assertEquals('select * from "users" where "created_at" between ? and ?', $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    public function getModel($stub = 'ModelStub')
    {
        $stub = 'Sofa\EloquentScopes\Tests\\'.$stub;
        $model = new $stub;
        $grammarClass = 'Illuminate\Database\Query\Grammars\SQLiteGrammar';
        $processorClass = 'Illuminate\Database\Query\Processors\SQLiteProcessor';
        $grammar = new $grammarClass;
        $processor = new $processorClass;
        $schema = m::mock('StdClass');
        $connection = m::mock('Illuminate\Database\ConnectionInterface', ['getQueryGrammar' => $grammar, 'getPostProcessor' => $processor]);
        $connection->shouldReceive('getSchemaBuilder')->andReturn($schema);
        $resolver = m::mock('Illuminate\Database\ConnectionResolverInterface', ['connection' => $connection]);
        $stub::setConnectionResolver($resolver);
        return $model;
    }
}

class ModelStubWithColumn extends Model
{
    use PeriodScopes;
    const PERIOD_COLUMN = 'custom_timestamp';
    protected $table = 'users';
}

class ModelStub extends Model
{
    use PeriodScopes;
    protected $table = 'users';
}
