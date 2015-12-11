<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryBuilderInterface;
use Emonkak\QueryBuilder\QueryFragmentInterface;
use Emonkak\QueryBuilder\SelectQueryBuilder;

class ExpressionResolver
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @param mixed $value
     * @return QueryFragmentInterface
     */
    public static function resolveAsValue($value)
    {
        if ($value === null) {
            return new NullValue();
        }
        if (is_scalar($value)) {
            return new Value($value);
        }
        if (is_array($value)) {
            return new Values($value);
        }
        if ($value instanceof QueryBuilderInterface) {
            return new SubQuery($value);
        }
        if ($value instanceof QueryFragmentInterface) {
            return $value;
        }
        if ($value instanceof \Closure) {
            return self::resolveAsValue($value(function() {
                return self::resolveCreteria(func_get_args());
            }));
        }
        $type = gettype($value);
        throw new \InvalidArgumentException("Invalid creteria, got '$type'.");
    }

    /**
     * @param mixed $value
     * @return QueryFragmentInterface
     */
    public static function resolveAsString($value)
    {
        if (is_string($value)) {
            return new Str($value, []);
        }
        return self::resolveAsValue($value);
    }

    /**
     * @param mixed $creteria
     * @return QueryFragmentInterface
     */
    public static function resolveCreteria($creteria)
    {
        if (is_array($creteria)) {
            switch (count($creteria)) {
            case 1:
                return self::resolveSingleCreteria($creteria[0]);
            case 2:
                return self::resolveDoubleCreteria($creteria[0], $creteria[1]);
            case 3:
                return self::resolveTripleCreteria($creteria[0], $creteria[1], $creteria[2]);
            default:
                throw new \InvalidArgumentException('The number of arguments is incorrect');
            }
        }
        return self::resolveSingleCreteria($creteria);
    }

    /**
     * @param $first mixed
     * @return QueryFragmentInterface
     */
    private static function resolveSingleCreteria($first)
    {
        return self::resolveAsString($first);
    }

    /**
     * @param $first  string
     * @param $second mixed
     * @return QueryFragmentInterface
     */
    private static function resolveDoubleCreteria($first, $second)
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
            return new PrefixOperator($first, self::resolveAsValue($second));
        }

        if (is_array($second)) {
            return new Raw($first, $second);
        } else {
            $lhs = self::resolveAsString($first);
            $rhs = self::resolveAsValue($second);
            return new Operator('=', $lhs, $rhs);
        }
    }

    /**
     * @param $first  string
     * @param $second string
     * @param $third  mixed
     * @return QueryFragmentInterface
     */
    private static function resolveTripleCreteria($first, $second, $third)
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
            $lhs = self::resolveAsString($first);
            $rhs = self::resolveAsValue($third);
            return new Operator($second, $lhs, $rhs);
        case 'BETWEEN':
        case 'NOT BETWEEN':
            $lhs = self::resolveAsString($first);
            $min = self::resolveAsValue($third[0]);
            $max = self::resolveAsValue($third[1]);
            return new BetweenOperator($second, $lhs, $min, $max);
        }
        throw new \InvalidArgumentException("Invalid operator, got '$second'.");
    }
}
