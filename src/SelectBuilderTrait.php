<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Expression\ExpressionResolver;

trait SelectBuilderTrait
{
    /**
     * @param string $prefix
     * @return self
     */
    public function prefix($prefix)
    {
        return $this->set('prefix', $prefix);
    }

    /**
     * @param mixed  $expr
     * @param string $alias
     * @return self
     */
    public function select($expr, $alias = null)
    {
        return $this->add('projections', [
            'expr' => ExpressionResolver::resolveCreteria($expr),
            'alias' => $alias
        ]);
    }

    /**
     * @param mixed  $expr
     * @param string $alias
     * @return self
     */
    public function from($expr, $alias = null)
    {
        return $this->add('from', [
            'expr' => ExpressionResolver::resolveCreteria($expr),
            'alias' => $alias,
        ]);
    }

    /**
     * @param mixed[] ...$args
     * @return self
     */
    public function where()
    {
        $args = func_get_args();
        return $this->add('where', ExpressionResolver::resolveCreteria($args));
    }

    /**
     * @param mixed  $table
     * @param mixed  $condition
     * @param string $alias
     * @param string $type
     * @return self
     */
    public function join($table, $condition = null, $alias = null, $type = 'JOIN')
    {
        return $this->add('join', [
            'table' => ExpressionResolver::resolveCreteria($table),
            'alias' => $alias,
            'type' => $type,
            'condition' => $condition !== null ? ExpressionResolver::resolveCreteria($condition) : null,
        ]);
    }

    /**
     * @param mixed  $table
     * @param mixed  $condition
     * @param string $alias
     * @return self
     */
    public function leftJoin($table, $condition = null, $alias = null)
    {
        return $this->join($table, $condition, $alias);
    }

    /**
     * @param mixed  $expr
     * @param string $direction
     * @return self
     */
    public function groupBy($expr, $direction = null)
    {
        return $this->add('groupBy', [
            'expr' => ExpressionResolver::resolveCreteria($expr),
            'direction' => $direction,
        ]);
    }

    /**
     * @param mixed[] ...$args
     * @return self
     */
    public function having()
    {
        $args = func_get_args();
        return $this->add('having', ExpressionResolver::resolveCreteria($args));
    }

    /**
     * @param mixed  $expr
     * @param stirng $direction
     * @return self
     */
    public function orderBy($expr, $direction = null)
    {
        return $this->add('orderBy', [
            'expr' => ExpressionResolver::resolveCreteria($expr),
            'direction' => $direction
        ]);
    }

    /**
     * @param integer $integer
     * @return self
     */
    public function limit($limit)
    {
        return $this->set('limit', $limit);
    }

    /**
     * @param integer $integer
     * @return self
     */
    public function offset($offset)
    {
        return $this->set('offset', $offset);
    }

    /**
     * @param string $suffix
     * @return self
     */
    public function suffix($suffix)
    {
        return $this->set('suffix', $suffix);
    }

    /**
     * @return self
     */
    public function forUpdate()
    {
        return $this->suffix('FOR UPDATE');
    }

    /**
     * @param string $expr
     * @param string $type
     * @return self
     */
    public function union($expr, $type = 'UNION')
    {
        return $this->add('union', [
            'expr' => ExpressionResolver::resolveValue($expr),
            'type' => $type,
        ]);
    }

    /**
     * @param string $table
     * @return self
     */
    public function unionAll($table)
    {
        return $this->union($table, 'UNION ALL');
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return self
     */
    abstract protected function add($key, $value);

    /**
     * @param string $key
     * @param mixed $value
     * @return self
     */
    abstract protected function set($key, $value);
}
