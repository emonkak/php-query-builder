<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryInterface;
use Emonkak\QueryBuilder\SelectBuilder;

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
     * @return ExpressionInterface
     */
    public static function resolveValue($value)
    {
        if (is_scalar($value)) {
            return new Value($value);
        }
        if (is_array($value)) {
            return new Values($value);
        }
        if ($value instanceof QueryInterface) {
            return new Query($value);
        }
        if ($value instanceof ExpressionInterface) {
            return $value;
        }
        if ($value instanceof \Closure) {
            return self::resolveValue($value(function() {
                return self::resolveCreteria(func_get_args());
            }));
        }
        $type = gettype($src);
        throw new \InvalidArgumentException("Invalid creteria, got '$type'.");
    }

    /**
     * @param mixed $creteria
     * @return ExpressionInterface
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
     * @return ExpressionInterface
     */
    private static function resolveSingleCreteria($first)
    {
        if (is_string($first)) {
            return new Raw($first, []);
        }
        return self::resolveValue($first);
    }

    /**
     * @param $first  string
     * @param $second mixed
     * @return ExpressionInterface
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
            return new PrefixOperator($first, self::resolveValue($second));
        case 'IS NULL';
        case 'IS NOT NULL';
            return new PostfixOperator($first, self::resolveValue($second));
        }

        if (is_array($second)) {
            return new Raw($first, $second);
        } else {
            return new Operator('=', $first, self::resolveValue($second));
        }
    }

    /**
     * @param $first  string
     * @param $second string
     * @param $third  mixed
     * @return ExpressionInterface
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
            $lhs = new Raw($first, []);
            $rhs = self::resolveValue($third);
            return new Operator($second, $lhs, $rhs);
        case 'BETWEEN':
        case 'NOT BETWEEN':
            $lhs = new Raw($first, []);
            $min = self::resolveValue($third[0]);
            $max = self::resolveValue($third[1]);
            return new BetweenOperator($second, $lhs, $min, $max);
        }
        throw new \InvalidArgumentException("Invalid operator, got '$second'.");
    }
}
