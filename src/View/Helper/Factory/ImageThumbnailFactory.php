<?php
namespace Crystal\ImageResize\View\Helper\Factory;
use Crystal\ImageResize\Service\ImageThumbnail as ImageThumbnailService;
use Crystal\ImageResize\View\Helper\ImageThumbnail;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ImageThumbnailFactory implements FactoryInterface {
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {

		$ImageThumbnailService = $container->get(ImageThumbnailService::class);
		return new ImageThumbnail($ImageThumbnailService);
	}
}
