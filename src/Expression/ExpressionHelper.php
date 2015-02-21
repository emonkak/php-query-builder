<?php

namespace Emonkak\QueryBuilder\Expression;

trait ExpressionHelper
{
    public function add($rhs)
    {
        return new Operator('+', $this, ExpressionResolver::get($rhs));
    }

    public function sub($rhs)
    {
        return new Operator('-', $this, ExpressionResolver::get($rhs));
    }

    public function mul($rhs)
    {
        return new Operator('*', $this, ExpressionResolver::get($rhs));
    }

    public function div($rhs)
    {
        return new Operator('/', $this, ExpressionResolver::get($rhs));
    }

    public function eq($rhs)
    {
        return new Operator('=', $this, ExpressionResolver::get($rhs));
    }

    public function notEq($rhs)
    {
        return new Operator('<>', $this, ExpressionResolver::get($rhs));
    }

    public function lt($rhs)
    {
        return new Operator('<', $this, ExpressionResolver::get($rhs));
    }

    public function ltEq($rhs)
    {
        return new Operator('<=', $this, ExpressionResolver::get($rhs));
    }

    public function gt($rhs)
    {
        return new Operator('>', $this, ExpressionResolver::get($rhs));
    }

    public function gtEq($rhs)
    {
        return new Operator('>=', $this, ExpressionResolver::get($rhs));
    }

    public function in($rhs)
    {
        return new Operator('IN', $this, ExpressionResolver::get($rhs));
    }

    public function notIn($rhs)
    {
        return new Operator('NOT IN', $this, ExpressionResolver::get($rhs));
    }

    public function _and($rhs)
    {
        return new Operator('AND', $this, ExpressionResolver::get($rhs));
    }

    public function _or($rhs)
    {
        return new Operator('OR', $this, ExpressionResolver::get($rhs));
    }

    public function isNull($rhs)
    {
        return new Operator('IS NULL', $this, ExpressionResolver::get($rhs));
    }

    public function isNotNull($rhs)
    {
        return new Operator('IS NOT NULL', $this, ExpressionResolver::get($rhs));
    }

    public function like($rhs)
    {
        return new Operator('LIKE', $this, ExpressionResolver::get($rhs));
    }

    public function notLike($rhs)
    {
        return new Operator('NOT LIKE', $this, ExpressionResolver::get($rhs));
    }

    public function not()
    {
        return new UnaryOperator('NOT', $this);
    }

    public function exists()
    {
        return new UnaryOperator('EXISTS', $this);
    }

    public function notExists()
    {
        return new UnaryOperator('NOT EXISTS', $this);
    }

    public function all()
    {
        return new UnaryOperator('ALL', $this);
    }

    public function notAll()
    {
        return new UnaryOperator('NOT ALL', $this);
    }

    public function any()
    {
        return new UnaryOperator('ANY', $this);
    }

    public function notAny()
    {
        return new UnaryOperator('NOT ANY', $this);
    }

    public function some()
    {
        return new UnaryOperator('SOME', $this);
    }

    public function notSome()
    {
        return new UnaryOperator('NOT SOME', $this);
    }

    public function avg()
    {
        return new Func('AVG', $this);
    }

    public function count()
    {
        return new Func('COUNT', $this);
    }

    public function max()
    {
        return new Func('MAX', $this);
    }

    public function min()
    {
        return new Func('MIN', $this);
    }

    public function sum()
    {
        return new Func('SUM', $this);
    }
}
