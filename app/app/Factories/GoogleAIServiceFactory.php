<?php

declare(strict_types=1);

namespace App\Factories;

use App\Services\GoogleAIService;


final readonly class GoogleAIServiceFactory
{
    public function create(
        array $history
    ): GoogleAIService
    {
        return new GoogleAIService(
            $history
        );
    }
}
