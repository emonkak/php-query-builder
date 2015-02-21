<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryInterface;

class SubQuery implements ExpressionInterface
{
    use ExpressionHelper;

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param QueryInterface $query
     */
    public function __construct(QueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function compile()
    {
        list ($sql, $binds) = $this->query->compile();

        return ["($sql)", $binds];
    }
}
