<?php

namespace App\Dto;

class NewsByTimeDto
{
    public ?string $symbol;
    public ?\DateTime $fromDate;
    public \DateTime $toDate;

    public function __construct(array $data)
    {
        $this->symbol = $data['symbol'] ?? null;
        $this->fromDate = !empty($data['fromDate']) ? new \DateTime($data['fromDate']) : null;
        $this->toDate = new \DateTime($data['toDate']);
    }
}
