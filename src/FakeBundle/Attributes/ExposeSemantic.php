<?php

namespace App\FakeBundle\Attributes;

use App\FakeBundle\NodeBuilder\NodeBuilderInterface;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class ExposeSemantic
{
    public function __construct(
        public readonly NodeBuilderInterface $node,
    ) {
    }
}
