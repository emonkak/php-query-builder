<?php

namespace Emonkak\QueryBuilder\Expression;

interface ExpressionInterface
{
    /**
     * Compiles this expression.
     *
     * @return array (sql, binds)
     */
    public function compile();
}
