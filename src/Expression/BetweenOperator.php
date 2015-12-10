<?php

namespace Emonkak\QueryBuilder\Expression;

class BetweenOperator implements ExpressionInterface
{
    use ExpressionHelpers;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var ExpressionInterface
     */
    private $lhs;

    /**
     * @var ExpressionInterface
     */
    private $min;

    /**
     * @var ExpressionInterface
     */
    private $max;

    /**
     * @param string              $operator
     * @param ExpressionInterface $lhs
     * @param ExpressionInterface $min
     * @param ExpressionInterface $max
     */
    public function __construct($operator, ExpressionInterface $lhs, ExpressionInterface $min, ExpressionInterface $max)
    {
        $this->operator = $operator;
        $this->lhs = $lhs;
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        list ($lhsSql, $lhsBinds) = $this->lhs->build();
        list ($minSql, $minBinds) = $this->min->build();
        list ($maxSql, $maxBinds) = $this->max->build();
        return ["($lhsSql $this->operator $minSql AND $maxSql)", array_merge($lhsBinds, $minBinds, $maxBinds)];
    }
}
