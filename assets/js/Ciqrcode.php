<?php
/**
* PHP QR Code porting for Codeigniter
*
* @package        	CodeIgniter
* @subpackage    	Libraries
* @category    	Libraries
* 
* @version		1.0

*/

class Ciqrcode
{
	var $cacheable = true;
	var $cachedir = './cache/';
	var $errorlog = './logs/';
	var $quality = true;
	var $size = 1024;

	function __construct($config = array()) {

		/*Path to the front controller (this file) directory*/
		define('FCPATH', "");
		$this->initialize($config);
	}

	public function initialize($config = array()) {
		$this->cacheable = (isset($config['cacheable'])) ? $config['cacheable'] : $this->cacheable;
		$this->cachedir = (isset($config['cachedir'])) ? $config['cachedir'] : FCPATH.$this->cachedir;
		$this->errorlog = (isset($config['errorlog'])) ? $config['errorlog'] : FCPATH.$this->errorlog;
		$this->quality = (isset($config['quality'])) ? $config['quality'] : $this->quality;
		$this->size = (isset($config['size'])) ? $config['size'] : $this->size;

		/*use cache - more disk reads but less CPU power, masks and format templates are stored there*/
		if (!defined('QR_CACHEABLE')) define('QR_CACHEABLE', $this->cacheable);

		/*used when QR_CACHEABLE === true*/
		if (!defined('QR_CACHE_DIR')) define('QR_CACHE_DIR', $this->cachedir);

		/*default error logs dir*/
		if (!defined('QR_LOG_DIR')) define('QR_LOG_DIR', $this->errorlog);

		/*if true, estimates best mask (spec. default, but extremally slow; set to false to significant performance boost but (propably) worst quality code*/
		if ($this->quality) {
			if (!defined('QR_FIND_BEST_MASK')) define('QR_FIND_BEST_MASK', true);
		} else {
			if (!defined('QR_FIND_BEST_MASK')) define('QR_FIND_BEST_MASK', false);
			if (!defined('QR_DEFAULT_MASK')) define('QR_DEFAULT_MASK', $this->quality);
		}

		/*if false, checks all masks available, otherwise value tells count of masks need to be checked, mask id are got randomly*/
		if (!defined('QR_FIND_FROM_RANDOM')) define('QR_FIND_FROM_RANDOM', false);

		/*maximum allowed png image width (in pixels), tune to make sure GD and PHP can handle such big images*/
		if (!defined('QR_PNG_MAXIMUM_SIZE')) define('QR_PNG_MAXIMUM_SIZE',  $this->size);

		/*use cache - more disk reads but less CPU power, masks and format templates are stored there*/
		if(!defined('QR_CACHEABLE')) define('QR_CACHEABLE', true);
		/*used when QR_CACHEABLE === true*/
		if(!defined('QR_CACHE_DIR')) define('QR_CACHE_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR);  
		/*default error logs dir   */
		if(!defined('QR_LOG_DIR')) define('QR_LOG_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

		/*if true, estimates best mask (spec. default, but extremally slow; set to false to significant performance boost but (propably) worst quality code*/
		if(!defined('QR_FIND_BEST_MASK')) define('QR_FIND_BEST_MASK', true);
		/*if false, checks all masks available, otherwise value tells count of masks need to be checked, mask id are got randomly*/
		if(!defined('QR_FIND_FROM_RANDOM')) define('QR_FIND_FROM_RANDOM', false);
		/*when QR_FIND_BEST_MASK === false*/
		if(!defined('QR_DEFAULT_MASK')) define('QR_DEFAULT_MASK', 2);

		/*maximum allowed png image width (in pixels), tune to make sure GD and PHP can handle such big images*/
		if(!defined('QR_PNG_MAXIMUM_SIZE')) define('QR_PNG_MAXIMUM_SIZE',  1024);

			/*call original library*/
			include "qrconst.php";
			include "qrtools.php";
			include "qrspec.php";
			include "qrimage.php";
			include "qrinput.php";
			include "qrbitstream.php";
			include "qrsplit.php";
			include "qrrscode.php";
			include "qrmask.php";
			include "qrencode.php";
		}

		public function generate($params = array()) {
			if (isset($params['BGColor']) 
				&& is_array($params['BGColor']) 
				&& count($params['BGColor']) == 3 
				&& array_filter($params['BGColor'], 'is_int') === $params['BGColor']) {
				QRimage::$BGColor = $params['BGColor']; 
		}

		if (isset($params['FGColor']) 
			&& is_array($params['FGColor']) 
			&& count($params['FGColor']) == 3 
			&& array_filter($params['FGColor'], 'is_int') === $params['FGColor']) {
			QRimage::$FGColor = $params['FGColor']; 
	}

	if(isset($params['IsShowQRLogo']) && $params['IsShowQRLogo']==1)
	{
		if(isset($params['QRLogoPath']) && $params['QRLogoPath']!='')
		{
			QRimage::$qrlogo = $params['QRLogoPath']; 
		}
	}

	if(isset($params['addText']) && $params['addText']!='')
	{
		QRimage::$addText = $params['addText']; 
	}

	if(isset($params['addTextPosition']) && $params['addTextPosition']!='')
	{
		QRimage::$addTextPosition = $params['addTextPosition']; 
	}

	if(isset($params['labelImage']) && $params['labelImage']!='')
	{
		QRimage::$labelImage = $params['labelImage']; 
	}

	$params['data'] = (isset($params['data'])) ? $params['data'] : 'QR Code Library';
	if (isset($params['savename'])) {
		$level = 'L';
		if (isset($params['level']) && in_array($params['level'], array('L','M','Q','H'))) $level = $params['level'];

		$size = 4;
		if (isset($params['size'])) $size = min(max((int)$params['size'], 1), 10);

		QRcode::png($params['data'], $params['savename'], $level, $size, 2);
		return $params['savename'];
	} else {
		$level = 'L';
		if (isset($params['level']) && in_array($params['level'], array('L','M','Q','H'))) $level = $params['level'];

		$size = 4;
		if (isset($params['size'])) $size = min(max((int)$params['size'], 1), 10);

		QRcode::png($params['data'], NULL, $level, $size, 2);
	}
}
}

/* end of file */