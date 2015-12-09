<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Compiler\CompilerInterface;
use Emonkak\QueryBuilder\Compiler\SelectCompiler;

class SelectBuilder implements QueryInterface
{
    use ToStringable;
    use SelectBuilderTrait;

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @var array
     */
    private $fragments;

    /**
     * @return self
     */
    public static function createDefault()
    {
        return self::create(SelectCompiler::getInstance());
    }

    /**
     * @param CompilerInterface $compiler
     * @return self
     */
    public static function create(CompilerInterface $compiler)
    {
        $fragments = [
            'prefix' => 'SELECT',
            'projections' => [],
            'from' => [],
            'join' => [],
            'where' => [],
            'groupBy' => [],
            'having' => [],
            'orderBy' => [],
            'offset' => null,
            'limit' => null,
            'suffix' => null,
            'union' => [],
        ];
        return new static($compiler, $fragments);
    }

    /**
     * @param CompilerInterface $compiler
     * @param array             $fragments
     */
    protected function __construct(CompilerInterface $compiler, array $fragments)
    {
        $this->compiler = $compiler;
        $this->fragments = $fragments;
    }

    /**
     * {@inheritDoc}
     */
    public function compile()
    {
        return $this->compiler->compile($this->fragments);
    }

    /**
     * {@inheritDoc}
     */
    protected function set($key, $value)
    {
        $fragments = $this->fragments;
        $fragments[$key] = $value;
        return new static($this->compiler, $fragments);
    }

    /**
     * {@inheritDoc}
     */
    protected function add($key, $value)
    {
        $fragments = $this->fragments;
        $fragments[$key][] = $value;
        return new static($this->compiler, $fragments);
    }
}
