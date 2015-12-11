<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Compiler\DefaultCompiler;
use Emonkak\QueryBuilder\Expression\Alias;
use Emonkak\QueryBuilder\Expression\ExpressionResolver;
use Emonkak\QueryBuilder\Expression\ExpressionInterface;

trait SelectQueryBuilderTrait
{
    /**
     * @var string
     */
    private $prefix = 'SELECT';

    /**
     * @var ExpressionInterface[]
     */
    private $projections = [];

    /**
     * @var ExpressionInterface[]
     */
    private $from = [];

    /**
     * @var array[] (expr => ExpressionInterface, condition => ExpressionInterface, type => string)
     */
    private $join = [];

    /**
     * @var ExpressionInterface
     */
    private $where = null;

    /**
     * @var array[] (expr => ExpressionInterface, direction => string)
     */
    private $groupBy = [];

    /**
     * @var ExpressionInterface
     */
    private $having = null;

    /**
     * @var array[] (expr => ExpressionInterface, direction => string)
     */
    private $orderBy = [];

    /**
     * @var integer
     */
    private $offset = null;

    /**
     * @var integer
     */
    private $limit = null;

    /**
     * @var string
     */
    private $suffix = null;

    /**
     * @var array[] (query => QueryBuilderInterface, type => string)
     */
    private $union = [];

    /**
     * @param string $prefix
     * @return self
     */
    public function prefix($prefix)
    {
        $chained = $this->chained();
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
        $chained = $this->chained();
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
        $chained = $this->chained();
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
        $chained = $this->chained();
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
        $chained = $this->chained();
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
        $chained = $this->chained();
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
        $chained = $this->chained();
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
        $chained = $this->chained();
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
        $chained = $this->chained();
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
        $chained = $this->chained();
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
        $chained = $this->chained();
        $chained->limit = $limit;
        return $chained;
    }

    /**
     * @param integer $integer
     * @return self
     */
    public function offset($offset)
    {
        $chained = $this->chained();
        $chained->offset = $offset;
        return $chained;
    }

    /**
     * @param string $suffix
     * @return self
     */
    public function suffix($suffix)
    {
        $chained = $this->chained();
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

    /**
     * @param QueryBuilderInterface $query
     * @param string                $type
     * @return self
     */
    public function union(QueryBuilderInterface $query, $type = 'UNION')
    {
        $chained = $this->chained();
        $chained->union[] = [
            'query' => $query,
            'type' => $type,
        ];
        return $chained;
    }

    /**
     * @param QueryBuilderInterface $query
     * @return self
     */
    public function unionAll(QueryBuilderInterface $query)
    {
        return $this->union($query, 'UNION ALL');
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        return $this->getCompiler()->compileSelect(
            $this->prefix,
            $this->projections,
            $this->from,
            $this->join,
            $this->where,
            $this->groupBy,
            $this->having,
            $this->orderBy,
            $this->limit,
            $this->offset,
            $this->suffix,
            $this->union
        );
    }

    /**
     * @return CompilerInterface
     */
    protected function getCompiler()
    {
        return DefaultCompiler::getInstance();
    }

    /**
     * @return self
     */
    protected function chained()
    {
        $chained = clone $this;
        return $chained;
    }
}
