<?php
namespace Crystal\ImageResize\Service\Factory;

use Crystal\ImageResize\Service\ImageResize;
use Crystal\ImageResize\Service\ImageThumbnail;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ImageThumbnailFactory implements FactoryInterface {
	public function __invoke(ContainerInterface $container,
		$requestedName, array $options = null) {
		$imageResize = $container->get(ImageResize::class);
		$config=$container->get('config');
		return new ImageThumbnail($imageResize,$config['public_path']);
	}
}