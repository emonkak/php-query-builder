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
     * @var QueryFragmentInterface[]
     */
    private $args;

    /**
     * @param string                   $func
     * @param QueryFragmentInterface[] $args
     */
    public function __construct($func, array $args)
    {
        $this->func = $func;
        $this->args = $args;
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        list ($funcSql, $funcBinds) = $this->func->build();

        $sqls = [];
        $binds = $funcBinds;

        foreach ($this->args as $arg) {
            list ($argSql, $argBinds) = $arg->build();
            $sqls[] = $argSql;
            $binds = array_merge($binds, $argBinds);
        }

        return [$funcSql . '(' . implode(', ', $sqls) . ')', $binds];
    }
}
