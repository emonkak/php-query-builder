<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Clause\Alias;
use Emonkak\QueryBuilder\Clause\ConditionalJoin;
use Emonkak\QueryBuilder\Clause\Join;
use Emonkak\QueryBuilder\Clause\Sort;
use Emonkak\QueryBuilder\Clause\Union;
use Emonkak\QueryBuilder\Compiler\DefaultCompiler;
use Emonkak\QueryBuilder\Expression\ExpressionResolver;
use Emonkak\QueryBuilder\QueryFragmentInterface;

trait SelectQueryBuilderTrait
{
    /**
     * @var string
     */
    private $prefix = 'SELECT';

    /**
     * @var QueryFragmentInterface[]
     */
    private $projections = [];

    /**
     * @var QueryFragmentInterface[]
     */
    private $from = [];

    /**
     * @var QueryFragmentInterface[]
     */
    private $join = [];

    /**
     * @var QueryFragmentInterface
     */
    private $where = null;

    /**
     * @var QueryFragmentInterface[]
     */
    private $groupBy = [];

    /**
     * @var QueryFragmentInterface
     */
    private $having = null;

    /**
     * @var QueryFragmentInterface[]
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
     * @var QueryFragmentInterface[]
     */
    private $union = [];

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return QueryFragmentInterface[]
     */
    public function getProjections()
    {
        return $this->projections;
    }

    /**
     * @return QueryFragmentInterface[]
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return QueryFragmentInterface[]
     */
    public function getJoin()
    {
        return $this->join;
    }

    /**
     * @return QueryFragmentInterface
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @return QueryFragmentInterface[]
     */
    public function getGroupBy()
    {
        return $this->groupBy;
    }

    /**
     * @return QueryFragmentInterface
     */
    public function getHaving()
    {
        return $this->having;
    }

    /**
     * @return QueryFragmentInterface[]
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @return integer
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @return QueryFragmentInterface[]
     */
    public function getUnion()
    {
        return $this->union;
    }

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
        if ($condition !== null) {
            $condition = ExpressionResolver::resolveCreteria($condition);
            $definition = new ConditionalJoin($table, $condition, $type);
        } else {
            $definition = new Join($table, $type);
        }
        $chained = $this->chained();
        $chained->join[] = $definition;
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
     * @param string $ordering
     * @return self
     */
    public function groupBy($expr, $ordering = null)
    {
        $expr = ExpressionResolver::resolveCreteria($expr);
        if ($ordering !== null) {
            $expr = new Sort($expr, $ordering);
        }
        $chained = $this->chained();
        $chained->groupBy[] = $expr;
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
     * @param stirng $ordering
     * @return self
     */
    public function orderBy($expr, $ordering = null)
    {
        $expr = ExpressionResolver::resolveCreteria($expr);
        if ($ordering !== null) {
            $expr = new Sort($expr, $ordering);
        }
        $chained = $this->chained();
        $chained->orderBy[] = $expr;
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
        $chained->union[] = new Union($query, $type);
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
    abstract protected function chained();
}
