<?php
namespace Crystal\ImageResize\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Crystal\ImageResize\Service\ImageThumbnail as ImageThumbnailService;
class ImageThumbnail extends AbstractHelper {
	protected $imageThumbnailService=null;
	public function __construct(ImageThumbnailService $imageThumbnailService){
		$this->imageThumbnailService=$imageThumbnailService;
	}
	public function __invoke(){
		return $this->imageThumbnailService;
	}

}