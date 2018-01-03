<?php

namespace Scheduler\Job;

use DateTimeInterface;
use DateTimeZone;
use DateTime;

/**
 * Class Job
 * @package Job
 * @author Aleh Hutnikau, <goodnickoff@gmail.com>
 */
class Job implements JobInterface
{
    /** @var RRule */
    private $rRule;

    /** @var callable */
    private $callable;

    /**
     * Job constructor.
     * @param RRule $rRule - recurrence rules (@see https://github.com/simshaun/recurr)
     * @param callable $callable
     */
    public function __construct(RRule $rRule, callable $callable)
    {
        $this->rRule = $rRule;
        $this->callable = $callable;
    }

    /**
     * @return RRule
     */
    public function getRRule()
    {
        return $this->rRule;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @param string $rRule RRULE string
     * @param string|DateTimeInterface $startDate - @see DateTime supported formats
     * @param callable $callback
     * @param string|DateTimeZone $timezone - If $timezone is omitted, the current timezone will be used.
     * @return Job
     */
    public static function createFromString($rRule, $startDate, callable $callback, $timezone = null)
    {
        if (empty($timezone)) {
            $timezone = date_default_timezone_get();
        }
        if (is_string($timezone)) {
            $timezone = new \DateTimeZone($timezone);
        }
        if (!$startDate instanceof DateTimeInterface) {
            $startDate = new DateTime($startDate, $timezone);
        }
        $timezone = $startDate->getTimezone()->getName();
        $rRule = new RRule($rRule, $startDate, $timezone);
        return new self($rRule, $callback);
    }
}