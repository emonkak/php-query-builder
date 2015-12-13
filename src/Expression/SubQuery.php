<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryBuilderInterface;
use Emonkak\QueryBuilder\QueryFragmentInterface;

/**
 * @internal
 */
class SubQuery implements QueryFragmentInterface
{
    use ExpressionHelper;

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
