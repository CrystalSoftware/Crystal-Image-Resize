<?php
namespace Crystal\ImageResize\Service;
use \Exception;
use \RuntimeException;

class ImageResize {
	public $image;
	public $image_type;
	private $image_save_path;
	private $image_save_name;
	private $image_tmp_name;
	private $image_original_height;
	private $image_original_width;

	public function __construct() {
		if (!extension_loaded('gd')) {
			throw new \RuntimeException("Image Resize class needs GD extension");
		}
	}

	public function getExtension() {
		switch ($this->image_type) {
		case IMAGETYPE_JPEG:
			return 'jpg';
			break;
		case IMAGETYPE_PNG:
			return 'png';
		case IMAGETYPE_GIF:
			return 'gif';
		}
	}

	public function getType() {
		return $this->image_type;
	}

	function load($filename) {
		if (!is_file($filename)) {
			throw new \RuntimeException("Image File Does not exist.");
		}
		$image_info = getimagesize($filename);
		$this->image_tmp_name = $filename;
		$this->image_type = $image_info[2];
		if ($this->image_type == IMAGETYPE_JPEG) {
			$this->image = imagecreatefromjpeg($filename);
		} elseif ($this->image_type == IMAGETYPE_GIF) {
			$this->image = imagecreatefromgif($filename);
		} elseif ($this->image_type == IMAGETYPE_PNG) {
			$this->image = imagecreatefrompng($filename);
		}
		$this->image_original_height = $this->getHeight();
		$this->image_original_width = $this->getWidth();
	}

	function save($filename, $image_type = '', $overwrite = true, $compression = 100, $permissions = null) {
		try {
			if ($image_type == '') {
				$image_type = $this->image_type;
			}
			if ($image_type == IMAGETYPE_JPEG) {
				if ($overwrite) {
					if (is_file($filename)) {
						unlink($filename);
					}
				}
				$filename = trim($filename, '.jpg');
				$filename = trim($filename, '.jpeg');
				$filename = $filename . '.jpg';
				imagejpeg($this->image, $filename, 100);
			} elseif ($image_type == IMAGETYPE_GIF) {
				if ($overwrite) {
					if (is_file($filename)) {
						unlink($filename);
					}
				}
				$filename = trim($filename, '.gif');
				$filename = $filename . '.gif';
				imagegif($this->image, $filename);
			} elseif ($image_type == IMAGETYPE_PNG) {
				if ($overwrite) {
					if (is_file($filename)) {
						unlink($filename);
					}
				}
				if ($this->getHeight() == $this->image_original_height || $this->getWidth() == $this->image_original_width) {
					$this->_resize($this->getWidth(), $this->getHeight());
				}
				$filename = trim($filename, '.png');
				$filename = $filename . '.png';
				imagepng($this->image, $filename, 9, PNG_ALL_FILTERS);
			}
			if ($permissions != null) {
				chmod($filename, $permissions);
			}
			return $filename;
		} catch (Exception $e) {
			throw $e;
		}
	}

	function output($image_type = IMAGETYPE_JPEG) {
		if ($image_type == IMAGETYPE_JPEG) {
			imagejpeg($this->image);
		} elseif ($image_type == IMAGETYPE_GIF) {
			imagegif($this->image);
		} elseif ($image_type == IMAGETYPE_PNG) {
			imagepng($this->image);
		}
	}

	function getWidth() {
		return imagesx($this->image);
	}

	function getHeight() {
		return imagesy($this->image);
	}

	function resizeToHeight($height) {
		if ($height < $this->getHeight()) {
			$ratio = $height / $this->getHeight();
			$width = $this->getWidth() * $ratio;
			$this->_resize($width, $height);
		}
	}

	function resizeToWidth($width) {
		if ($width < $this->getWidth()) {
			$ratio = $width / $this->getWidth();
			$height = $this->getheight() * $ratio;
			$this->_resize($width, $height);
		}
	}

	public function resize($width, $height) {
		if ($width < $this->getWidth() || $height < $this->getHeight()) {
			// compare width to height ratio
			if (($width / $height) > ($this->getWidth() / $this->getHeight())) {
				// resize by width
				$ratio = $width / $this->getWidth();
			} else {
				// resize by height
				$ratio = $height / $this->getHeight();
			}
			if ($width == $height) {
				if ($this->getWidth() > $this->getHeight()) {
					$ratio = $width / $this->getWidth();
				} else {
					$ratio = $height / $this->getHeight();
				}
			}

			$width = ceil($this->getWidth() * $ratio);
			$height = ceil($this->getHeight() * $ratio);
			// now resize
			$this->_resize($width, $height);
		}
	}

	function scale($scale) {
		$width = $this->getWidth() * $scale / 100;
		$height = $this->getheight() * $scale / 100;
		$this->_resize($width, $height);
	}

	function _resize($width, $height) {

		$new_image = imagecreatetruecolor($width, $height);

		if ($this->image_type == IMAGETYPE_GIF) {
			imagealphablending($new_image, false);
			imagesavealpha($new_image, true);
			$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
			imagefilledrectangle($new_image, 0, 0, $this->getWidth(), $this->getHeight(), $transparent);
			imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		} else if ($this->image_type == IMAGETYPE_JPEG) {
			imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		} else {
			// its png now process it
			$sourcemg = $this->image_tmp_name;
			$s = getimagesize($sourcemg);
			$w = $width;
			$h = $height;
			$source = imagecreatefrompng($sourcemg);
			$new_image = imagecreatetruecolor($w, $h);

			$palette = (imagecolortransparent($source) < 0);

			if (!$palette || (ord(file_get_contents($sourcemg, false, null, 25, 1)) & 4)) {
				if (($tc = imagecolorstotal($source)) && $tc <= 256) {
					imagetruecolortopalette($new_image, false, $tc);
				}

				imagealphablending($new_image, false);
				$alpha = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
				imagefill($new_image, 0, 0, $alpha);
				imagesavealpha($new_image, true);
			}

			imagecopyresampled($new_image, $source, 0, 0, 0, 0, $w, $h, $s[0], $s[1]);
			if ((ord(file_get_contents($sourcemg, false, null, 25, 1)) & 4)) {
				$new_imagex = min(max(floor($w / 50), 1), 10);
				$new_imagey = min(max(floor($h / 50), 1), 10);

				$palette = true;
				for ($x = 0; $x < $w; $x = $x + $new_imagex) {
					for ($y = 0; $y < $h; $y = $y + $new_imagey) {
						$col = imagecolorsforindex($new_image, imagecolorat($new_image, $x, $y));
						if ($col['alpha'] > 13) {
							$palette = false;
							break 2;
						}
					}
				}
			}
			if ($palette) {
				imagetruecolortopalette($new_image, false, 256);
			}
		}

		$this->image = $new_image;
	}

}