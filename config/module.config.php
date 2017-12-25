<?php
namespace Crystal\ImageResize;
use Zend\ServiceManager\Factory\InvokableFactory;

return [

	'controller_plugins' => [
		'factories' => [
			Controller\Plugin\ImageResize::class => Controller\Plugin\Factory\ImageResizeFactory::class,
			Controller\Plugin\ImageThumbnail::class => Controller\Plugin\Factory\ImageThumbnailFactory::class,
		],
		'aliases' => [
			'ImageResize' => Controller\Plugin\ImageResize::class,
			'imageResize' => Controller\Plugin\ImageResize::class,
			'ImageThumbnail' => Controller\Plugin\ImageThumbnail::class,
			'imageThumbnail' => Controller\Plugin\ImageThumbnail::class,
		],
	],
	'view_helpers' => [
		'factories' => [
			View\Helper\ImageThumbnail::class => View\Helper\Factory\ImageThumbnailFactory::class,
		],
		'aliases' => [
			'ImageThumbnail' => View\Helper\ImageThumbnail::class,
			'imageThumbnail' => View\Helper\ImageThumbnail::class,
		],

	],
	'service_manager' => [
		'factories' => [
			Service\ImageResize::class => InvokableFactory::class,
			Service\ImageThumbnail::class => Service\Factory\ImageThumbnailFactory::class,
		],

	],

];
