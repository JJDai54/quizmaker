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
define('_LG_PLUGIN_SELECTIMAGES', "Click the correct images");
define('_LG_PLUGIN_SELECTIMAGES_DESC', "This slide is composed of a sequence of images, one or more of which may be correct, like radio buttons or checkboxes.");
define('_LG_PLUGIN_SELECTIMAGES_CONSIGNE', "Instructions to complete");

define('_LG_PLUGIN_SELECTIMAGES_TYPE', "Selection type");
define('_LG_PLUGIN_SELECTIMAGES_TYPE_DESC', "");
define('_LG_PLUGIN_SELECTIMAGES_TYPE_0', "Multiple choice");
define('_LG_PLUGIN_SELECTIMAGES_TYPE_1', "Single choice");
define('_LG_PLUGIN_SELECTIMAGES_TYPE_2', "Single choice and advance to the next slide");

define('_LG_PLUGIN_SELECTIMAGES_IMG_HEIGHT', "Height of the images in the suggestions");
define('_LG_PLUGIN_SELECTIMAGES_COCHE', "Check mark");
define('_LG_PLUGIN_SELECTIMAGES_COCHE_IMG_HEIGHT', "Height of the check marks");
define('_LG_PLUGIN_SELECTIMAGES_IMG_TOP', "Label height position");
define('_LG_PLUGIN_SELECTIMAGES_IMG_TOP_DESC', "Up < 50% > Down");
define('_LG_PLUGIN_SELECTIMAGES_FONT_SIZE', "Font size (in em, default=1.1)");

define('_LG_PLUGIN_SELECTIMAGES_FONT_SIZE_DESC', "Font size");

define('_LG_PLUGIN_SELECTIMAGES_DISPOSITION', "Layout");

define('_LG_PLUGIN_SELECTIMAGES_DISPOSITION_DESC', "Each number corresponds to the number of buttons on each row.<br>Example: 123 = 1 button on the first row, 2 buttons on the second row, and 3 buttons on the third row.<br>If there are more buttons than the sum of the numbers, the last buttons will all be on the last row.");

define('_LG_PLUGIN_SELECTIMAGES_MSG_NEXT_SLIDE', "Message");
define('_LG_PLUGIN_SELECTIMAGES_MSG_NEXT_SLIDE_DESC', "Message displayed if this is a single-choice question with a slide advancement.");
define('_LG_PLUGIN_SELECTIMAGES_MSGBG', "Message background color.");

define('_LG_PLUGIN_SELECTIMAGES_NEXT_QUESTION1', "Next question");

define('_LG_PLUGIN_SELECTIMAGES_NEXT_QUESTION1_OPTIONS', _LG_PLUGIN_SELECTIMAGES_NEXT_QUESTION1 . ",Moving on,Persevere,Cumulative score: {score} / {Maximum score}");

define('_LG_PLUGIN_SELECTIMAGES_NEXT_SLIDE', "Message");
define('_LG_PLUGIN_SELECTIMAGES_NEXT_SLIDE_DESC', "Message displayed when moving to the next slide in automatic mode");
define('_LG_PLUGIN_SELECTIMAGES_NEXT_SLIDE0', "Well done!");

?>
