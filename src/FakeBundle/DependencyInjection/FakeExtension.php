<?php

namespace App\FakeBundle\DependencyInjection;

use App\FakeBundle\Model\TestSemantic;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class FakeExtension extends Extension
{
    private ConfigurationBuilder $configurationBuilder;

    public function __construct()
    {
        $this->configurationBuilder = new ConfigurationBuilder();
        $this->configurationBuilder->push(TestSemantic::class);
    }

    public function getConfiguration(array $config, ContainerBuilder $container): Configuration
    {
        return new Configuration($this->configurationBuilder);
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        dump($config);
    }
}
