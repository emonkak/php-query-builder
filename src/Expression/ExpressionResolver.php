<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryInterface;

class ExpressionResolver
{
    private function __construct() {}

    /**
     * @param mixed $value
     * @return ExpressionInterface
     */
    public static function get($value)
    {
        if (is_scalar($value) || is_null($value)) {
            return new Value($value);
        }
        if (is_array($value)) {
            return new Values($value);
        }
        if ($value instanceof QueryInterface) {
            return new SubQuery($value);
        }
        if ($value instanceof ExpressionInterface) {
            return $value;
        }
        $type = gettype($src);
        throw new \InvalidArgumentException("Invalid expression, got '$type'.");
    }
}
