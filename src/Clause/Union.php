<?php

namespace Emonkak\QueryBuilder\Clause;

use Emonkak\QueryBuilder\QueryBuilderInterface;
use Emonkak\QueryBuilder\QueryFragmentInterface;

/**
 * @internal
 */
class Union implements QueryFragmentInterface
{
    /**
     * @var QueryBuilderInterface $query
     */
    private $query;

    /**
     * @var string
     */
    private $type;

    /**
     * @param QueryBuilderInterface $query
     * @param string                $type
     */
    public function __construct(QueryBuilderInterface $query, $type)
    {
        $this->query = $query;
        $this->type = $type;
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        list ($sql, $binds) = $this->query->build();
        return [$this->type . ' (' . $sql . ')', $binds];
    }
}
