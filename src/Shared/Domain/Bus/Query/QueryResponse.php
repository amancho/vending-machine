<?php declare(strict_types=1);

namespace App\Shared\Domain\Bus\Query;

interface QueryResponse
{
    public function toArray(): array;
}