<?php

namespace Emonkak\QueryBuilder\Expression;

class Raw implements ExpressionInterface
{
    use ExpressionHelper;

    /**
     * @var string
     */
    private $sql;

    /**
     * @var mixed[]
     */
    private $binds;

    /**
     * @param string  $sql
     * @param mixed[] $binds
     */
    public function __construct($sql, array $binds = [])
    {
        $this->sql = $sql;
        $this->binds = $binds;
    }

    /**
     * {@inheritDoc}
     */
    public function compile()
    {
        return [$this->sql, $this->binds];
    }
}
