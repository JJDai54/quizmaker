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
define('_LG_PLUGIN_FINDOBJECTS', "Find the hidden objects");
define('_LG_PLUGIN_FINDOBJECTS_DESC', "This slide is composed of one or two images. You must find the hidden or different objects.");
define('_LG_PLUGIN_FINDOBJECTS_CONSIGNE', "Instructions to complete");

define('_LG_PLUGIN_FINDOBJECTS_WIDTH', "Image width %s");
define('_LG_PLUGIN_FINDOBJECTS_MAXTRY', "Maximum number of tries");
define('_LG_PLUGIN_FINDOBJECTS_MAXTOUCHES', "Maximum number of touches");

define('_LG_FINDOBJECTS_NB_OBJETS', "Number of objects");
define('_LG_FINDOBJECTS_DELETE', "X");
define('_LG_FINDOBJECTS_TITLE', "Title");
define('_LG_FINDOBJECTS_COLOR', "Color");
define('_LG_FINDOBJECTS_LEFT', "Left");
define('_LG_FINDOBJECTS_TOP', "Top");
define('_LG_FINDOBJECTS_WIDTH', "Width");
define('_LG_FINDOBJECTS_HEIGHT', "Height");
define('_LG_FINDOBJECTS_BORDER_WIDTH', "Thickness");
define('_LG_FINDOBJECTS_BORDER_RADIUS', "Rounding");

define('_LG_FINDOBJECTS_TOUCHES_PARAMS', "Keyboard parameters (Validate and reload the form to apply these settings)");
define('_LG_FINDOBJECTS_NEXT_SLIDE_PARAMS', "Automatic message parameters");
define('_LG_FINDOBJECTS_POINTS', "Points");
define('_LG_FINDOBJECTS_TEST_ClICK_OBJECTS', "Sensitive Area Test");
define('_LG_FINDOBJECTS_DISPOSITION_DESC', "The first image is mandatory for hidden objects, for example.<br>The second optional image will be useful for spot the difference games, for example.<br>If the second image is not defined, the first will be automatically loaded as the second image but will not be used in the quiz.<br>It will be useful for testing sensitive areas.");
define('_LG_FINDOBJECTS_IMAGES_ATTEMPTS_MAX_DESC', "The number of attempts is limited to prevent cheating by clicking dozens of times randomly.<br>It is important to set this value, which should be greater than or equal to the number of touches.<br>Although possible, it is not recommended to set a value lower than the number of touches.<br>0 = Unlimited number of attempts.");
define('_LG_FINDOBJECTS_REFRESH_IMG_SIZES', "Apply the reference image size");

define('_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_DELAI', "Delay before displaying the next slide");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_DELAI_DESC', "<b>Delay = 0</b>: The user must press the next button themselves. <br><b>Delay > 0</b>: The slide will move automatically in two cases: the user has failed, or the user has exhausted the number of possible tests.");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_MESSAGE', "Message");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_MESSAGE_DESC', "Message displayed when moving to the next slide. Used if the delay is zero");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_BG', "Message background color");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDET_MESSAGE_DEFAULT', "Next question!");

define('_LG_PLUGIN_FINDSOBJECTS_NEXTSLIDE_AUTO', "Automatic next slide");
define('_LG_PLUGIN_FINDSOBJECTS_NEXTSLIDE_AUTO_DESC', "<b>No</b>: The user must click the next button.<br><b>Yes</b>: The next slide will be automatically advanced in two cases:<br>1) The number of attempts has been reached,<br>2) All objects have been found.");

define('_LG_PLUGIN_FINDOBJECTS_INFO', "You have made {total} attempts on {max}//including {winning} objects found on {length}!");

define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN', "Message 1");

define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN_DESC', "Message displayed if the user has found all objects.");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN0', "You found {winning} / {length} objects//Next question!");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN1', "You found {winning} / {length} objects//Let's move on");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN2', "You made {total}/{max} attempts, including {winning} objects found out of {length}//Next question");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN3', "Cumulative score: {score} / {Maximumscore}");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN4', "Your cumulative score is {score}/{MaximumScore}//Next Question");

define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX', "Message 2");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX_DESC', "Message displayed if the user has reached the maximum number of attempts.");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX0', "You have reached the maximum number of attempts {max}//Next Question!");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX1', "You have reached the maximum number of attempts {max} including {winning} / {length} of correct objects//Next question!");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX2', "You have made {total}/{max} attempts including {winning} objects found on {length}//Next question!");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX3', "Cumulative score: {score} / {Maximumscore}");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX4', "

?>
