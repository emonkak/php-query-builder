<?php

namespace Emonkak\QueryBuilder\Expression;

class UnaryOperator
{
    use ExpressionHelper;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var ExpressionInterface
     */
    private $value;

    /**
     * @param string              $operator
     * @param ExpressionInterface $value
     */
    public function __construct($operator, ExpressionInterface $value)
    {
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function compile()
    {
        list ($sql, $binds) = $this->lhs->compile();
        return ["($this->operator $sql)", $binds];
    }
}
