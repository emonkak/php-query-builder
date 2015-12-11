<?php

namespace Emonkak\QueryBuilder;

trait Chainable
{
    /**
     * @return self
     */
    protected function chained()
    {
        $chained = clone $this;
        return $chained;
    }
}
