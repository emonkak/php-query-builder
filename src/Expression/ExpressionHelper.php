<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\Creteria;

/**
 * @internal
 */
trait ExpressionHelper
{
    public function eq($rhs)
    {
        return new Operator('=', $this, Creteria::ofValue($rhs));
    }

    public function notEq($rhs)
    {
        return new Operator('<>', $this, Creteria::ofValue($rhs));
    }

    public function lt($rhs)
    {
        return new Operator('<', $this, Creteria::ofValue($rhs));
    }

    public function ltEq($rhs)
    {
        return new Operator('<=', $this, Creteria::ofValue($rhs));
    }

    public function gt($rhs)
    {
        return new Operator('>', $this, Creteria::ofValue($rhs));
    }

    public function gtEq($rhs)
    {
        return new Operator('>=', $this, Creteria::ofValue($rhs));
    }

    public function in($rhs)
    {
        return new Operator('IN', $this, Creteria::ofValue($rhs));
    }

    public function notIn($rhs)
    {
        return new Operator('NOT IN', $this, Creteria::ofValue($rhs));
    }

    public function between($min, $max)
    {
        return new BetweenOperator('BETWEEN', $this, Creteria::ofValue($min), Creteria::ofValue($max));
    }

    public function notBetween($rhs)
    {
        return new BetweenOperator('NOT BETWEEN', $this, Creteria::ofValue($min), Creteria::ofValue($max));
    }

    public function _and($rhs)
    {
        return new Operator('AND', $this, Creteria::ofValue($rhs));
    }

    public function _or($rhs)
    {
        return new Operator('OR', $this, Creteria::ofValue($rhs));
    }

    public function like($rhs)
    {
        return new Operator('LIKE', $this, Creteria::ofValue($rhs));
    }

    public function notLike($rhs)
    {
        return new Operator('NOT LIKE', $this, Creteria::ofValue($rhs));
    }

    public function regexp($rhs)
    {
        return new Operator('REGEXP', $this, Creteria::ofValue($rhs));
    }

    public function notRegexp($rhs)
    {
        return new Operator('NOT REGEXP', $this, Creteria::ofValue($rhs));
    }

    public function not()
    {
        return new PrefixOperator('NOT', $this);
    }

    public function exists()
    {
        return new PrefixOperator('EXISTS', $this);
    }

    public function notExists()
    {
        return new PrefixOperator('NOT EXISTS', $this);
    }

    public function all()
    {
        return new PrefixOperator('ALL', $this);
    }

    public function notAll()
    {
        return new PrefixOperator('NOT ALL', $this);
    }

    public function any()
    {
        return new PrefixOperator('ANY', $this);
    }

    public function notAny()
    {
        return new PrefixOperator('NOT ANY', $this);
    }

    public function some()
    {
        return new PrefixOperator('SOME', $this);
    }

    public function notSome()
    {
        return new PrefixOperator('NOT SOME', $this);
    }

    public function isNull()
    {
        return new PostfixOperator('IS NULL', $this);
    }

    public function isNotNull()
    {
        return new PostfixOperator('IS NOT NULL', $this);
    }

    public function call(array $args)
    {
        return new Func($this, array_map('Emonkak\QueryBuilder\Creteria::of', $args));
    }
}
