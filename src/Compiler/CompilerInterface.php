<?php

namespace Emonkak\QueryBuilder\Compiler;

use Emonkak\QueryBuilder\Expression\ExpressionInterface;

interface CompilerInterface
{
    /**
     * @param string                   $prefix
     * @param ExpressionInterface[]    $projections
     * @param ExpressionInterface[]    $from
     * @param array[]                  $join
     * @param ExpressionInterface|null $where
     * @param array[]                  $groupBy
     * @param ExpressionInterface|null $having
     * @param array[]                  $orderBy
     * @param integer                  $limit
     * @param integer                  $offset
     * @param array[]                  $union
     * @return array (sql: string, binds: mixed[])
     */
    public function compileSelect($prefix, array $projections, array $from = null, array $join, ExpressionInterface $where = null, array $groupBy, ExpressionInterface $having = null, array $orderBy, $limit, $offset, $suffix, array $union);
}
