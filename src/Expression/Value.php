<?php

namespace Emonkak\QueryBuilder\Expression;

class Value implements ExpressionInterface
{
    use ExpressionHelper;

    /**
     * @var mixed $value
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function compile()
    {
        return ['?', [$this->value]];
    }
}
