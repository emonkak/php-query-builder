<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Expression\ExpressionResolver;

trait SelectBuilderTrait
{
    /**
     * @var array
     */
    protected $fragments = [
        'prefix' => 'SELECT',
        'projections' => [],
        'from' => [],
        'join' => [],
        'where' => null,
        'groupBy' => [],
        'having' => null,
        'orderBy' => [],
        'offset' => null,
        'limit' => null,
        'suffix' => null,
    ];

    /**
     * @param string $prefix
     * @return self
     */
    public function prefix($prefix)
    {
        $builder = clone $this;
        $builder->fragments['prefix'] = $prefix;
        return $builder;
    }

    /**
     * @param mixed $expr
     * @return self
     */
    public function project($expr)
    {
        $builder = clone $this;
        $builder->fragments['projections'][] = ExpressionResolver::get($expr);
        return $builder;
    }

    /**
     * @param mixed $table
     * @return self
     */
    public function from($table)
    {
        $builder = clone $this;
        $builder->fragments['from'][] = ExpressionResolver::get($table);
        return $builder;
    }

    /**
     * @param mixed $condition
     * @return self
     */
    public function where($condition)
    {
        $builder = clone $this;
        $builder->fragments['where'] = ExpressionResolver::get($condition);
        return $builder;
    }

    /**
     * @param mixed $condition
     * @return self
     */
    public function whereAnd($condition)
    {
        $builder = clone $this;
        $builder->fragments['where'] = $builder->fragments['where']->_and(ExpressionResolver::get($condition));
        return $builder;
    }

    /**
     * @param mixed $condition
     * @return self
     */
    public function whereOr($condition)
    {
        $builder = clone $this;
        $builder->fragments['where'] = $builder->fragments['where']->_or(ExpressionResolver::get($condition));
        return $builder;
    }

    /**
     * @param mixed      $table
     * @param mixed|nlll $condition
     * @param string     $type
     * @return self
     */
    public function join($table, $condition = null, $type = 'JOIN')
    {
        $builder = clone $this;
        $builder->fragments['join'][] = [
            'table' => ExpressionResolver::get($table),
            'condition' => $condition !== null ? ExpressionResolver::get($condition) : null,
            'type' => $type
        ];
        return $builder;
    }

    /**
     * @param mixed  $expr
     * @param string $direction
     * @return self
     */
    public function groupBy($expr, $direction = null)
    {
        $builder = clone $this;
        $builder->fragments['groupBy'][] = [
            'expr' => ExpressionResolver::get($expr),
            'direction' => $direction,
        ];
        return $builder;
    }

    /**
     * @param mixed $condition
     * @return self
     */
    public function having($condition)
    {
        $builder = clone $this;
        $builder->fragments['having'] = ExpressionResolver::get($condition);
        return $builder;
    }

    /**
     * @param mixed $condition
     * @return self
     */
    public function havingAnd($condition)
    {
        $builder = clone $this;
        $builder->fragments['having'] = $builder->fragments['having']->_and(ExpressionResolver::get($condition));
        return $builder;
    }

    /**
     * @param mixed $condition
     * @return self
     */
    public function havingOr($condition)
    {
        $builder = clone $this;
        $builder->fragments['having'] = $builder->fragments['having']->_or(ExpressionResolver::get($condition));
        return $builder;
    }

    /**
     * @param mixed  $expr
     * @param stirng $direction
     * @return self
     */
    public function orderBy($expr, $direction = null)
    {
        $builder = clone $this;
        $builder->fragments['orderBy'][] = [
            'expr' => ExpressionResolver::get($expr),
            'direction' => $direction
        ];
        return $builder;
    }

    /**
     * @param integer $integer
     * @return self
     */
    public function limit($limit)
    {
        $builder = clone $this;
        $builder->fragments['limit'] = $limit;
        return $builder;
    }

    /**
     * @param integer $integer
     * @return self
     */
    public function offset($offset)
    {
        $builder = clone $this;
        $builder->fragments['offset'] = $offset;
        return $builder;
    }

    /**
     * @param string $suffix
     * @return self
     */
    public function suffix($suffix)
    {
        $builder = clone $this;
        $builder->fragments['suffix'] = $suffix;
        return $builder;
    }
}
