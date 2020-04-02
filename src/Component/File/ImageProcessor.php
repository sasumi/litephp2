<?php
namespace Lite\Component\File;

use Lite\Exception\Exception;

class ImageProcessor {
	const TYPE_JPG = 'jpg';
	const TYPE_PNG = 'png';
	const TYPE_BMP = 'bmp';
	const TYPE_GIF = 'gif';

	private $image;
	private $width = 0;
	private $height = 0;
	private $ori_file;

	public function __construct($file){
		if(!is_file($file)){
			throw new Exception('Image file no exists:'.$file);
		}
		$this->ori_file = $file;
		$this->image = $this->createImageFromFile($file);
		list($this->width, $this->height) = getimagesize($file);
	}

	public function __destruct(){
		imagedestroy($this->image);
	}

	/**
	 * get image size
	 * @return array:
	 */
	public function getSize(){
		return array($this->width, $this->height);
	}
	
	/**
	 * resize image
	 * @param int|number $scale_rate
	 */
	public function resizeByRate($scale_rate = 1){
		if($scale_rate == 1){
			return;
		}
		$new_width = $this->width*$scale_rate;
		$new_height = $this->height*$scale_rate;
		$new_image = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->width, $this->height);
		imagedestroy($this->image);
		$this->image = $new_image;
		$this->width = $new_width;
		$this->height = $new_height;
	}
	
	/**
	 * resize by specified region info
	 * @param int $min_width
	 * @param int $min_height
	 */
	public function resizeByMinRegion($min_width = 0, $min_height = 0){
		if(!$min_width || !$min_height){
			return;
		}
		if($this->width/$this->height>$min_width/$min_height){
			$scale_rate = $min_height/$this->height;
		} else{
			$scale_rate = $min_width/$this->width;
		}
		$this->resizeByRate($scale_rate);
	}

	/**
	 * resize by specified max region
	 * @param int|number $max_width
	 * @param int|number $max_height
	 */
	public function resizeByMaxRegion($max_width = 0, $max_height = 0){
		if(!$max_width || !$max_height){
			return;
		}
		$width_scale_rate = $max_width/$this->width;
		$height_scale_rate = $max_height/$this->height;
		$scale_rate = min($width_scale_rate, $height_scale_rate);
		$this->resizeByRate($scale_rate);
	}

	/**
	 * crop image center by specified region
	 * @param number $width
	 * @param number $height
	 */
	public function cropCenterByRegion($width, $height){
		$new_image = imagecreatetruecolor($width, $height);
		$src_x = max((int)($this->width - $width)/2, 0);
		$src_y = max((int)($this->height - $height)/2, 0);
		imagecopyresampled($new_image, $this->image, 0, 0, $src_x, $src_y, $width, $height, $width, $height);

		$this->image = $new_image;
		$this->width = $width;
		$this->height = $height;
	}

	/**
	 * image rotation
	 * @param int|number $degree
	 * @param string $bgd_color
	 */
	public function rotate($degree=0, $bgd_color='#000000'){
		imagerotate($this->image, $degree, $bgd_color);
	}

	/**
	 * image reversal
	 * @param int|number $pos
	 * @throws Exception
	 */
	public function reversal($pos=0){
		if($pos == 0){

		} else if($pos == 1){

		} else if($pos == 2){

		} else {
			throw new Exception("parameter error");
		}
	}

	/**
	 * get image resource
	 * @return resource
	 */
	public function getImage(){
		return $this->image;
	}

	/**
	 * create image resource from file
	 * @param string $file
	 * @throws Exception
	 * @return resource
	 */
	private function createImageFromFile($file){
		$type = $this->getImageTypeFromExt($file);
		switch($type){
			case self::TYPE_JPG:
				return imagecreatefromjpeg($file);

			case self::TYPE_GIF:
				return imagecreatefromgif($file);

			case self::TYPE_PNG:
				return imagecreatefrompng($file);

			default:
				throw new Exception("image type error:".$type);
		}
	}

	private function getImageTypeFromExt($file){
		$ext = strtolower(array_pop(explode('.',$file)));
		switch ($ext){
			case 'jpg':
			case 'jpeg':
				return self::TYPE_JPG;

			case 'gif':
				return self::TYPE_GIF;

			case 'png':
				return self::TYPE_PNG;

			case 'bmp':
				return self::TYPE_BMP;
		}
		return $ext;
	}
	
	/**
	 * Save to image file
	 * @param $new_file
	 * @param string $file_type
	 * @param null $quality
	 * @throws \Exception
	 */
	public function saveToFile($new_file, $file_type='', $quality = null){
		if(!$file_type){
			$file_type = $this->getImageTypeFromExt($new_file);
		}

		switch($file_type){
			case self::TYPE_JPG:
				imagejpeg($this->image, $new_file, $quality);
				break;
			case self::TYPE_PNG:
				imagepng($this->image, $new_file, $quality);
				break;
			case self::TYPE_GIF:
				imagegif($this->image, $new_file);
				break;
			default:
				throw new \Exception('image saving handler error:'.$file_type);
		}
	}
}