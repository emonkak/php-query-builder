<?php

namespace Emonkak\QueryBuilder\Expression;

trait ExpressionHelpers
{
    public function eq($rhs)
    {
        return new Operator('=', $this, ExpressionResolver::resolveValue($rhs));
    }

    public function notEq($rhs)
    {
        return new Operator('<>', $this, ExpressionResolver::resolveValue($rhs));
    }

    public function lt($rhs)
    {
        return new Operator('<', $this, ExpressionResolver::resolveValue($rhs));
    }

    public function ltEq($rhs)
    {
        return new Operator('<=', $this, ExpressionResolver::resolveValue($rhs));
    }

    public function gt($rhs)
    {
        return new Operator('>', $this, ExpressionResolver::resolveValue($rhs));
    }

    public function gtEq($rhs)
    {
        return new Operator('>=', $this, ExpressionResolver::resolveValue($rhs));
    }

    public function in($rhs)
    {
        return new Operator('IN', $this, ExpressionResolver::resolveValue($rhs));
    }

    public function notIn($rhs)
    {
        return new Operator('NOT IN', $this, ExpressionResolver::resolveValue($rhs));
    }

    public function between($min, $max)
    {
        return new BetweenOperator('BETWEEN', $this, ExpressionResolver::resolveValue($min), ExpressionResolver::resolveValue($max));
    }

    public function notBetween($rhs)
    {
        return new BetweenOperator('NOT BETWEEN', $this, ExpressionResolver::resolveValue($min), ExpressionResolver::resolveValue($max));
    }

    public function _and($rhs)
    {
        return new Operator('AND', $this, ExpressionResolver::resolveValue($rhs));
    }

    public function _or($rhs)
    {
        return new Operator('OR', $this, ExpressionResolver::resolveValue($rhs));
    }

    public function like($rhs)
    {
        return new Operator('LIKE', $this, ExpressionResolver::resolveValue($rhs));
    }

    public function notLike($rhs)
    {
        return new Operator('NOT LIKE', $this, ExpressionResolver::resolveValue($rhs));
    }

    public function regexp($rhs)
    {
        return new Operator('REGEXP', $this, ExpressionResolver::resolveValue($rhs));
    }

    public function notRegexp($rhs)
    {
        return new Operator('NOT REGEXP', $this, ExpressionResolver::resolveValue($rhs));
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
}
