<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryFragmentInterface;

class Raw implements QueryFragmentInterface
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
        if (!is_string($expr)) {
            $type = gettype($expr);
            throw new \InvalidArgumentException("The expression must be String, got '$type'");
        }

        $this->expr = $expr;
        $this->binds = $binds;
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        return [$this->expr, $this->binds];
    }
}
