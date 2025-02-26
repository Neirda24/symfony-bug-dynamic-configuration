<?php

declare(strict_types=1);

namespace App\FakeBundle\DependencyInjection;

use App\FakeBundle\Attributes\ExposeSemantic;
use App\FakeBundle\Attributes\SemanticNode;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use function array_reverse;

final class ConfigurationBuilder
{
    /**
     * @var array<string, class-string>
     */
    private array $nameToClassMap = [];

    /**
     * @var array<string, NodeDefinition>
     */
    public array $nameToNodeMap = [];

    public function push(string $class): void
    {
        if (\in_array($class, $this->nameToClassMap, true)) {
            return; // TODO : understand why this is called two times on fresh cache with tests
            throw new \LogicException('logic');
        }

        $reflection = new \ReflectionClass($class);
        $nodeAttributes = $reflection->getAttributes(SemanticNode::class);

        if (\count($nodeAttributes) === 0) {
            throw new \LogicException(\sprintf('%s is missing the %s attribute', $class, SemanticNode::class));
        }

        /** @var SemanticNode $semanticNode */
        $semanticNode = $nodeAttributes[0]->newInstance();

        $this->nameToClassMap[$semanticNode->name] = $class;

        $root = new ArrayNodeDefinition($semanticNode->name);

        foreach (array_reverse($reflection->getMethods(\ReflectionMethod::IS_PUBLIC)) as $method) {
            $attributes = $method->getAttributes(ExposeSemantic::class);

            if (\count($attributes) === 0) {
                continue;
            }

            /** @var ExposeSemantic $attribute */
            $attribute = $attributes[0]->newInstance();

            $root->append($attribute->node->create());
        }

        $this->nameToNodeMap ??= [];
        $this->nameToNodeMap[$semanticNode->name] = $root;
    }
}
