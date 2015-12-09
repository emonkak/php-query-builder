<?php

namespace Emonkak\QueryBuilder\Expression;

class Raw implements ExpressionInterface
{
    use ExpressionHelpers;

    /**
     * @var string
     */
    private $expr;

    /**
     * @var mixed[]
     */
    private $binds;

    /**
     * @param string $expr
     * @param mixed[] $binds
     */
    public function __construct($expr, array $binds)
    {
        if ($expr === null) {
            throw new \InvalidArgumentException('$expr can not be null');
        }

        $this->expr = $expr;
        $this->binds = $binds;
    }

    /**
     * {@inheritDoc}
     */
    public function compile()
    {
        return [$this->expr, $this->binds];
    }
}
