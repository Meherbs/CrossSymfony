<?php

namespace CrossSymfony\CorsGeneratorBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use CrossSymfony\CorsGeneratorBundle\EventListener\ResponseListener;
use CrossSymfony\CorsGeneratorBundle\DependencyInjection\CorsGeneratorExtension;

class CorsGeneratorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->registerExtension(new CorsGeneratorExtension());
    }

    public function getContainerExtension()
    {
        return new CorsGeneratorExtension();
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    } 
}