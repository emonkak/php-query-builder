<?php

namespace Emonkak\QueryBuilder\Compiler;

use Emonkak\QueryBuilder\QueryFragmentInterface;

class DefaultCompiler implements CompilerInterface
{
    /**
     * @return self
     */
    public static function getInstance()
    {
        static $instance;

        if (isset($instance)) {
            return $instance;
        }

        return $instance = new self();
    }

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function compileSelect($prefix, array $select, array $from = null, array $join, QueryFragmentInterface $where = null, array $groupBy, QueryFragmentInterface $having = null, array $orderBy, $limit, $offset, $suffix, array $union)
    {
        $binds = [];
        $sql = $prefix
             . $this->compileProjections($select, $binds)
             . $this->compileFrom($from, $binds)
             . $this->compileJoin($join, $binds)
             . $this->compileWhere($where, $binds)
             . $this->compileGroupBy($groupBy, $binds)
             . $this->compileHaving($having, $binds)
             . $this->compileOrderBy($orderBy, $binds)
             . $this->compileLimit($limit, $binds)
             . $this->compileOffset($offset, $binds)
             . ($suffix !== null ? ' ' . $suffix : '');

        if (!empty($union)) {
            $sql = '(' . $sql . ')';
        }

        foreach ($union as $definition) {
            list ($unionSql, $unionBinds) = $definition->build();
            $sql .= ' ' . $unionSql;
            $binds = array_merge($binds, $unionBinds);
        }

        return [$sql, $binds];
    }

    /**
     * @param QueryFragmentInterface[] $select
     * @param mixed[]                  &$binds
     * @return string
     */
    protected function compileProjections(array $select, array &$binds)
    {
        if (empty($select)) {
            return ' *';
        }

        $sqls = [];
        foreach ($select as $definition) {
            list ($selectSql, $selectBinds) = $definition->build();
            $sqls[] = $selectSql;
            $binds = array_merge($binds, $selectBinds);
        }

        return ' ' . implode(', ', $sqls);
    }

    /**
     * @param QueryFragmentInterface[] $from
     * @param mixed[]                  &$binds
     * @return string
     */
    protected function compileFrom(array $from, array &$binds)
    {
        if (empty($from)) {
            return '';
        }

        $sqls = [];
        foreach ($from as $definition) {
            list ($tableSql, $tableBinds) = $definition->build();
            $sqls[] = $tableSql;
            $binds = array_merge($binds, $tableBinds);
        }

        return ' FROM ' . implode(', ', $sqls);
    }

    /**
     * @param array   $join
     * @param mixed[] &$binds
     * @return string
     */
    protected function compileJoin(array $join, array &$binds)
    {
        if (empty($join)) {
            return '';
        }

        $sqls = [];
        foreach ($join as $definition) {
            list ($joinSql, $joinBinds) = $definition->build();
            $sqls[] = $joinSql;
            $binds = array_merge($binds, $joinBinds);
        }

        return ' ' . implode(' ', $sqls);
    }

    /**
     * @param QueryFragmentInterface $where
     * @param mixed[]                &$binds
     * @return string
     */
    protected function compileWhere(QueryFragmentInterface $where = null, array &$binds)
    {
        if (!isset($where)) {
            return '';
        }

        list ($whereSql, $whereBinds) = $where->build();
        $binds = array_merge($binds, $whereBinds);

        return ' WHERE ' . $whereSql;
    }

    /**
     * @param array   $groupBy
     * @param mixed[] &$binds
     * @return string
     */
    protected function compileGroupBy(array $groupBy, array &$binds)
    {
        if (empty($groupBy)) {
            return '';
        }

        $sqls = [];
        foreach ($groupBy as $definition) {
            list ($groupBySql, $groupByBinds) = $definition->build();
            $sqls[] = $groupBySql;
            $binds = array_merge($binds, $groupByBinds);
        }

        return ' GROUP BY ' . implode(', ', $sqls);
    }

    /**
     * @param QueryFragmentInterface $having
     * @param mixed[]                &$binds
     * @return string
     */
    protected function compileHaving(QueryFragmentInterface $having = null, array &$binds)
    {
        if (!isset($having)) {
            return '';
        }

        list ($havingSql, $havingBinds) = $having->build();
        $binds = array_merge($binds, $havingBinds);

        return ' HAVING ' . $havingSql;
    }

    /**
     * @param array   $orderBy
     * @param mixed[] &$binds
     * @return string
     */
    protected function compileOrderBy(array $orderBy, array &$binds)
    {
        if (empty($orderBy)) {
            return '';
        }

        $sqls = [];
        foreach ($orderBy as $definition) {
            list ($orderBySql, $orderByBinds) = $definition->build();
            $sqls[] = $orderBySql;
            $binds = array_merge($binds, $orderByBinds);
        }

        return ' ORDER BY ' . implode(', ', $sqls);
    }

    /**
     * @param integer $limit
     * @param mixed[] &$binds
     * @return string
     */
    protected function compileLimit($limit, array &$binds)
    {
        if ($limit === null) {
            return '';
        }

        $binds[] = $limit;
        return ' LIMIT ?';
    }

    /**
     * @param integer $offset
     * @param mixed[] &$binds
     * @return string
     */
    protected function compileOffset($offset, array &$binds)
    {
        if ($offset === null) {
            return '';
        }

        $binds[] = $offset;
        return ' OFFSET ?';
    }
}
