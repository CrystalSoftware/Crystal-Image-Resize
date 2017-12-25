<?php
namespace Crystal\ImageResize\Controller\Plugin\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Crystal\ImageResize\Service\ImageResize as ImageResizeService;
use Crystal\ImageResize\Controller\Plugin\ImageResize;
class ImageResizeFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,$requestedName,array $options = null)
    {

        $imageResizeService=$container->get(ImageResizeService::class);
        return new ImageResize($imageResizeService);
    }
}
