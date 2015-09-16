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
        $query = ModelStub::query()->periods('year', -2, true);

        $range = [
            Carbon::now()->subYears(2)->startOfYear(),
            Carbon::now()->endOfYear(),
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function lastPeriods()
    {
        $query = ModelStub::periods('year', -2);

        $range = [
            Carbon::now()->subYears(2)->startOfYear(),
            Carbon::now()->subYear()->endOfYear()
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function nextPeriodsWithCurrent()
    {
        $query = ModelStub::periods('year', 3, null, true);

        $range = [
            Carbon::now()->startOfYear(),
            Carbon::now()->addYears(3)->endOfYear()
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function nextPeriods()
    {
        $query = ModelStub::periods('year', 3);

        $range = [
            Carbon::now()->addYear()->startOfYear(),
            Carbon::now()->addYears(3)->endOfYear()
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function thisYearWithColumnProvided()
    {
        $query = ModelStub::thisYear('another_column');

        $range = [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear()
        ];
        $this->assertEquals("select * from `users` where `another_column` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function thisYearWithColumnSpecified()
    {
        $query = ModelStubWithColumn::thisYear();

        $range = [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear()
        ];
        $this->assertEquals("select * from `users` where `custom_timestamp` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function nextYear()
    {
        $query = ModelStub::nextYear();

        $range = [
            Carbon::parse('+1 year')->startOfYear(),
            Carbon::parse('+1 year')->endOfYear()
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function nextMonth()
    {
        $query = ModelStub::nextMonth();

        $range = [
            Carbon::parse('+1 month')->startOfMonth(),
            Carbon::parse('+1 month')->endOfMonth()
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function tomorrow()
    {
        $query = ModelStub::tomorrow();

        $range = [
            Carbon::tomorrow(),
            Carbon::tomorrow()->endOfDay()
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function nextHour()
    {
        $query = ModelStub::nextHour();

        $range = [
            Carbon::parse('+1 hour')->minute(0)->second(0),
            Carbon::parse('+1 hour')->minute(59)->second(59)
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function nextMinute()
    {
        $query = ModelStub::nextMinute();

        $range = [
            Carbon::parse('+1 minute')->second(0),
            Carbon::parse('+1 minute')->second(59)
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function thisYear()
    {
        $query = ModelStub::thisYear();

        $range = [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear()
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function thisMonth()
    {
        $query = ModelStub::thisMonth();

        $range = [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function today()
    {
        $query = ModelStub::today();

        $range = [
            Carbon::today(),
            Carbon::today()->endOfDay()
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function thisHour()
    {
        $query = ModelStub::thisHour();

        $range = [
            Carbon::now()->minute(0)->second(0),
            Carbon::now()->minute(59)->second(59)
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function lastYear()
    {
        $query = ModelStub::lastYear();

        $range = [
            Carbon::parse('-1 year')->startOfYear(),
            Carbon::parse('-1 year')->endOfYear()
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function lastMonth()
    {
        $query = ModelStub::lastMonth();

        $range = [
            Carbon::parse('-1 month')->startOfMonth(),
            Carbon::parse('-1 month')->endOfMonth()
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function yesterday()
    {
        $query = ModelStub::yesterday();

        $range = [
            Carbon::yesterday(),
            Carbon::yesterday()->endOfDay()
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function lastHour()
    {
        $query = ModelStub::lastHour();

        $range = [
            Carbon::parse('-1 hour')->minute(0)->second(0),
            Carbon::parse('-1 hour')->minute(59)->second(59)
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
    }

    /** @test */
    public function lastMinute()
    {
        $query = ModelStub::lastMinute();

        $range = [
            Carbon::parse('-1 minute')->second(0),
            Carbon::parse('-1 minute')->second(59)
        ];
        $this->assertEquals("select * from `users` where `created_at` between ? and ?", $query->toSql());
        $this->assertEquals($range, $query->getBindings());
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
