<?php
namespace Crystal\ImageResize\View\Helper\Factory;

use Zend\View\Helper\AbstractHelper;
use Crystal\ImageResize\Service\ImageThumbnail as ImageThumbnailService;
use Crystal\ImageResize\View\Helper\ImageThumbnail;
class ImageThumbnailFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,$requestedName,array $options = null)
    {

        $ImageThumbnailService=$container->get(ImageThumbnailService::class);
        return new ImageThumbnail($ImageThumbnailService);
    }
}
