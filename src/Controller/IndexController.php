<?php

declare(strict_types=1);

namespace App\Controller;

use App\FakeBundle\DependencyInjection\FakeExtension;
use App\FakeBundle\FakeBundle;
use Symfony\Component\Config\Definition\Dumper\YamlReferenceDumper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class IndexController
{
    public function __invoke()
    {
        $yamlDumper = new YamlReferenceDumper();

        $containerBuilder = new ContainerBuilder();
        $fakeBundle = new FakeBundle();

        $containerBuilder->registerExtension($fakeBundle->getContainerExtension());

        $fakeBundle->build($containerBuilder);

        /** @var FakeExtension $fakeExtension */
        $fakeExtension = $containerBuilder->getExtension('fake');

        return new Response(
            $yamlDumper->dump($fakeExtension->getConfiguration([], $containerBuilder)),
            headers: ['Content-Type' => 'text/yaml'],
        );
    }
}
