<?php

namespace Emonkak\QueryBuilder\Expression;

class PostfixOperator implements ExpressionInterface
{
    use ExpressionHelpers;

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
        list ($sql, $binds) = $this->value->compile();
        return ["($sql $this->operator)", $binds];
    }
}
