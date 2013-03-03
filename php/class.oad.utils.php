<?php
	
	/*
	 * File				: class.oad.utils.php
	 * Author			: Barry Jones (barry@OnAllDevices.com)
	 * Purpose			: This class is a container for various utility functions
	*/
	class c_oad_utils {

		///////////////////////////////////////////////////
		// Properties
		
		// Array of small words 
		public $smallwords = array(
			'of','a','the','and','an','or','nor','but','is','if','then','else','when', 'at','from','by','on','off',
			'for','in','out','over','to','into','with'
		);		
		
		//////////////////////////////////////////////////
		// Protected Methods

		// Convert hi-asciii chars
		protected function _cleanHiAscii($v_input_str, $v_from_type = 'ord', $v_to_type = 'xml'){
			GLOBAL $v_html_hi_ascci_ord;
			GLOBAL $v_html_hi_ascci_html;
			GLOBAL $v_html_hi_ascci_xhtml;
			if(!isset($v_html_hi_ascci_ord))
			{
				$v_html_hi_ascci_ord = Array(chr(128),chr(130),chr(131),chr(132),chr(133),chr(134),chr(135),chr(136),chr(137),chr(138),chr(139),chr(140),chr(145),chr(146),chr(147),chr(148),chr(149),chr(150),chr(151),chr(152),chr(153),chr(154),chr(155),chr(156),chr(159),chr(160),chr(161),chr(162),chr(163),chr(164),chr(165),chr(166),chr(167),chr(168),chr(169),chr(170),chr(171),chr(172),chr(173),chr(174),chr(175),chr(176),chr(177),chr(178),chr(179),chr(180),chr(181),chr(182),chr(183),chr(184),chr(185),chr(186),chr(187),chr(188),chr(189),chr(190),chr(191),chr(192),chr(193),chr(194),chr(195),chr(196),chr(197),chr(198),chr(199),chr(200),chr(201),chr(202),chr(203),chr(204),chr(205),chr(206),chr(207),chr(208),chr(209),chr(210),chr(211),chr(212),chr(213),chr(214),chr(215),chr(216),chr(217),chr(218),chr(219),chr(220),chr(221),chr(222),chr(223),chr(224),chr(225),chr(226),chr(227),chr(228),chr(229),chr(230),chr(231),chr(232),chr(233),chr(234),chr(235),chr(236),chr(237),chr(238),chr(239),chr(240),chr(241),chr(242),chr(243),chr(244),chr(245),chr(246),chr(247),chr(248),chr(249),chr(250),chr(251),chr(252),chr(253),chr(254),chr(255));
				$v_html_hi_ascci_html = Array('&euro;','&sbquo;','&fnof;','&bdquo;','&hellip;','&dagger;','&Dagger;','&circ;','&permil;','&Scaron;','&lsaquo;','&OElig;','&lsquo;','&rsquo;','&ldquo;','&rdquo;','&bull;','&ndash;','&mdash;','&tilde;','&trade;','&scaron;','&rsaquo;','&oelig;','&Yuml;','&nbsp;','&iexcl;','&cent;','&pound;','&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;','&laquo;','&not;','&shy;','&reg;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;','&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;','&thorn;','&yuml;');
				$v_html_hi_ascci_xhtml = Array('&#8364;','&#8218;','&#402;','&#8222;','&#8230;','&#8224;','&#8225;','&#710;','&#8240;','&#352;','&#8249;','&#338;','&#8216;','&#8217;','&#8220;','&#8221;','&#8226;','&#8211;','&#8212;','&#732;','&#8482;','&#353;','&#8250;','&#339;','&#376;','&#160;','&#161;','&#162;','&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#169;','&#170;','&#171;','&#172;','&#173;','&#174;','&#175;','&#176;','&#177;','&#178;','&#179;','&#180;','&#181;','&#182;','&#183;','&#184;','&#185;','&#186;','&#187;','&#188;','&#189;','&#190;','&#191;','&#192;','&#193;','&#194;','&#195;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;','&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#209;','&#210;','&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;','&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;','&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;','&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;','&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;','&#251;','&#252;','&#253;','&#254;','&#255;');
			}
		
			switch($v_from_type)
			{
				case 'ord':  $v_tmp_srch = &$v_html_hi_ascci_ord;  break;
				case 'html': $v_tmp_srch = &$v_html_hi_ascci_html; break;
				case 'xhtml':$v_tmp_srch = &$v_html_hi_ascci_xhtml;break;
				default:     $v_tmp_srch = &$v_html_hi_ascci_ord;  break;
			}
			switch($v_to_type)
			{
				case 'ord':  $v_tmp_repl = &$v_html_hi_ascci_ord;  break;
				case 'html': $v_tmp_repl = &$v_html_hi_ascci_html; break;
				case 'xhtml':$v_tmp_repl = &$v_html_hi_ascci_xhtml;break;
				default:     $v_tmp_repl = &$v_html_hi_ascci_xhtml;break;
			}
		
			return str_replace($v_tmp_srch, $v_tmp_repl,$v_input_str);
		} // _cleanHiAscii()
		
		// Converts a given string into xhtml entities
		protected function _convertToXHTMLEntities($v_input_str, $v_no_html = false){
			if($v_no_html === false){
				return $this->_cleanHiAscii($this->_cleanHiAscii(html_entity_decode($v_input_str), 'ord', 'xhtml'), 'html', 'xhtml');
			} else {
				return $this->_cleanHiAscii($this->_cleanHiAscii(strip_tags(html_entity_decode($v_input_str)), 'ord', 'xhtml'), 'html', 'xhtml');
			}
		} // _convertToXHTMLEntities()
		
		// Convert UTF8 string into XHTML entities
		protected function _convertUTF8ToXHTML($v_input_str, $v_ignore_html_tags = false, $v_old_version = false){
			if($v_ignore_html_tags)
			{
				if ($v_old_version)
				return htmlspecialchars_decode($this->_cleanHiAscii(htmlentities(html_entity_decode($v_input_str, ENT_COMPAT, 'UTF-8'), ENT_COMPAT, 'UTF-8'), 'html', 'xml'));
				else
				return htmlspecialchars_decode($this->_cleanHiAscii($this->_convertToXHTMLEntities(html_entity_decode($v_input_str, ENT_COMPAT, 'UTF-8')), 'html', 'xml'));
			}
			else
			{
				if ($v_old_version)
				return $this->_cleanHiAscii(htmlentities(html_entity_decode($v_input_str, ENT_COMPAT, 'UTF-8'), ENT_COMPAT, 'UTF-8'), 'html', 'xml');
				else
				return $this->_cleanHiAscii($this->_convertToXHTMLEntities(htmlspecialchars(html_entity_decode($v_input_str, ENT_COMPAT, 'UTF-8'))), 'html', 'xml');
			}
		} // _convertUTF8ToXHTML()
		
		//////////////////////////////////////////////////
		// Public Methods

		// Time format is UNIX timestamp or
		// PHP strtotime compatible strings
		public function dateDiff($time1, $time2, $precision = 6) {
			// If not numeric then convert texts to unix timestamps
			if (!is_int($time1)) {
				$time1 = strtotime($time1);
			}
			if (!is_int($time2)) {
				$time2 = strtotime($time2);
			}

			// If time1 is bigger than time2
			// Then swap time1 and time2
			if ($time1 > $time2) {
				$ttime = $time1;
				$time1 = $time2;
				$time2 = $ttime;
			}

			// Set up intervals and diffs arrays
			$intervals = array('year','month','day','hour','minute','second');
			$diffs = array();

			// Loop thru all intervals
			foreach ($intervals as $interval) {
				// Set default diff to 0
				$diffs[$interval] = 0;
				// Create temp time from time1 and interval
				$ttime = strtotime("+1 " . $interval, $time1);
				// Loop until temp time is smaller than time2
				while ($time2 >= $ttime) {
					$time1 = $ttime;
					$diffs[$interval]++;
					// Create new temp time from time1 and interval
					$ttime = strtotime("+1 " . $interval, $time1);
				}
			}

			$count = 0;
			$times = array();
			// Loop thru all diffs
			foreach ($diffs as $interval => $value) {
				// Break if we have needed precission
				if ($count >= $precision) {
					break;
				}
				// Add value and interval
				// if value is bigger than 0
				if ($value > 0) {
					// Add s if value is not 1
					if ($value != 1) {
						$interval .= "s";
					}
					// Add value and interval to times array
					$times[] = $value . " " . $interval;
					$count++;
				}
			}

			// Return string with times
			return implode(", ", $times);
		}

		// Attempts to convert a string from an unknown charset into xhtml encoding
		public function convertToXHTML($v_str){
			return $this->isUTF8($v_str) ? $this->_convertUTF8ToXHTML($v_str) : $this->_convertToXHTMLEntities($v_str);
		} // convertToXHTML()
		
		// Converts xhtml entities to a UTF8 string
		public function convertToUTF8($v_input_str){
			return $this->_cleanHiAscii(html_entity_decode($v_input_str), 'xhtml', 'ord');
		} // convertToUTF8()
		
		// This function detects if a string is UTF-8 encoded or not.
		// From http://w3.org/International/questions/qa-forms-utf-8.html
		public function isUTF8($v_string) {
			return preg_match('%^(?:
			         [\x09\x0A\x0D\x20-\x7E]            # ASCII
			       | [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
			       |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
			       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
			       |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
			       |  \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
			       | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
			       |  \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
			)*$%xs', $v_string);		  
		} // isUTF8()
		
		// Is current request https?
		public function isRequestSecure(){
			global $_SERVER;		
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
				return true;
			} else if (isset($_SERVER['HTTP_SSL']) && $_SERVER['HTTP_SSL'] == "1"){
				return true;
			} else {
				return false;
			}
		}
		
		// Function to get request ip address.
		// Takes into account proxy servers and ip masking
		public function getRequestIp(){
			global $_SERVER;		
			if(isset($_SERVER['HTTP_CLIENT_IP']) && strlen($_SERVER['HTTP_CLIENT_IP']) > 6){
				return $_SERVER['HTTP_CLIENT_IP'];
			} else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && strlen($_SERVER['HTTP_X_FORWARDED_FOR']) > 6){
				return $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				return $_SERVER['REMOTE_ADDR'];
			}
		} // getRequestIp()
		
		// Function to recursively merge arrays to any level
		// NOTE: PHP's array_merge_recursive() is broken
		public function mergeArrayRecursive() {
				
			// Holds all the arrays passed
			$a_params = &func_get_args();
		
			// First array is used as the base, everything else overwrites on it
			$a_return = array_shift($a_params);
		
			// Merge all arrays on the first array
			foreach ($a_params as $a_array) {
				foreach ($a_array as $v_key => $v_value) {
					 
					// Numeric keyed values are added (unless already there)
					if (is_numeric ($v_key) && (!in_array($v_value, $a_return))) {
						if (is_array($v_value)) {
							$a_return[] = $this->mergeArrayRecursive($a_return[$$v_key], $v_value);
						} else {
							$a_return[] = $v_value;
						}
		
						// String keyed values are replaced
					} else {
						if (isset ($a_return[$v_key]) && is_array($v_value) && is_array($a_return[$v_key])) {
							$a_return[$v_key] = $this->mergeArrayRecursive($a_return[$$v_key], $v_value);
						} else {
							$a_return[$v_key] = $v_value;
						}
					}
				}
			}
		
			return $a_return;
		} // mergeArrayRecursive()
		
		// This function takes a string, typically from a textarea, and turns \n or \r\n end of line characters into <br /> characters
		public function convertLineBreaksToHTML($v_str){
			$v_str = preg_replace("/(\r\n|\n|\r)/", "\n", $v_str); // cross-platform newlines
			$v_str = preg_replace("/\n\n+/", "\n\n", $v_str); // take care of duplicates
			$v_str = preg_replace('|(?<!</p>)\s*\n|', "<br />\n", $v_str); // optionally make line breaks
			$v_str = preg_replace('!(</(?:table|[ou]l|pre|select|form|blockquote)>)<br />!', "$1", $v_str);
			$v_str = str_replace('<br /><br />', '<br />', $v_str);
			$v_str = str_replace('<br><br>', '<br />', $v_str);
			return $v_str;
		} // convertLineBreaksToHTML()

		// Validate a date against a given format
		public function validateDate($v_date, $v_format = 'YYYY-MM-DD'){
			switch($v_format)
			{
				case 'YYYY/MM/DD':
				case 'YYYY-MM-DD':
					list($y, $m, $d) = preg_split( '/[-\.\/ ]/', $v_date);
					break;
		
				case 'YYYY/DD/MM':
				case 'YYYY-DD-MM':
					list($y, $d, $m) = preg_split( '/[-\.\/ ]/', $v_date);
					break;
		
				case 'DD-MM-YYYY':
				case 'DD/MM/YYYY':
					list($d, $m, $y) = preg_split( '/[-\.\/ ]/', $v_date);
					break;
		
				case 'MM-DD-YYYY':
				case 'MM/DD/YYYY':
					list($m, $d, $y) = preg_split( '/[-\.\/ ]/', $v_date);
					break;
		
				case 'YYYYMMDD':
					$y = substr($v_date, 0, 4);
					$m = substr($v_date, 4, 2);
					$d = substr($v_date, 6, 2);
					break;
		
				case 'YYYYDDMM':
					$y = substr($v_date, 0, 4);
					$d = substr($v_date, 4, 2);
					$m = substr($v_date, 6, 2);
					break;
		
				default:
					throw new Exception( "Invalid Date Format" );
			}
			return checkdate($m, $d, $y);
		} // validateDate()
		
		// Create a scaled PNG with transparency and save it to the path specified
		public function createScaledPNGAlphaImage($v_img_path_src, $v_img_path_dest, $v_new_width){
				
			$v_error = '';
				
			// Validate paths
			if (!file_exists($v_img_path_src)){
				$v_error = 'Invalid source image path.';
			}
			if (!file_exists(dirname($v_img_path_dest))){
				$v_error = 'Invalid destination image directory.';
			}
				
			// Read in the image
			if (strlen($v_error) == 0){
				$o_master_img = imagecreatefrompng($v_img_path_src);
				if ($o_master_img === false){
					$v_error = 'Failed to create image object from source.';
				}
			}
		
			// Grab image dimensions
			if (strlen($v_error) == 0){
				$v_image_width 		= imagesx($o_master_img);
				$v_image_height 	= imagesy($o_master_img);
				if ($v_image_width === false || $v_image_height === false){
					imagedestroy($o_master_img);
					$v_error = 'Failed to retrieve dimensions from image object.';
				}
			}
		
			// Calculate height
			if (strlen($v_error) == 0){
				$v_aspect_ratio		= $v_new_width / $v_image_width;
				$v_new_height		= intval($v_image_height * $v_aspect_ratio);
			}
		
			// Create blank canvas
			if (strlen($v_error) == 0){
				$o_new_image = imagecreatetruecolor($v_new_width, $v_new_height);
				if ($o_new_image === false){
					imagedestroy($o_master_img);
					$v_error = 'Failed to create new blank image.';
				}
			}
				
			// Make BG transparent
			if (strlen($v_error) == 0){
				if (!imagealphablending($o_new_image, false)){
					imagedestroy($o_master_img);
					imagedestroy($o_new_image);
					$v_error = 'Failed to blend alpha channel in new image object.';
				}
			}
			if (strlen($v_error) == 0){
				if (!imagesavealpha($o_new_image, true)){
					$v_error = 'Failed to save alpha channel in new image object.';
				}
			}
				
			// Resize source into canvas
			if (strlen($v_error) == 0){
				if (!imagecopyresampled($o_new_image, $o_master_img, 0, 0, 0, 0, $v_new_width, $v_new_height, $v_image_width, $v_image_height)){
					$v_error = 'Failed to resample image.';
				}
			}
		
			// Make BG transparent
			if (strlen($v_error) == 0){
				if (!imagealphablending($o_new_image, false)){
					$v_error = 'Failed to blend alpha channel in new image object.';
				}
			}
			if (strlen($v_error) == 0){
				if (!imagesavealpha($o_new_image, true)){
					$v_error = 'Failed to save alpha channel in new image object.';
				}
			}
				
			// Save to disk
			if (strlen($v_error) == 0){
				if (!imagepng($o_new_image, $v_img_path_dest)){
					$v_error = 'Failed to save image object to destination directory.';
				}
			}
				
			// Housekeeping and return
			if ($o_master_img){
				imagedestroy($o_master_img);
			}
			if ($o_master_img){
				imagedestroy($o_new_image);
			}
			return $v_error;
		} // createScaledPNGAlphaImage()
		
		// Convert a date in a known UK format to a timestamp
		// Returns false if invalid format DD/MM/YYYY or DD-MM-YYYY
		public function convertUKDateToTS ($v_input_date){
				
			// Validate
			if (!$this->validateDate($v_input_date, 'DD/MM/YYYY')){
				return false;
			}
				
			// Convert into ANSI format
			$a_uk_format = list($d, $m, $y) = preg_split( '/[-\.\/ ]/', $v_input_date);
			$v_ansi_format = $a_uk_format[2].'-'.$a_uk_format[1].'-'.$a_uk_format[0];
				
			// Return a timestamp
			return strtotime($v_ansi_format);
				
		} // convertUKDateToTS()
		
		// Return x unique random values from an indexed array
		public function getRandomArrayValues($a_source, $v_howmany){
		
			// Validate
			if (!is_array($a_source) || count($a_source) == 0){
				return false;
			}
				
			// Cap to max
			$v_howmany = min($v_howmany, count($a_source));
		
			// Get random array indexes
			$a_picked_idxs = array_rand($a_source, $v_howmany);
		
			// Grab values
			$a_winners = array();
			foreach ($a_picked_idxs as $v_idx){
				$a_winners[] = $a_source[$v_idx];
			}
		
			return $a_winners;
		
		} // getRandomArrayValues()
		
		// Validate telephone number chars (simple)
		function validateTelephoneNumber($v_tel){
			return preg_match("/^[0-9 \-\+()]+$/D", $v_tel);
		} // validateTelephoneNumber()
		 
		// Validate email address
		public function validateEmailAddress($v_email){
			return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $v_email);
		} // validateEmailAddress()
		
		// Set specific response headers
		public function setResponseHeaders($v_type, $v_cache_offset_secs = 0){
			$time = time() - 60; // or filemtime($fn), etc
			switch($v_type){
				case 'html':
					header('HTTP/1.1 200 OK');
					header('Pragma: Public', true);
					header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT');
					header('Content-Type: text/html; charset=utf-8');
					break;
				case '404':
					header('HTTP/1.1 404 Not Found');
					break;
				case 'xhtml':
					header('HTTP/1.1 200 OK');
					//header('Content-language: en');
					header('Pragma: Public', true);
					$time = time() - 60; // or filemtime($fn), etc
					header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT');
					header('Content-Type: application/xhtml+xml; charset=utf-8');
					break;
				case 'imagecacheenable':
					header("Expires: " . gmdate("D, d M Y H:i:s", time() + $v_cache_offset_secs) . " GMT");
					header('Cache-Control: max-age='.$v_cache_offset_secs);
					header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT');
					header('Pragma: public');
					break;
				case 'json':
					header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT');
					header('Cache-Control: no-cache, must-revalidate');
					header('Expires: 0');
					header('Content-type: application/json');
					break;
				case 'javascript':
					header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT');
					header('Cache-Control: no-cache, must-revalidate');
					header('Accept-Ranges : bytes');
					header('Expires: 0');
					header('Content-type: application/javascript');
					break;
				case "download":
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					break;
				case 'pdf':
					header('Content-Type: application/pdf');
					break;
				case 'jpg':
				case 'jpeg':
					header('Content-Type: image/jpeg');
					break;
				case 'png':
					header('Content-Type: image/png');
					break;
				case 'gif':
					header('Content-Type: image/gif');
					break;
				case 'vcard':
					header('Content-Description: File Transfer');
					header('Content-Type: text/x-vcard');
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					break;
				case 'binary':
				default:
					header('Content-Type: application/octet-stream');
			}
		} // setResponseHeaders()
		
		// Serve a given file for viewing
		public function fileServe ($v_filename){
			if (file_exists($v_filename)) {
				$a_pathinfo = pathinfo($v_filename);
				$this->setResponseHeaders($a_pathinfo['extension']);
				ob_clean();
				flush();
				readfile($v_filename);
				exit;
			}
		} // fileServe()
		
		// Clean a string as a CSV field (replaces commas and dbl quotes)
		public function cleanCSVString ($v_in){
			return trim(html_entity_decode(str_replace(',', '.', str_replace('"', "'", $v_in))));
		} // cleanCSVString()

		/**
		 * Formats a line (passed as a fields  array) as CSV and returns the CSV as a string.
		 * Adapted from http://us3.php.net/manual/en/function.fputcsv.php#87120
		 */
		function arrayToCsv(array &$fields, $delimiter = ';', $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false) {
			$delimiter_esc = preg_quote($delimiter, '/');
			$enclosure_esc = preg_quote($enclosure, '/');

			$output = array();
			foreach ( $fields as $field ) {
				if ($field === null && $nullToMysqlNull) {
					$output[] = 'NULL';
					continue;
				}

				// Enclose fields containing $delimiter, $enclosure or whitespace
				if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
					$output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
				}
				else {
					$output[] = $field;
				}
			}

			return implode( $delimiter, $output );
		} // arrayToCsv()

		// Serve a CSV string as file download
		public function csvFileServe ($v_csvdata, $v_filename){
			$this->setResponseHeaders('download');
			header('Content-Disposition: attachment; filename='.$v_filename);
			header('Content-Length: ' . strlen($v_csvdata));
			ob_clean();
			flush();
			echo ($v_csvdata);
			exit;
		} // csvFileServe()
		
		// This function generates a random alphanumeric password of length $length
		public function createPassword($v_length = 10) {
			$v_password = '';
			if ($v_length < 1){
				return "";
			}
			$a_characters = "ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz1234567890";
			for ($i = 0; $i < $v_length; $i++){
				$v_password .= $a_characters[mt_rand() % 58];
			}
			return $v_password;
		} // createPassword()
		
		// Make a random string
		public function makeString($v_length = 10) {
			$v_str = '';
			if ($v_length < 1){
				return "";
			}
			$a_characters = "ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz1234567890";
			for ($i = 0; $i < $v_length; $i++){
				$v_str .= $a_characters[mt_rand() % 58];
			}
			return $v_str;
		} // makeString()

		// Return x words from a string
		public function getWords($v_string, $v_len = 50, $v_ellipsis = "...")
		{
			$a_words = explode(' ', $v_string);
			if (count($a_words) > $v_len){
				return implode(' ', array_slice($a_words, 0, $v_len)) . $v_ellipsis;
			} else {
				return $v_string;
			}
		}

		// Get the current page name
		// If URL is passed, use that, otherwise use $_SERVER
		public function getCurrentPageName($v_url = false){
			$v_uri = ($v_url ? $v_url : $_SERVER['SCRIPT_NAME']);
			if (stripos($v_uri, '.php') === false){
				return '';
			} else {
				$a_parts = explode('/', $v_uri);
				if (count($a_parts) == 0){
					return '';
				}
				return $a_parts[count($a_parts) - 1];
			}
		} // getCurrentPageName()
		
		// Send xml to a remote web service
		public function postXMLWebService ($v_url, $v_xml, $v_debug = false){
		
			// Create cURL request
			$v_ua = $_SERVER['HTTP_USER_AGENT'];
			$o_ch = curl_init(); // initialize curl handle
			curl_setopt($o_ch, CURLOPT_URL, $v_url); // set url to post to
			curl_setopt($o_ch, CURLOPT_HTTPHEADER, array(
								'Content-Type: application/xml',
								'Accept: text/xml'
			));
			curl_setopt($o_ch, CURLOPT_FOLLOWLOCATION, 1); // allow redirects
			curl_setopt($o_ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
			curl_setopt($o_ch, CURLOPT_PORT, 80); //Set the port number
			if ($v_debug){
				curl_setopt($o_ch, CURLOPT_VERBOSE, 1);
			}
			curl_setopt($o_ch, CURLOPT_TIMEOUT, 30); // times out after 30s
			if ($v_xml){
				curl_setopt($o_ch, CURLOPT_POST, 1);
				curl_setopt($o_ch, CURLOPT_POSTFIELDS, $v_xml);
			} else {
				curl_setopt($o_ch, CURLOPT_POST, 0);
			}
			curl_setopt($o_ch, CURLOPT_USERAGENT, $v_ua);
		
			// Call service and return data
			$a_data = curl_exec($o_ch);
			$a_hdrs = curl_getinfo($o_ch);
			curl_close($o_ch);
			if ($a_hdrs['http_code'] != 200){
				return false;
			}
			return $a_data;
		
		} // postXMLWebService()
		
		// Make an httpRequest request. Supports basic authentication
		public function httpRequest($v_url, $a_prms = array(), $a_hdrs = array(), $v_method = 'GET', $v_username = '', $v_password = ''){
			
			// Define return structure
			$a_return = array(
				"success"		=> false,
				"error"			=> '',
				"headers"		=> array(),
				"response"		=> null
			);
			
			try {
				
				// Build http header and url
				$a_hdrs[] = 'Accept-Encoding: none';
				$a_hdrs[] = 'Connection: Close';
				if (strlen($v_username) > 0){
					$a_hdrs[] = 'Authorization: Basic '.base64_encode("{$v_username}:{$v_password}");
				}
				
				// Create cURL request
				$o_ch = curl_init(); // initialize curl handle
				curl_setopt($o_ch, CURLOPT_HTTPHEADER, $a_hdrs);
				curl_setopt($o_ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
				curl_setopt($o_ch, CURLOPT_PORT, 80); //Set the port number
				if (DEV_DEBUG){
					curl_setopt($o_ch, CURLOPT_VERBOSE, 1);
				}
				curl_setopt($o_ch, CURLOPT_TIMEOUT, 30); // times out after 30s
				if ($v_method == 'GET'){
					curl_setopt($o_ch, CURLOPT_POST, 0);
					$v_url .= '?'.http_build_query($a_prms);
				} else {
					curl_setopt($o_ch, CURLOPT_POST, 1);
					if (count($a_prms) > 0){
						curl_setopt($o_ch, CURLOPT_POSTFIELDS, $a_prms);
					}
				}
				curl_setopt($o_ch, CURLOPT_URL, $v_url); 
				
				// Grab response
				$a_return['response'] 	= curl_exec($o_ch);
				$a_return['headers']	= curl_getinfo($o_ch);
				curl_close($o_ch);
				
				// Return
				$a_return['success']	= ($a_return['headers']['http_code'] == 200);
				if ($a_return['headers']['http_code'] != 200){
					$a_return['error']  	= trim(str_replace(PHP_EOL, '', $a_return['response']));
				}				
				
			} catch(Exception $e) {
				$a_return['error'] = $e->getMessage();
			}
			
			return $a_return;
			
		} // httpRequest()
		
		// Clean a native javascript date string
		public function cleanJSDate ($v_date){
			$v_pos = strpos($v_date, '(');
			if ($v_pos !== false){
				return substr($v_date, 0, $v_pos);
			}
			return $v_date;
		} // cleanJSDate()
		
		// Normalise the whitespace in a string
		public function normaliseWhitespace($v_str){
			$a_find = array(' ', "\t", PHP_EOL, '&#x0020;', '&#x00A0;', '&#x1680;', '&#x180E;', '&#x2000;', '&#x2001;', '&#x2002;', '&#x2003;', '&#x2004;', '&#x2005;', '&#x2006;', '&#x2007;', '&#x2008;', '&#x2009;', '&#x200A;', '&#x202F;', '&#x205F;', '&#x3000;');
			return trim(preg_replace('/\s\s+/', ' ', str_replace($a_find, ' ', $v_str)));
			//return trim(preg_replace('/\s\s+/', ' ', preg_replace('/[^(\x20-\x7F)]*/','', $v_str)));
		} // normaliseWhitespace()
		
		// Display single xml validation error
		function formatXMLError($o_error){
			$v_return = '';
			switch ($error->level) {
				case LIBXML_ERR_WARNING:
					$v_return = 'Warning '.$o_error->code.': ';
					break;
				case LIBXML_ERR_ERROR:
					$v_return = 'Error '.$o_error->code.': ';
					break;
				case LIBXML_ERR_FATAL:
					$v_return = 'Fatal '.$o_error->code.': ';
					break;
			}
			$v_return .= trim($o_error->message);
			$v_return .= ' on line '.$o_error->line.'.';
		
			return $v_return;
		}
		
		// Display XML validation errors
		function formatXMLErrors() {
			$a_ret = array();
			$a_errors = libxml_get_errors();
			foreach ($a_errors as $o_error) {
				$a_ret[] = $this->formatXMLError($o_error);
			}
			libxml_clear_errors();
			return $a_ret;
		}
		
		// Format and prepare request vars
		// Converts checkboxes and radios into bools
		public function getRequestVar($v_name, $v_method = 'request', $v_data_type = 'string'){
			switch ($v_method){
				case 'get':
					$a_req = $_GET;
					break;
				case 'post':
					$a_req = $_POST;
					break;
				default:
					$a_req = $_REQUEST;
				break;
			}
			switch($v_data_type){
				case 'int':
					return isset($a_req[$v_name]) ? intval($a_req[$v_name]): null;
					break;
				case 'float':
					return isset($a_req[$v_name]) ? floatval($a_req[$v_name]): null;
					break;
				case 'string':
					return isset($a_req[$v_name]) ? strval($a_req[$v_name]): '';
					break;
				case 'bool':
					if (isset($a_req[$v_name])){
						if ($a_req[$v_name] == 'on'){
							return 1;
						} else {
							return intval((bool)$a_req[$v_name]);
						}
					} else {
						return 0;
					}
					break;
			}
		} // getRequestVar()
		
		// Validate and grab administrators email address and name
		public function getAdminEmailDetails(){
				
			global $settings;
				
			// Check for admin email settings
			$v_admin_email 	= $settings->get('SMTP_ADMIN_EMAILADDR');
			$v_admin_name 	= $settings->get('SMTP_ADMIN_EMAILNAME');
			if (!$settings || !$v_admin_email){
				return false;
			}
			$v_admin_name = strlen($v_admin_name) == 0 ? 'Administrator' : $v_admin_name;
		
			return array("email" => $v_admin_email, "name" => $v_admin_name);
				
		} // getAdminEmailDetails()
		
		// Validate and grab web servers email address and name
		public function getServerEmailDetails(){
		
			global $settings;
		
			// Check for admin email settings
			$v_svr_email 	= $settings->get('SMTP_SERVER_EMAILADDR');
			$v_svr_name 	= $settings->get('SMTP_SERVER_EMAILNAME');
			if (!$settings || !$v_svr_email){
				return false;
			}
			$v_svr_name = strlen($v_svr_name) == 0 ? 'Web Server' : $v_svr_name;
		
			return array("email" => $v_svr_email, "name" => $v_svr_name);
		
		} // getServerEmailDetails()
		
		// Validate and grab SMTP email server details
		public function getSMTPServerDetails(){
		
			global $settings;
		
			// Check for smtp server settings
			$v_svr_host 	= $settings->get('SMTP_SERVER_HOST');
			$v_svr_secure 	= $settings->get('SMTP_SERVER_SECURE');
			$v_svr_port 	= $settings->get('SMTP_SERVER_PORT');
			$v_svr_user 	= $settings->get('SMTP_SERVER_USER');
			$v_svr_pwd 		= $settings->get('SMTP_SERVER_PASS');
		
			return array(
				"host" 		=> $v_svr_host, 
				"secure"	=> $v_svr_secure,
				"port"		=> $v_svr_port,
				"user" 		=> $v_svr_user, 
				"pass" 		=> $v_svr_pwd
			);
		
		} // getSMTPServerDetails()

		// Validate and grab customer services email address and name
		public function getCustomerServiceEmailDetails(){

			global $settings;

			// Check for cs email settings
			$v_cs_email 	= $settings->get('SITE_EMAIL');
			$v_cs_name 	= $settings->get('SITE_EMAIL_NAME');
			if (!$settings || !$v_cs_email){
				return false;
			}
			$v_cs_name = strlen($v_cs_name) == 0 ? 'Customer Services' : $v_cs_name;

			return array("email" => $v_cs_email, "name" => $v_cs_name);

		} // getCustomerServiceEmailDetails()

		// Send the site administrator an email
		public function sendAdminEmail($v_subject, $v_message){
			try {

				// Check email class is included
				if (!class_exists('c_oad_email')){
					global $libroot;
					include_once ($libroot.'class.oad.email.php');
				}

				// Grab the SMTP server config
				$a_smtpcfg	= $this->getSMTPServerDetails();
				if ($a_smtpcfg === false){
					throw new Exception ('There was a problem retrieving the smtp server config from global settings.');
				}

				// Configure our gmail smtp object
				$o_gmail = new c_oad_email (array(
					"debug"			=> DEV_DEBUG,				// Display SMTP communications
					"remotehost"	=> $a_smtpcfg['host'],		// SMTP account hostname
					"secure"		=> $a_smtpcfg['secure'],	// SMTP account secure? ('ssl' or '')
					"remoteport"	=> $a_smtpcfg['port'],		// SMTP account port number
					"username"		=> $a_smtpcfg['user'],		// SMTP account username
					"password"		=> $a_smtpcfg['pass']		// SMTP account password
				));

				// Set FROM and REPLY-TO headers (NOTE - GMail must be configured to send via this account)
				$a_svremail		= $this->getServerEmailDetails();
				if ($a_svremail === false){
					throw new Exception ('There was a problem retrieving the web servers email address from global settings.');
				}
				$o_gmail->setFrom($a_svremail['email'], $a_svremail['name']);

				// Recipient (Administrator)
				$a_adminemail		= $this->getAdminEmailDetails();
				if ($a_adminemail === false){
					throw new Exception ('There was a problem retrieving the web server admin email address from global settings.');
				}
				$o_gmail->addRecipients(array($a_adminemail['email'] => $a_adminemail['name']));

				// Message subject
				$o_gmail->setSubject($v_subject);

				// Send the email
				$o_gmail->sendText($v_message);

			} catch (Exception $e) {
				unset($o_gmail);
				return "Error sending admin email: ".$e->getMessage();
			}

			return ''; // Success
		} // sendAdminEmail()

		// Send a customer service email
		public function sendCustomerServiceEmail($v_subject, $v_message, $v_html = false){
			try {

				// Check email class is included
				if (!class_exists('c_oad_email')){
					global $libroot;
					include_once ($libroot.'class.oad.email.php');
				}

				// Grab the SMTP server config
				$a_smtpcfg	= $this->getSMTPServerDetails();
				if ($a_smtpcfg === false){
					throw new Exception ('There was a problem retrieving the smtp server config from global settings.');
				}

				// Configure our gmail smtp object
				$o_gmail = new c_oad_email (array(
					"debug"			=> DEV_DEBUG,				// Display SMTP communications
					"remotehost"	=> $a_smtpcfg['host'],		// SMTP account hostname
					"secure"		=> $a_smtpcfg['secure'],	// SMTP account secure? ('ssl' or '')
					"remoteport"	=> $a_smtpcfg['port'],		// SMTP account port number
					"username"		=> $a_smtpcfg['user'],		// SMTP account username
					"password"		=> $a_smtpcfg['pass']		// SMTP account password
				));

				// Set FROM and REPLY-TO headers (NOTE - GMail must be configured to send via this account)
				$a_svremail		= $this->getServerEmailDetails();
				if ($a_svremail === false){
					throw new Exception ('There was a problem retrieving the web servers email address from global settings.');
				}
				$o_gmail->setFrom($a_svremail['email'], $a_svremail['name']);

				// Recipient (customer service)
				$a_csemail		= $this->getCustomerServiceEmailDetails();
				if ($a_csemail === false){
					throw new Exception ('There was a problem retrieving the customer services email address from global settings.');
				}
				$o_gmail->addRecipients(array($a_csemail['email'] => $a_csemail['name']));

				// Message subject
				$o_gmail->setSubject($v_subject);

				// Send the email
				if ($v_html){
					$o_gmail->sendHTML($v_message, strip_tags($v_message));
				} else {
					$o_gmail->sendText($v_message);
				}

			} catch (Exception $e) {
				unset($o_gmail);
				return "Error sending customer service email: ".$e->getMessage();
			}

			return ''; // Success
		} // sendCustomerServiceEmail()

		// Send an HTML email
		// Return value is error message - success is zero-length string
		public function sendSingleHTMLEmail($v_to_email, $v_to_name, $v_subject, $v_body_html, $v_body_text = ''){
			try {
			
				// Check email class is included
				if (!class_exists('c_oad_email')){
					global $libroot;
					include_once ($libroot.'class.oad.email.php');
				}
				
				// Grab the SMTP server config
				$a_smtpcfg	= $this->getSMTPServerDetails();
				if ($a_smtpcfg === false){
					throw new Exception ('There was a problem retrieving the smtp server config from global settings.');
				}
				
				// Configure our gmail smtp object
				$o_gmail = new c_oad_email (array(
					"debug"			=> DEV_DEBUG,				// Display SMTP communications
					"remotehost"	=> $a_smtpcfg['host'],		// SMTP account hostname
					"secure"		=> $a_smtpcfg['secure'],	// SMTP account secure? ('ssl' or '')
					"remoteport"	=> $a_smtpcfg['port'],		// SMTP account port number
					"username"		=> $a_smtpcfg['user'],		// SMTP account username
					"password"		=> $a_smtpcfg['pass']		// SMTP account password
				));
				
				// Set FROM and REPLY-TO headers (NOTE - GMail must be configured to send via this account)
				$a_svremail		= $this->getServerEmailDetails();
				if ($a_svremail === false){
					throw new Exception ('There was a problem retrieving the web servers email address from global settings.');
				}
				$o_gmail->setFrom($a_svremail['email'], $a_svremail['name']);
				
				// Recipient
				$o_gmail->addRecipients(array($v_to_email => $v_to_name));
				
				// Message subject
				$o_gmail->setSubject($v_subject);
				
				// Send the email
				$o_gmail->sendHTML($v_body_html, $v_body_text);
				
			} catch (Exception $e) {
				unset($o_gmail);
				return "Error sending email: ".$e->getMessage();
			}
			
			return ''; // Success
			
		} // sendSingleHTMLEmail()
		
		// Convert a stdObject into assoc array
		public function convertObjectToArray ($o_obj){
			return json_decode(json_encode($o_obj), true);
		} // convertObjectToArray()
		
		// Extracts URLs from a string
		// Returns an array with two keys:
		//		"links"	- an array of links found
		//		"text"	- the original string with the URLs removed
		public function extractURLS ($v_source){
			
			// Prep arrays
			$a_protocols	= array('http:', 'https:', 'ftp:', 'file:');
			$a_bits 		= explode(' ', $v_source);
			$a_links		= array();
			$a_strings		= array();

			// Process each part of the source string 
			foreach ($a_bits as $v_idx => $v_val){
				
				// Check the start of this string part for a known URL protocol
				$v_found = false;
				foreach ($a_protocols as $v_proto){
					if (stristr($v_val, $v_proto)){
						$v_found = true;
						$a_links[] = $v_val;
					}
				}
				if (!$v_found){
					$a_strings[] = $v_val;
				}
				
			}
			
			// Return 
			return array(
				"links"		=> $a_links,
				"text"		=> implode(' ', $a_strings)
			);
			
		} // extractURLS()

		// Functions to record current page as true referrer
		public function strleft($s1, $s2) {
			return substr($s1, 0, strpos($s1, $s2));
		} // strleft()
		public function recordCurrentUrl() {
			if(!isset($_SERVER['REQUEST_URI'])) {
				$serverrequri = $_SERVER['PHP_SELF'];
			}
			else {
				$serverrequri = $_SERVER['REQUEST_URI'];
			}
			$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
			$protocol = $this->strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
			$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
			$_SESSION['referrer'] = $protocol."://".$_SERVER['SERVER_NAME'].$port.$serverrequri;
		} // recordCurrentUrl()
		public function getReferrer(){
			return (isset($_SESSION['referrer']) ? $_SESSION['referrer'] : '/');
		} // getReferrer()

		// Redirect to last url (referrer)
		public function back(){
			header("Location: ".$this->getReferrer());
		}

		// HTTP Redirect
		public function redirect($v_url, $v_permanent = false){
			if ($v_permanent){
				header("HTTP/1.1 301 Moved Permanently");
			}
			echo 'Redirecting..';
			header("Location: ".$v_url);
			exit;
		} // redirect()

		// Clear the current output buffer and preserve any headers
		public function clearResponseBuffer (){
			$headers = array();
			if ( !headers_sent() ) {
				$headers = apache_response_headers();
			}

			ob_end_clean();
			ob_start();

			if ( !empty( $headers ) ) {
				foreach ( $headers as $name => $value ) {
					header( "$name: $value" );
				}
			}
		} // clearResponseBuffer()

		// Read in and cleanse a CSV file
		// Returns error string, or filedata array
		public function readCSVFile($v_filepath, $v_startrow = 0){
		
			$a_rows = array();
			
			// Open file and set pointer
			$v_file = fopen($v_filepath, 'r');
			if ($v_file === false){
				return 'Unable to read file "'.$v_filepath.'".';
			}
			$i = 0; while ($i++ < $v_startrow){
				$a_binme = fgetcsv($v_file);
			}
			
			// Read in rows
			while (($a_line = fgetcsv($v_file)) !== FALSE) {
			
				// Clean
				$a_clean = array_map('htmlentities', $a_line);
				$a_clean = array_map('trim', $a_clean);
				
				// Include if not empty
				if (strlen(trim(implode('', $a_clean), ',')) > 0){
					$a_rows[] = $a_clean;
				}
			}
			
			// Clean up and return data
			fclose($v_file);
			return $a_rows;
		}

		// Get tracking image beacon url for google analytics mobile
		// Copyright 2010 Google Inc. All Rights Reserved.
		public function analytics_get_mobile_beacon() {
			global $settings;
			if (
				!DEV_DEBUG &&
				strlen($settings->get('GOOGLE_ANALYTICS_MOBILE_ID')) &&
				strlen(GOOGLE_ANALYTICS_MOBILE_PIXEL)
			){
				try {
					$v_url          = "";
					$v_url          .= GOOGLE_ANALYTICS_MOBILE_PIXEL . "?";
					$v_url          .= "utmac=" . $settings->get('GOOGLE_ANALYTICS_MOBILE_ID');
					$v_url          .= "&utmn=" . rand(0, 0x7fffffff);
					$v_referer      = (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"]: '');
					$v_query        = $_SERVER["QUERY_STRING"];
					$v_path         = $_SERVER["REQUEST_URI"];
					if (empty($v_referer)) {$v_referer = "-";}
					$v_url          .= "&utmr=".urlencode($v_referer);
					if (!empty($v_path)) {$v_url .= "&utmp=".urlencode($v_path);}
					$v_url          .= "&guid=ON";
					return '<img src="'.str_replace("&", "&amp;", $v_url).'" title="" />';
				} catch(Exception $e){
					return '<!-- ERROR building google mobile tracking beacon url: '.$e->getMessage().'. -->';
				}
			} else {
				return '<!-- NOTICE Criteria not met for placing mobile tracking code, check config file. -->';
			}
		} // getGoogleAnalyticsMobileBeaconUrl()

		// Get tracking script for google analytics
		// Copyright 2010 Google Inc. All Rights Reserved.
		public function analytics_get_trackscript() {
			global $settings;
			if (
				!DEV_DEBUG &&
				strlen($settings->get('GOOGLE_ANALYTICS_ID'))
			){
				try {
					return "
				<script>
					var _gaq = _gaq || [];
					_gaq.push(['_setAccount', '".$settings->get('GOOGLE_ANALYTICS_ID')."']);
					_gaq.push(['_trackPageview']);
					(function() {
						var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
						ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
						var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
					})();
				</script>
						";
				} catch(Exception $e){
					return '<!-- ERROR building google analytics script: '.$e->getMessage().'. -->';
				}
			} else {
				return '<!-- NOTICE Criteria not met for placing google analytics script, check config file. -->';
			}
		} // getGoogleAnalyticsScript()

		// Force a trailing slash on a path
		public function forceTrailingSlash($v_path){
			return rtrim($v_path, '/').'/';
		}

		// Get a list of files in a given absolute directory (not recursive)
		// Provide an optional array of file extensions to filter (don't include period)
		public function getFiles($v_root, $a_filter_exts = array(), $addroot = true){
			$a_files    = scandir ($v_root);
			$a_excludes = array('..', '.');
			if ($a_files !== false){
				foreach ($a_files as $v_file){

					// Check file ext filter
					$v_ext = pathinfo($v_file, PATHINFO_EXTENSION);
					if (!in_array($v_ext, $a_filter_exts)){
						$a_excludes[] = $v_file;
					}

				}

				// Remove excluded files
				$a_files = array_diff($a_files, $a_excludes);
				$a_files = array_values($a_files);

				// Append root to each file
				if ($addroot){
					function _addRoot(&$v_item, $v_key, $v_prefix){
						$v_item = $v_prefix.$v_item;
					}
					array_walk($a_files, '_addRoot', $this->forceTrailingSlash($v_root));
				}

			}
			return $a_files;
		} // getFiles()

		// Turn the output buffer capture on
		public function bufferOn(){
			ob_start();
		}

		// Turn the output buffer capture off and return the captured data
		public function bufferOff(){
			$v_buff = ob_get_contents();
			ob_end_clean();
			return $v_buff;
		}

		// Add a flash message
		public function setFlash($v_name, $v_value){
			$_SESSION['flashdata'][$v_name] = $v_value;
		}

		// Grab a flash message
		public function getFlash($v_name, $v_preserve = false){
			$v_val = isset($_SESSION['flashdata'][$v_name]) ? $_SESSION['flashdata'][$v_name] : null;
			if (!$v_preserve){
				unset($_SESSION['flashdata'][$v_name]);
			}
			return $v_val;
		}

		// Regenerate the current session
		public function newSession(){
			session_regenerate_id(true);
		}

	} // c_oad_utils

	// Debugging output function (DUMP)
	// HTTP and CLI compatible
	function dump($v_stuff){
		$v_is_cmdline = (PHP_SAPI === 'cli');
		if (!$v_is_cmdline){echo '<pre class="debug">';}
		if (is_array($v_stuff) || is_object($v_stuff)){
			print_r($v_stuff);
		} else {
			$v_delim = ($v_is_cmdline) ? "\n" : "<br />";
			echo $v_stuff.$v_delim;
		}
		if (!$v_is_cmdline){echo "</pre>";}
	}
