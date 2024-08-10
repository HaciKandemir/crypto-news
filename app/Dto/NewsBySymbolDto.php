<?php

namespace App\Dto;

class NewsBySymbolDto
{
    public string $symbol;

    public function __construct(array $data)
    {
        $this->symbol = $data['symbol'];
    }
}
