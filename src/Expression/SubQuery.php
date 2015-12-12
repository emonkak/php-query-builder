<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryBuilderInterface;
use Emonkak\QueryBuilder\ToStringable;

/**
 * @internal
 */
class SubQuery implements QueryBuilderInterface
{
    use ExpressionHelper;
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
