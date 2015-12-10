<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Expression\Alias;
use Emonkak\QueryBuilder\Expression\ExpressionResolver;

trait SelectQueryBuilderTrait
{
    private $prefix = 'SELECT';
    private $projections = [];
    private $from = [];
    private $join = [];
    private $where = null;
    private $groupBy = [];
    private $having = null;
    private $orderBy = [];
    private $offset = null;
    private $limit = null;
    private $suffix = null;
    private $union = [];

    /**
     * @param string $prefix
     * @return self
     */
    public function prefix($prefix)
    {
        $chained = clone $this;
        $chained->prefix = $prefix;
        return $chained;
    }

    /**
     * @param mixed  $expr
     * @param string $alias
     * @return self
     */
    public function select($expr, $alias = null)
    {
        $expr = ExpressionResolver::resolveCreteria($expr);
        if ($alias !== null) {
            $expr = new Alias($expr, $alias);
        }
        $chained = clone $this;
        $chained->projections[] = $expr;
        return $chained;
    }

    /**
     * @param mixed  $expr
     * @param string $alias
     * @return self
     */
    public function from($expr, $alias = null)
    {
        $expr = ExpressionResolver::resolveCreteria($expr);
        if ($alias !== null) {
            $expr = new Alias($expr, $alias);
        }
        $chained = clone $this;
        $chained->from[] = $expr;
        return $chained;
    }

    /**
     * @param mixed[] ...$args
     * @return self
     */
    public function where()
    {
        $args = func_get_args();
        $expr = ExpressionResolver::resolveCreteria($args);
        $chained = clone $this;
        $chained->where = $chained->where ? $chained->where->_and($expr) : $expr;
        return $chained;
    }

    /**
     * @param mixed[] ...$args
     * @return self
     */
    public function orWhere()
    {
        $args = func_get_args();
        $expr = ExpressionResolver::resolveCreteria($args);
        $chained = clone $this;
        $chained->where = $chained->where ? $chained->where->_or($expr) : $expr;
        return $chained;
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
        $table = ExpressionResolver::resolveCreteria($table);
        if ($alias !== null) {
            $table = new Alias($table, $alias);
        }
        $chained = clone $this;
        $chained->join[] = [
            'table' => $table,
            'condition' => $condition !== null ? ExpressionResolver::resolveCreteria($condition) : null,
            'type' => $type,
        ];
        return $chained;
    }

    /**
     * @param mixed  $table
     * @param mixed  $condition
     * @param string $alias
     * @return self
     */
    public function leftJoin($table, $condition = null, $alias = null)
    {
        return $this->join($table, $condition, $alias, 'LEFT JOIN');
    }

    /**
     * @param mixed  $expr
     * @param string $direction
     * @return self
     */
    public function groupBy($expr, $direction = null)
    {
        $chained = clone $this;
        $chained->groupBy[] = [
            'expr' => ExpressionResolver::resolveCreteria($expr),
            'direction' => $direction,
        ];
        return $chained;
    }

    /**
     * @param mixed[] ...$args
     * @return self
     */
    public function having()
    {
        $args = func_get_args();
        $expr = ExpressionResolver::resolveCreteria($args);
        $chained = clone $this;
        $chained->having = $chained->having ? $chained->having->_and($expr) : $expr;
        return $chained;
    }

    /**
     * @param mixed[] ...$args
     * @return self
     */
    public function orHaving()
    {
        $args = func_get_args();
        $expr = ExpressionResolver::resolveCreteria($args);
        $chained = clone $this;
        $chained->having = $chained->having ? $chained->having->_or($expr) : $expr;
        return $chained;
    }

    /**
     * @param mixed  $expr
     * @param stirng $direction
     * @return self
     */
    public function orderBy($expr, $direction = null)
    {
        $chained = clone $this;
        $chained->orderBy[] = [
            'expr' => ExpressionResolver::resolveCreteria($expr),
            'direction' => $direction,
        ];
        return $chained;
    }

    /**
     * @param integer $integer
     * @return self
     */
    public function limit($limit)
    {
        $chained = clone $this;
        $chained->limit = $limit;
        return $chained;
    }

    /**
     * @param integer $integer
     * @return self
     */
    public function offset($offset)
    {
        $chained = clone $this;
        $chained->offset = $offset;
        return $chained;
    }

    /**
     * @param string $suffix
     * @return self
     */
    public function suffix($suffix)
    {
        $chained = clone $this;
        $chained->suffix = $suffix;
        return $chained;
    }

    /**
     * @return self
     */
    public function forUpdate()
    {
        return $this->suffix('FOR UPDATE');
    }
}
