<?php

namespace Emonkak\QueryBuilder\Expression;

class Alias implements ExpressionInterface
{
    use ExpressionHelpers;

    /**
     * @var ExpressionInterface $value
     */
    private $value;

    /**
     * @var string
     */
    private $alias;

    /**
     * @param ExpressionInterface $value
     * @param string              $alias
     */
    public function __construct(ExpressionInterface $value, $alias)
    {
        $this->value = $value;
        $this->alias = $alias;
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        list ($sql, $binds) = $this->value->build();
        return [$sql . ' AS ' . $this->alias, $binds];
    }
}
