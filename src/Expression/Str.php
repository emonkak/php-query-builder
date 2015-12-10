<?php

namespace Emonkak\QueryBuilder\Expression;

class Str implements ExpressionInterface
{
    use ExpressionHelpers;

    /**
     * @var string
     */
    private $expr;

    /**
     * @param string $expr
     */
    public function __construct($expr)
    {
        if (!is_string($expr)) {
            $type = gettype($expr);
            throw new \InvalidArgumentException("The expression must be string, got '$type'");
        }

        $this->expr = $expr;
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        return [$this->expr, []];
    }
}
