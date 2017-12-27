<?php

namespace Crystal\ImageResize\Service;

class ImageThumbnail {
	protected $imageResize;
	protected $publicFolder = '';

	public function __construct(ImageResize $imageResize, $publicFolder) {
		$this->imageResize = $imageResize;
		$this->publicFolder = realpath($publicFolder);
	}
	public function getImageResize() {
		return $this->imageResize;
	}

	public function getPublicFolder() {
		return $this->publicFolder;
	}
	public function setPublicFolder($folder) {
		$this->publicFolder = $folder;
	}

	public function getImage($file, $width = null, $height = null, $publicFolder = null) {
		try {
			if ($publicFolder) {
				$this->publicFolder = $publicFolder;
			}

			if (!defined('DS')) {
				define('DS', DIRECTORY_SEPARATOR);
			}

			$file = realpath($file);	
			if (!is_file($file)) {
				return;
			}

			$this->imageResize->load($file);

			$ext = pathinfo($file);

			$ext = $ext['extension'];
			$filename = substr($file, strrpos($file, DS) + 1, -4);
			$o_filename = $filename . '.' . $ext;
			if (is_null($height) && is_null($width)) {
				// if both are null filename remains same as no resize is necessary
				$filename = $o_filename;
			} elseif (is_null($height)) {
				// if height is null name with width only
				$filename = $filename . '_' . $width . '.' . $ext;
			} elseif (is_null($width)) {
				// if width if null name with height only
				$filename = $filename . '_' . $height . '.' . $ext;
			} else {
				// if both are present name with both
				$filename = $filename . '_' . $width . 'x' . $height . '.' . $ext;
			}

			// remove filename from the path
			$path = str_replace($o_filename, '', $file);

			// check if cache directory in image folder exists if it does not create one
			if (!is_dir($path . 'cache' . DS)) {
				mkdir($path . 'cache' . DS, 0755);
			}

			//remove application path + public folder from the image path

			if ($this->publicFolder) {
				$section_path = str_replace($this->publicFolder, '', $path);
			}

			$parts = explode(DS, $section_path);
			if (is_null($height) && is_null($width)) {
				return implode('/', $parts) . '/' . $filename;
			}

			$section_url = implode('/', $parts) . 'cache/';

			if (is_file($path . 'cache' . DS . $filename)) {
				return $section_url . $filename;
			} else {
				if ($width > $height) {
					$this->imageResize->resizeToHeight($height);
				} else {

					$this->imageResize->resizeToWidth($width);
				}

				$this->imageResize->save($path . 'cache' . DS . $filename);
			}

			return $section_url . $filename;

		} catch (\Exception $e) {
			throw $e;
		}
	}

}
