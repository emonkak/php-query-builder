<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryFragmentInterface;

/**
 * @internal
 */
class Func implements QueryFragmentInterface
{
    use ExpressionHelper;

    /**
     * @var string
     */
    private $func;

    /**
     * @var QueryFragmentInterface
     */
    private $expr;

    /**
     * @param string                 $func
     * @param QueryFragmentInterface $expr
     */
    public function __construct($func, QueryFragmentInterface $expr)
    {
        $this->func = $func;
        $this->expr = $expr;
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        list ($sql, $binds) = $this->expr->build();
        return ["$this->func($sql)", $binds];
    }
}
