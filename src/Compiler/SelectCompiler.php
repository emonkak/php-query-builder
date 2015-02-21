<?php

namespace Emonkak\QueryBuilder\Compiler;

class SelectCompiler implements CompilerInterface
{
    /**
     * {@inheritDoc}
     */
    public function compile(array $fragments)
    {
        $binds = [];

        $sql = $fragments['prefix'];
        $sql .= $this->compileProjections($fragments, $binds);
        $sql .= $this->compileFrom($fragments, $binds);
        $sql .= $this->compileJoin($fragments, $binds);
        $sql .= $this->compileWhere($fragments, $binds);
        $sql .= $this->compileGroupBy($fragments, $binds);
        $sql .= $this->compileHaving($fragments, $binds);
        $sql .= $this->compileOrderBy($fragments, $binds);
        $sql .= $this->compileLimit($fragments, $binds);
        $sql .= $this->compileOffset($fragments, $binds);
        $sql .= isset($fragments['suffix']) ? ' ' . $fragments['prefix'] : '';

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
            list ($projectionSql, $projectionBinds) = $projection->compile();
            $sql[] = $projectionSql;
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
            list ($tableSql, $tableBinds) = $definition->compile();
            $sql[] = $tableSql;
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
            $sql[] = $definition['type'] . ' ' . $tableSql;
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
        if (!isset($fragments['where'])) {
            return '';
        }

        list ($whereSql, $whereBinds) = $fragments['where']->compile();
        $binds = array_merge($binds, $whereBinds);

        return ' WHERE ' . $whereSql;
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
        if (!isset($fragments['having'])) {
            return '';
        }

        list ($havingSql, $havingBinds) = $fragments['having']->compile();
        $binds = array_merge($binds, $havingBinds);

        return ' HAVING ' . $havingSql;
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
}
