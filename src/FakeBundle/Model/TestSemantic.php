<?php

declare(strict_types=1);

namespace App\FakeBundle\Model;

use App\FakeBundle\Attributes\ExposeSemantic;
use App\FakeBundle\Attributes\SemanticNode;
use App\FakeBundle\NodeBuilder\ArrayNodeBuilder;
use App\FakeBundle\NodeBuilder\EnumNodeBuilder;
use App\FakeBundle\NodeBuilder\ScalarNodeBuilder;
use App\FakeBundle\NodeBuilder\VariableNodeBuilder;
use App\FakeBundle\NodeBuilder\WebhookNodeBuilder;

#[SemanticNode('test')]
final class TestSemantic
{
    #[ExposeSemantic(new WebhookNodeBuilder('webhook', children: [
        new ScalarNodeBuilder('config_name', restrictTo: 'string'),
        new ArrayNodeBuilder('success', children: [
            new ScalarNodeBuilder('url', restrictTo: 'string'),
            new VariableNodeBuilder('route'),
            new EnumNodeBuilder('method', values: ['POST', 'PUT', 'PATCH']),
        ]),
        new ArrayNodeBuilder('error', children: [
            new ScalarNodeBuilder('url', restrictTo: 'string'),
            new VariableNodeBuilder('route'),
            new EnumNodeBuilder('method', values: ['POST', 'PUT', 'PATCH']),
        ]),
        new ArrayNodeBuilder('extra_http_headers', normalizeKeys: false, useAttributeAsKey: 'name', prototype: 'variable'),
    ]))]
    public function plop()
    {

    }
}
