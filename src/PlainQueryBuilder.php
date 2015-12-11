<?php

namespace Emonkak\QueryBuilder\Expression;

class PlainQueryBuilder implements QueryBuilderInterface
{
    use ToStringable;

    /**
     * @var string
     */
    private $sql;

    /**
     * @var mixed[]
     */
    private $binds;

    /**
     * @param QueryBuilderInterface $query
     * @return self
     */
    public function fromQuery(QueryBuilderInterface $query)
    {
        list ($sql, $binds) = $query->build();
        return new static($sql, $binds);
    }

    /**
     * @param string  $sql
     * @param mixed[] $binds
     */
    public function __construct($sql, $binds)
    {
        $this->sql = $sql;
        $this->binds = $binds;
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        return [$sql, $binds];
    }
}
