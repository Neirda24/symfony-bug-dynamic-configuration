<?php

declare(strict_types=1);

namespace App\FakeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class FakeBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
