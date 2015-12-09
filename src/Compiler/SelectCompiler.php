<?php

namespace Emonkak\QueryBuilder\Compiler;

class SelectCompiler implements CompilerInterface
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
    public function compile(array $fragments)
    {
        $binds = [];
        $sql = $fragments['prefix']
             . $this->compileProjections($fragments, $binds)
             . $this->compileFrom($fragments, $binds)
             . $this->compileJoin($fragments, $binds)
             . $this->compileWhere($fragments, $binds)
             . $this->compileGroupBy($fragments, $binds)
             . $this->compileHaving($fragments, $binds)
             . $this->compileOrderBy($fragments, $binds)
             . $this->compileLimit($fragments, $binds)
             . $this->compileOffset($fragments, $binds)
             . (isset($fragments['suffix']) ? ' ' . $fragments['suffix'] : '')
             . $this->compileUnion($fragments, $binds);

        return [$sql, $binds];
    }

    /**
     * @param array $fragments
     * @param array &$binds
     * @return string
     */
    protected function compileProjections(array $fragments, array &$binds)
    {
        if (empty($fragments['projections'])) {
            return ' *';
        }

        $sql = [];
        foreach ($fragments['projections'] as $projection) {
            list ($projectionSql, $projectionBinds) = $projection['expr']->compile();
            if (isset($projection['alias'])) {
                $sql[] = $projectionSql . ' AS ' . $projection['alias'];
            } else {
                $sql[] = $projectionSql;
            }
            $binds = array_merge($binds, $projectionBinds);
        }

        return ' ' . implode(', ', $sql);
    }

    /**
     * @param array $fragments
     * @param array &$binds
     * @return string
     */
    protected function compileFrom(array $fragments, array &$binds)
    {
        if (empty($fragments['from'])) {
            return '';
        }

        $sql = [];
        foreach ($fragments['from'] as $definition) {
            list ($tableSql, $tableBinds) = $definition['expr']->compile();
            if (isset($definition['alias'])) {
                $sql[] = $tableSql . ' AS ' . $definition['alias'];
            } else {
                $sql[] = $tableSql;
            }
            $binds = array_merge($binds, $tableBinds);
        }

        return ' FROM ' . implode(', ', $sql);
    }

    /**
     * @param array $fragments
     * @param array &$binds
     * @return string
     */
    protected function compileJoin(array $fragments, array &$binds)
    {
        if (empty($fragments['join'])) {
            return '';
        }

        $sql = [];
        foreach ($fragments['join'] as $definition) {
            list ($tableSql, $tableBinds) = $definition['table']->compile();
            if (isset($definition['alias'])) {
                $sql[] = $definition['type'] . ' ' . $tableSql . ' AS ' . $definition['alias'];
            } else {
                $sql[] = $definition['type'] . ' ' . $tableSql;
            }
            $binds = array_merge($binds, $tableBinds);

            if (isset($definition['condition'])) {
                list ($conditionSql, $conditionBinds) = $definition['condition']->compile();
                $sql[] = 'ON ' . $conditionSql;
                $binds = array_merge($binds, $conditionBinds);
            }
        }

        return ' ' . implode(' ', $sql);
    }

    /**
     * @param array $fragments
     * @param array &$binds
     * @return string
     */
    protected function compileWhere(array $fragments, array &$binds)
    {
        if (empty($fragments['where'])) {
            return '';
        }

        $sql = [];
        foreach ($fragments['where'] as $definition) {
            list ($whereSql, $whereBinds) = $definition->compile();
            $sql[] = $whereSql;
            $binds = array_merge($binds, $whereBinds);
        }

        return ' WHERE ' . implode(' AND ', $sql);
    }

    /**
     * @param array $fragments
     * @param array &$binds
     * @return string
     */
    protected function compileGroupBy(array $fragments, array &$binds)
    {
        if (empty($fragments['groupBy'])) {
            return '';
        }

        $sql = [];
        foreach ($fragments['groupBy'] as $definition) {
            list ($groupBySql, $groupByBinds) = $definition['expr']->compile();
            if (isset($definition['direction'])) {
                $sql[] = $groupBySql . ' ' . $definition['direction'];
            } else {
                $sql[] = $groupBySql;
            }
            $binds = array_merge($binds, $groupByBinds);
        }

        return ' GROUP BY ' . implode(', ', $sql);
    }

    /**
     * @param array $fragments
     * @param array &$binds
     * @return string
     */
    protected function compileHaving(array $fragments, array &$binds)
    {
        if (empty($fragments['having'])) {
            return '';
        }

        $sql = [];
        foreach ($fragments['having'] as $definition) {
            list ($havingSql, $havingBinds) = $definition->compile();
            $sql[] = $havingSql;
            $binds = array_merge($binds, $havingBinds);
        }

        return ' HAVING ' . implode(' AND ', $sql);
    }

    /**
     * @param array $fragments
     * @param array &$binds
     * @return string
     */
    protected function compileOrderBy(array $fragments, array &$binds)
    {
        if (empty($fragments['orderBy'])) {
            return '';
        }

        $sql = [];
        foreach ($fragments['orderBy'] as $definition) {
            list ($orderBySql, $orderByBinds) = $definition['expr']->compile();
            if (isset($definition['direction'])) {
                $sql[] = $orderBySql . ' ' . $definition['direction'];
            } else {
                $sql[] = $orderBySql;
            }
            $binds = array_merge($binds, $orderByBinds);
        }

        return ' ORDER BY ' . implode(', ', $sql);
    }

    /**
     * @param array $fragments
     * @param array &$binds
     * @return string
     */
    protected function compileLimit(array $fragments, array &$binds)
    {
        if (!isset($fragments['limit'])) {
            return '';
        }

        $binds[] = $fragments['limit'];
        return ' LIMIT ?';
    }

    /**
     * @param array $fragments
     * @param array &$binds
     * @return string
     */
    protected function compileOffset(array $fragments, array &$binds)
    {
        if (!isset($fragments['offset'])) {
            return '';
        }

        $binds[] = $fragments['offset'];
        return ' OFFSET ?';
    }

    /**
     * @param array $fragments
     * @param array &$binds
     * @return string
     */
    protected function compileUnion(array $fragments, array &$binds)
    {
        if (empty($fragments['union'])) {
            return '';
        }

        $sql = [];
        foreach ($fragments['union'] as $definition) {
            list ($unionSql, $unionBinds) = $definition['expr']->compile();
            $sql[] = $definition['type'] . ' ' . $unionSql;
            $binds = array_merge($binds, $unionBinds);
        }

        return ' ' . implode(' ', $sql);
    }
}
