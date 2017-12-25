<?php
namespace Crystal\ImageResize\Controller\Plugin;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Crystal\ImageResize\Service\ImageResize as ImageResizeService;
class ImageResize extends AbstractPlugin{
    protected $imageResizeService;
    public function __construct(ImageResizeService $imageResizeService){
        $this->imageResizeService=$imageResizeService;
    }
    public function __invoke(){
        return $this->imageResizeService;
    }
}