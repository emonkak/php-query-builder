<?php

namespace Emonkak\QueryBuilder\Compiler;

use Emonkak\QueryBuilder\Expression\ExpressionInterface;

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
    public function compileSelect($prefix, array $projections, array $from = null, array $join, ExpressionInterface $where = null, array $groupBy, ExpressionInterface $having = null, array $orderBy, $limit, $offset, $suffix, array $union)
    {
        $binds = [];
        $sql = $prefix
             . $this->compileProjections($projections, $binds)
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
            list ($unionSql, $unionBinds) = $definition['query']->build();
            $sql .= ' ' . $definition['type'] . ' (' . $unionSql . ')';
            $binds = array_merge($binds, $unionBinds);
        }

        return [$sql, $binds];
    }

    /**
     * @param ExpressionInterface[] $projections
     * @param mixed[]                &$binds
     * @return string
     */
    protected function compileProjections(array $projections, array &$binds)
    {
        if (empty($projections)) {
            return ' *';
        }

        $sqls = [];
        foreach ($projections as $projection) {
            list ($projectionSql, $projectionBinds) = $projection->build();
            $sqls[] = $projectionSql;
            $binds = array_merge($binds, $projectionBinds);
        }

        return ' ' . implode(', ', $sqls);
    }

    /**
     * @param ExpressionInterface[] $from
     * @param mixed[]               &$binds
     * @return string
     */
    protected function compileFrom(array $from, array &$binds)
    {
        if (empty($from)) {
            return '';
        }

        $sqls = [];
        foreach ($from as $table) {
            list ($tableSql, $tableBinds) = $table->build();
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
            list ($tableSql, $tableBinds) = $definition['table']->build();
            $sqls[] = $definition['type'] . ' ' . $tableSql;
            $binds = array_merge($binds, $tableBinds);

            if (isset($definition['condition'])) {
                list ($conditionSql, $conditionBinds) = $definition['condition']->build();
                $sqls[] = 'ON ' . $conditionSql;
                $binds = array_merge($binds, $conditionBinds);
            }
        }

        return ' ' . implode(' ', $sqls);
    }

    /**
     * @param ExpressionInterface $where
     * @param mixed[]             &$binds
     * @return string
     */
    protected function compileWhere(ExpressionInterface $where = null, array &$binds)
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
            list ($groupBySql, $groupByBinds) = $definition['expr']->build();
            if (isset($definition['direction'])) {
                $sqls[] = $groupBySql . ' ' . $definition['direction'];
            } else {
                $sqls[] = $groupBySql;
            }
            $binds = array_merge($binds, $groupByBinds);
        }

        return ' GROUP BY ' . implode(', ', $sqls);
    }

    /**
     * @param ExpressionInterface $having
     * @param mixed[]             &$binds
     * @return string
     */
    protected function compileHaving(ExpressionInterface $having = null, array &$binds)
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
            list ($orderBySql, $orderByBinds) = $definition['expr']->build();
            if (isset($definition['direction'])) {
                $sqls[] = $orderBySql . ' ' . $definition['direction'];
            } else {
                $sqls[] = $orderBySql;
            }
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
