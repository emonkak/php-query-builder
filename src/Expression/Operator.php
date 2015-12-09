<?php

namespace Emonkak\QueryBuilder\Expression;

class Operator implements ExpressionInterface
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
    private $rhs;

    /**
     * @param string              $operator
     * @param ExpressionInterface $lhs
     * @param ExpressionInterface $rhs
     */
    public function __construct($operator, ExpressionInterface $lhs, ExpressionInterface $rhs)
    {
        $this->operator = $operator;
        $this->lhs = $lhs;
        $this->rhs = $rhs;
    }

    /**
     * {@inheritDoc}
     */
    public function compile()
    {
        list ($lhsSql, $lhsBinds) = $this->lhs->compile();
        list ($rhsSql, $rhsBinds) = $this->rhs->compile();
        return ["($lhsSql $this->operator $rhsSql)", array_merge($lhsBinds, $rhsBinds)];
    }
}
