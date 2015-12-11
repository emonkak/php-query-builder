<?php

namespace Emonkak\QueryBuilder;

interface QueryFragmentInterface
{
    /**
     * @return array (string, mixed[])
     */
    public function build();
}
