<?php



/**
 * Minifier Class
 * @author Marco Cesarato <cesarato.developer@gmail.com>
 * @copyright Copyright (c) 2019
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link https://github.com/marcocesarato/PHP-Minifier
 * @version 0.1.5
 */
class Minifier
{
	/**
	 * Compress HTML
	 * @param $buffer
	 * @return null|string|string[]
	 */
	public function minify(&$buffer, $ext, $extra='') {
        $ext .= $extra; //permet de gérer le cas particulier des fichiers de langues
        switch (trim($ext)){
            case 'js'     : return $this->minifyJS($buffer);   break;
            case 'jslang' : return $this->minifyJSLanguage($buffer);   break;
            case 'css'    : return $this->minifyCSS($buffer);  break;
            case 'html'   : return $this->minifyHTML($buffer); break;
        }
        return null;
    }
    
	/**
	 * Compress HTML
	 * @param $buffer
	 * @return null|string|string[]
	 */
	public function minifyHTML($buffer) {
		$this->zlibCompression();
		if ($this->isHTML($buffer)) {
			$pattern = "/<script[^>]*>(.*?)<\/script>/is";
			preg_match_all($pattern, $buffer, $matches, PREG_SET_ORDER, 0);
			foreach ($matches as $match) {
				$pattern = "/(<script[^>]*>)(" . preg_quote($match[1], '/') . ")(<\/script>)/is";
				$compress = self::compressJS($match[1]);
				$buffer = preg_replace($pattern, '$1' . $compress . '$3', $buffer);
			}
			$pattern = "/<style[^>]*>(.*?)<\/style>/is";
			preg_match_all($pattern, $buffer, $matches, PREG_SET_ORDER, 0);
			foreach ($matches as $match) {
				$pattern = "/(<style[^>]*>)(" . preg_quote($match[1], '/') . ")(<\/style>)/is";
				$compress = self::compressCSS($match[1]);
				$buffer = preg_replace($pattern, '$1' . $compress . '$3', $buffer);
			}
			$buffer = preg_replace(array('/<!--[^\[](.*)[^\]]-->/Uuis', "/[[:blank:]]+/u", '/\s+/u'), array('', ' ', ' '), str_replace(array("\n", "\r", "\t"), '', $buffer));
		}
		return $buffer;
	}

	/**
	 * Compress CSS
	 * @param $buffer
	 * @return string
	 */
	public function minifyCSS($buffer) {
		$this->zlibCompression();
		//$buffer = preg_replace(array('#\/\*[\s\S]*?\*\/#', '/\s+/'), array('', ' '), str_replace(array("\n", "\r", "\t"), '', $buffer));

        $this->minify_common($buffer);
        $buffer = str_replace( array('( ',' ('), '(', $buffer );
        $buffer = str_replace( array(' )'), ')', $buffer );
        $buffer = str_replace( array('= ',' ='), '=', $buffer );
        
		$buffer = str_replace(array("\n", "\r", "\t"), '', $buffer);
        
        return $buffer;
	}

	/**
	 * Compress Javascript
	 * @param $buffer
	 * @return string
	 */

	public function minifyJS($buffer) {
		$this->zlibCompression();
        $this->minify_common($buffer);
      $buffer = str_replace( array('|| ',' ||'), '||', $buffer );
        $buffer = str_replace( array('( ',' ('), '(', $buffer );
        $buffer = str_replace( array(' )',') '), ')', $buffer );
        $buffer = str_replace( array('= ',' ='), '=', $buffer );
        $buffer = str_replace( array('+ ',' +'), '+', $buffer );
        $buffer = str_replace( array('- ',' -'), '-', $buffer );
        $buffer = str_replace( array('* ',' *'), '*', $buffer );
        $buffer = str_replace( array('/ ',' /'), '/', $buffer );
/*
		$buffer =  str_replace(array("\n", "\r", "\t"), '', preg_replace(array('#\/\*[\s\S]*?\*\/|([^:]|^)\/\/.*$#m', '/\s+/'), array('', ' '), $buffer));
		$buffer =  preg_replace(array('#\/\*[\s\S]*?\*\/|([^:]|^)\/\/.*$#m', '/\s+/'), array('', ' '), $buffer);
*/        
		$buffer =  str_replace(array("\n", "\r", "\t"), '', $buffer);

    return trim($buffer);
  } // minifyJS  
  
	/**
	 * Compress Javascript pour des fichiers de langues
	 * nottament il ne faut pas supprimler les espace avant les parenthès ou accolades
	 * @param $buffer
	 * @return string
	 */

	public function minifyJSLanguage($buffer) {
		$this->zlibCompression();
        $buffer = preg_replace( '#/\*.*?\*/#s', '', $buffer );
      
        // remove all comment lines
        $buffer = preg_replace( '#//(.*)$#m', '', $buffer );
        
        // remove all begin and end space of each line
        $buffer = $this->trimAllLines($buffer);
  
        $buffer =  str_replace(array("\n", "\r", "\t"), '', $buffer);
  
        return $buffer;
    } // minifyJSLanguage  
    
	/**
	 * Minifie for all type af file
	 * @param $string
	 * @return string
	 */
	private function minify_common(&$buffer) {
      // remove all comment blocks
      $buffer = preg_replace( '#/\*.*?\*/#s', '', $buffer );
      
      // remove all comment lines
      $buffer = preg_replace( '#//(.*)$#m', '', $buffer );
      
        $this->clearDblSpace($buffer);        
      // remove unecessary spaces (before|after) some signs ...
      $buffer = str_replace( array('{ ',' {'), '{', $buffer );
      $buffer = str_replace( array('} ',' }'), '}', $buffer );
      $buffer = str_replace( array('; ',' ;'), ';', $buffer );
      $buffer = str_replace( array(': ',' :'), ':', $buffer );
      $buffer = str_replace( array(', ',' ,'), ',', $buffer );
      $buffer = str_replace( array('! ',' !'), '!', $buffer );
      
      $buffer = $this->trimAllLines($buffer);
      
/*  Fonctionne pas correctement
       // remove all blank spaces
      $buffer = preg_replace( '#\s+#', ' ', $buffer );
     
      
*/      
      //return trim($buffer);
	} // minify_common
    
	/**
	 * trim de tues les lignes de content
	 * @param $string
	 * @return string
	 */
	private function trimAllLines($buffer) {
        $t = explode("\n", $buffer);
        for ($h = 0; $h < count($t); $h++)
            $t[$h] = trim($t[$h]);
		return implode("\n", $t);
	} // trimAllLines
    
	/**
	 * trim de tues les lignes de content
	 * @param $string
	 * @return string
	 */
	private function clearDblSpace(&$buffer) {
        $pos = strpos($buffer, '  ');
        while (!$pos === false){
            $buffer = str_replace('  ', ' ', $buffer);
            $pos = strpos($buffer, '  ');
        }
	} // clearDblSpace
    
	/**
	 * Check if string is HTML
	 * @param $string
	 * @return bool
	 */
	private function isHTML($string) {
		return preg_match('/<html.*>/', $string) ? true : false;
	} // isHTML

	/**
	 * Set zlib compression
	 */
	private function zlibCompression() {
		if (ini_get('zlib.output_compression')) {
			ini_set("zlib.output_compression", 1);
			ini_set("zlib.output_compression_level", "9");
		}
	}
}