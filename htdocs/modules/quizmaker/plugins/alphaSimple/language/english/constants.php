<?php
   /**
 * Name: modinfo.php
 * Description:
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package : XOOPS
 * @Module : 
 * @subpackage : Menu Language
 * @since 2.5.7
 * @author Jean-Jacques DELALANDRE (jjdelalandre@orange.fr)
 * @version {version}
 * Traduction:  
 */
 
defined( 'XOOPS_ROOT_PATH' ) or die( 'Accès restreint' );

//------------------------------------------------------------------
define('_LG_PLUGIN_ALPHASIMPLE', "Letter Question");
define('_LG_PLUGIN_ALPHASIMPLE_DESC', "This slide consists of a question and several alphanumeric characters or expressions to select.");

define('_LG_PLUGIN_ALPHASIMPLE_CONSIGNE', "Select one or more alphanumeric characters or expressions that match the question.");
define('_LG_PLUGIN_ALPHASIMPLE_INTRUS', "Incorrect expressions");

define('_LG_PLUGIN_ALPHASIMPLE_LETTERS_DESC', <<<__exp__
List of characters or expressions that supplement the suggestions to facilitate entry.
<br>This list must be separated by one of the following characters: \",-|\"</b>
<br>The \"X#@\" buttons allow automatic completion with predefined lists.
<br>The items in this list are worth 0 points.
<br>When this list is used, it is not necessary to enter them in the list of suggestions.
__exp__);

define('_LG_PLUGIN_ALPHASIMPLE_DIRECTIVE', "Directive");
define('_LG_PLUGIN_ALPHASIMPLE_DIRECTIVE_DESC', "Action to be performed displayed above the letters.");
define('_LG_PLUGIN_ALPHASIMPLE_DIRECTIVE_LIB', "Click on one or more letters or numbers.");
define('_LG_PLUGIN_ALPHASIMPLE_IGNORE_ACCENTS', "Ignore accents.");

define('_LG_PLUGIN_ALPHASIMPLE_NEXT_SLIDE', "Message");

define('_LG_PLUGIN_ALPHASIMPLE_NEXT_SLIDE_DESC', "Message displayed when moving to the next slide in automatic mode");

define('_LG_PLUGIN_ALPHASIMPLE_NEXT_SLIDE0', "Well done!");

?>
