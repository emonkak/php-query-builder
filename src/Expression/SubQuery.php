<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryBuilderInterface;
use Emonkak\QueryBuilder\ToStringable;

class SubQuery implements QueryBuilderInterface
{
    use ExpressionHelpers;
    use ToStringable;

    /**
     * @var QueryBuilderInterface
     */
    private $query;

    /**
     * @param QueryBuilderInterface $query
     */
    public function __construct(QueryBuilderInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        list ($sql, $binds) = $this->query->build();

        return ['(' . $sql . ')', $binds];
    }
}
