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
             . $this->processProjections($select, $binds)
             . $this->processFrom($from, $binds)
             . $this->processJoin($join, $binds)
             . $this->processWhere($where, $binds)
             . $this->processGroupBy($groupBy, $binds)
             . $this->processHaving($having, $binds)
             . $this->processOrderBy($orderBy, $binds)
             . $this->processLimit($limit, $binds)
             . $this->processOffset($offset, $binds)
             . ($suffix !== null ? ' ' . $suffix : '');

        if (!empty($union)) {
            $sql = '(' . $sql . ')';
        }

        $sql .= $this->processUnion($union, $binds);

        return [$sql, $binds];
    }

    /**
     * @param QueryFragmentInterface[] $select
     * @param mixed[]                  &$binds
     * @return string
     */
    protected function processProjections(array $select, array &$binds)
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
    protected function processFrom(array $from, array &$binds)
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
    protected function processJoin(array $join, array &$binds)
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
    protected function processWhere(QueryFragmentInterface $where = null, array &$binds)
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
    protected function processGroupBy(array $groupBy, array &$binds)
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
    protected function processHaving(QueryFragmentInterface $having = null, array &$binds)
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
    protected function processOrderBy(array $orderBy, array &$binds)
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
    protected function processLimit($limit, array &$binds)
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
    protected function processOffset($offset, array &$binds)
    {
        if ($offset === null) {
            return '';
        }

        $binds[] = $offset;
        return ' OFFSET ?';
    }

    /**
     * @param array   $union
     * @param mixed[] &$binds
     * @return string
     */
    protected function processUnion(array $union, array &$binds)
    {
        if (empty($union)) {
            return '';
        }

        $sqls = [];
        foreach ($union as $definition) {
            list ($unionSql, $unionBinds) = $definition->build();
            $sqls[] = $unionSql;
            $binds = array_merge($binds, $unionBinds);
        }

        return ' ' . implode(' ', $sqls);
    }
}
