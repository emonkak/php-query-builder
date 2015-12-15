<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Expression\BetweenOperator;
use Emonkak\QueryBuilder\Expression\NullValue;
use Emonkak\QueryBuilder\Expression\Operator;
use Emonkak\QueryBuilder\Expression\PrefixOperator;
use Emonkak\QueryBuilder\Expression\Raw;
use Emonkak\QueryBuilder\Expression\Str;
use Emonkak\QueryBuilder\Expression\SubQuery;
use Emonkak\QueryBuilder\Expression\Value;
use Emonkak\QueryBuilder\Expression\Values;

class Creteria
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @param mixed ...$args
     * @return QueryFragmentInterface
     */
    public static function of($creteria)
    {
        if (!is_array($creteria)) {
            $creteria = func_get_args();
        }

        switch (count($creteria)) {
        case 1:
            return self::ofSingle($creteria[0]);
        case 2:
            return self::ofDouble($creteria[0], $creteria[1]);
        case 3:
            return self::ofTriple($creteria[0], $creteria[1], $creteria[2]);
        }

        throw new \InvalidArgumentException('The number of arguments is incorrect');
    }

    /**
     * @param mixed $value
     * @return QueryFragmentInterface
     */
    public static function ofValue($value)
    {
        if ($value === null) {
            return new NullValue();
        }
        if (is_scalar($value)) {
            return new Value($value);
        }
        if (is_array($value)) {
            return new Values(array_map('self::ofValue', $value));
        }
        if ($value instanceof QueryBuilderInterface) {
            return new SubQuery($value);
        }
        if ($value instanceof QueryFragmentInterface) {
            return $value;
        }
        if ($value instanceof \Closure) {
            return self::ofValue($value(function() {
                return self::of(func_get_args());
            }));
        }
        $type = gettype($value);
        throw new \InvalidArgumentException("Invalid creteria, got '$type'.");
    }

    /**
     * @param mixed $first
     * @return QueryFragmentInterface
     */
    private static function ofSingle($first)
    {
        if (is_string($first)) {
            return new Str($first);
        }
        return self::ofValue($first);
    }

    /**
     * @param string $first
     * @param mixed  $second
     * @return QueryFragmentInterface
     */
    private static function ofDouble($first, $second)
    {
        switch ($first) {
        case 'ALL';
        case 'NOT ALL';
        case 'ANY';
        case 'NOT ANY';
        case 'SOME';
        case 'NOT SOME';
        case 'EXISTS';
        case 'NOT EXISTS';
            return new PrefixOperator($first, self::ofValue($second));
        }

        if (is_array($second)) {
            return new Raw($first, $second);
        } else {
            $lhs = self::ofSingle($first);
            $rhs = self::ofValue($second);
            return new Operator('=', $lhs, $rhs);
        }
    }

    /**
     * @param string $first
     * @param string $second
     * @param mixed  $third
     * @return QueryFragmentInterface
     */
    private static function ofTriple($first, $second, $third)
    {
        switch ($second) {
        case '=':
        case '!=':
        case '<>':
        case '<=>':
        case '<':
        case '<=':
        case '!<':
        case '>':
        case '>=':
        case '!>':
        case 'IN':
        case 'NOT IN':
        case 'LIKE':
        case 'NOT LIKE':
        case 'REGEXP':
        case 'NOT REGEXP':
        case 'IS':
        case 'IS NOT':
            $lhs = self::ofSingle($first);
            $rhs = self::ofValue($third);
            return new Operator($second, $lhs, $rhs);
        case 'BETWEEN':
        case 'NOT BETWEEN':
            $lhs = self::ofSingle($first);
            $min = self::ofValue($third[0]);
            $max = self::ofValue($third[1]);
            return new BetweenOperator($second, $lhs, $min, $max);
        }
        throw new \InvalidArgumentException("Invalid operator, got '$second'.");
    }
}
