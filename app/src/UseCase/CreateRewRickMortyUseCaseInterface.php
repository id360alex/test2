<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Entity\Reviews;

interface CreateRewRickMortyUseCaseInterface
{
    public function handle($request,array $queryParams): ?int;
}
