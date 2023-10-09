<?php

namespace CrossSymfony\CorsGeneratorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use CrossSymfony\CorsGeneratorBundle\DependencyInjection\Configuration;
use Symfony\Component\Yaml\Yaml;

class CorsGeneratorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        // Generate and copy the default configuration file to the root config folder
        $defaultConfig = $this->processConfiguration(new Configuration(), $configs);
        $configFilePath = $container->getParameter('kernel.project_dir') . '/config/packages/cors_generator.yaml';

        if (!file_exists($configFilePath)) {
            file_put_contents($configFilePath, Yaml::dump(['cors_generator' => $defaultConfig], 4));
        }

        foreach ($defaultConfig as $key => $value) {
            $container->setParameter('cors_generator.' . $key, $value);
        }
    }
}