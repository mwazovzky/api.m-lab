<?php

namespace App\AdjacencyList;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\MySqlGrammar;

class AdjacencyListGrammar extends MySqlGrammar
{
    protected $sql;

    public function __construct(string $sql)
    {
        $this->sql = $sql;
    }

    public function compileSelect(Builder $query)
    {
        return $this->sql;
    }
}
