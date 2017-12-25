<?php
namespace Crystal\ImageResize\Controller\Plugin;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Crystal\ImageResize\Service\ImageThumbnail as ImageThumbnailService;
class ImageThumbnail extends AbstractPlugin{
    protected $imageThumbnail;
    public function __construct(ImageThumbnailService $imageThumbnail){
        $this->imageThumbnail=$imageThumbnail;
    }
    public function __invoke(){
        return $this->imageThumbnail;
    }
}