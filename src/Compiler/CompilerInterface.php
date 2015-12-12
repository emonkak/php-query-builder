<?php

namespace Emonkak\QueryBuilder\Compiler;

use Emonkak\QueryBuilder\QueryBuilderInterface;
use Emonkak\QueryBuilder\QueryFragmentInterface;

interface CompilerInterface
{
    /**
     * @param string                   $prefix
     * @param QueryFragmentInterface[] $select
     * @param QueryFragmentInterface[] $from
     * @param QueryFragmentInterface[] $join
     * @param QueryFragmentInterface   $where
     * @param QueryFragmentInterface[] $groupBy
     * @param QueryFragmentInterface   $having
     * @param QueryFragmentInterface[] $orderBy
     * @param integer                  $limit
     * @param integer                  $offset
     * @param QueryBuilderInterface[]  $union
     * @return array (sql: string, binds: mixed[])
     */
    public function compileSelect($prefix, array $select, array $from = null, array $join, QueryFragmentInterface $where = null, array $groupBy, QueryFragmentInterface $having = null, array $orderBy, $limit, $offset, $suffix, array $union);
}
