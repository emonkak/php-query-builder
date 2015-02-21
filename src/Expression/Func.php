<?php

namespace Emonkak\QueryBuilder\Expression;

class Func
{
    use ExpressionHelper;

    /**
     * @var string
     */
    private $func;

    /**
     * @var ExpressionInterface[]
     */
    private $args;

    /**
     * @param string                $func
     * @param ExpressionInterface[] $args
     */
    public function __construct($func, array $args)
    {
        $this->func = $func;
        $this->args = $args;
    }

    /**
     * {@inheritDoc}
     */
    public function compile()
    {
        $sql = [];
        $binds = [];

        foreach ($this->args as $arg) {
            list ($argSql, $argBinds) = $arg->compile();
            $sql[] = $argSql;
            $binds = array_merge($binds, $argBinds);
        }

        return [sprintf('%s(%s)', $this->func, implode(', ', $sql)), $binds];
    }
}
