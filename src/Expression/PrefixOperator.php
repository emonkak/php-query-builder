<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryFragmentInterface;

class PrefixOperator implements QueryFragmentInterface
{
    use ExpressionHelpers;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var QueryFragmentInterface
     */
    private $value;

    /**
     * @param string                 $operator
     * @param QueryFragmentInterface $value
     */
    public function __construct($operator, QueryFragmentInterface $value)
    {
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        list ($sql, $binds) = $this->value->build();
        return ["($this->operator $sql)", $binds];
    }
}
