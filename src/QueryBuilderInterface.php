<?php

namespace Emonkak\QueryBuilder;

interface QueryBuilderInterface extends QueryFragmentInterface
{
    /**
     * @return string
     */
    public function __toString();
}
