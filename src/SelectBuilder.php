<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Compiler\CompilerInterface;
use Emonkak\QueryBuilder\Compiler\SelectCompiler;
use Emonkak\QueryBuilder\Expression\ExpressionResolver;

class SelectBuilder implements QueryInterface
{
    use ToStringable;
    use SelectBuilderTrait;

    /**
     * @var Compiler
     */
    protected $compiler;

    /**
     * @return self
     */
    public static function create()
    {
        return new static(new SelectCompiler());
    }

    /**
     * @param CompilerInterface $compiler
     */
    public function __construct(CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * {@inheritDoc}
     */
    public function compile()
    {
        return $this->compiler->compile($this->fragments);
    }
}
