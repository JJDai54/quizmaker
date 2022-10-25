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

define('_CO_QUIZMAKER_TYPE_PNN', "<br>Each option is associated with a null or negative number of positive points.");
define('_CO_QUIZMAKER_TYPE_PAC', "<br>Neither punctuation, accent nor case are taken into account to compare the result");

define('_CO_QUIZMAKER_TYPE_PAGEINFO', "Info Page");
define('_CO_QUIZMAKER_TYPE_PAGEINFO_DESC', "This slide has several features: Intro page, insert and results.<br>The page type is defined in the slide options.\"
. \"<br><b>Introduction</b>: to be placed imperatively first, it allows to present the quiz.\"
. \"<br><b>Insert</b>: placed anywhere it allows you to group the questions by theme. You must define the grouping insert in the questions.<br>The change in weight (order) results in all child questions.<br>It can also display intermediate results\"
. \"<br><b>Result</b>: Mandatory placed at the end, it allows to display the result of the quiz and to save it.");
define('_CO_QUIZMAKER_TYPE_PAGEINFO_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_CHECKBOXSIMPLE', "Multiple Answer Question");
define('_CO_QUIZMAKER_TYPE_CHECKBOXSIMPLE_DESC', "This slide is composed of a question and several checkboxes.");
define('_CO_QUIZMAKER_TYPE_CHECKBOXSIMPLE_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_CHECKBOXLOGICAL', "Multiple Answer Logic Question");
define('_CO_QUIZMAKER_TYPE_CHECKBOXLOGICAL_DESC', "This slide is made up of several options which have points in common.<br>You must check all the options which also have these points in common.");
define('_CO_QUIZMAKER_TYPE_CHECKBOXLOGICAL_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_LISTBOXINTRUDERS1', "Hunt Intruders");
define('_CO_QUIZMAKER_TYPE_LISTBOXINTRUDERS1_DESC', "This slide is made up of a list from which intruders must be removed.<br>No backtracking.");
define('_CO_QUIZMAKER_TYPE_LISTBOXINTRUDERS1_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_LISTBOXINTRUDERS2', "Move intruders (double list)");
define('_CO_QUIZMAKER_TYPE_LISTBOXINTRUDERS2_DESC', "This slide is made up of two lists.<br>The intruders must be discarded in the second list.<br>It is possible to correct and reintegrate discarded terms.");
define('_CO_QUIZMAKER_TYPE_LISTBOXINTRUDERS2_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_COMBOBOXMATCHITEMS', "Match items two by two");
define('_CO_QUIZMAKER_TYPE_COMBOBOXMATCHITEMS_DESC', "This slide is composed of a list of terms to which the terms of another list must be associated<br>Each term of the second list is a mixed list of the terms of the first list. ");
define('_CO_QUIZMAKER_TYPE_COMBOBOXMATCHITEMS_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_TEXTBOXMULTIPLE', "Multiple questions with multiple answers.");
define('_CO_QUIZMAKER_TYPE_TEXTBOXMULTIPLE_DESC', "This slide is composed of one or more questions.<br>Each question can have several enterable answers.");
define('_CO_QUIZMAKER_TYPE_TEXTBOXMULTIPLE_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_RADIOLOGICAL', "Single Answer Logic Question");
define('_CO_QUIZMAKER_TYPE_RADIOLOGICAL_DESC', "This slide is composed of two lists:<br>- a list of terms with common points<br>- a second list of which only one term has the same common points");
define('_CO_QUIZMAKER_TYPE_RADIOLOGICAL_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_RADIOMULTIPLE2', "Find terms that go together");
define('_CO_QUIZMAKER_TYPE_RADIOMULTIPLE2_DESC', "This slide is composed of list folders with an identical number of terms.<br>You have to find the terms that go together.<br>Several solutions can be proposed with a different number of points.") ;
define('_CO_QUIZMAKER_TYPE_RADIOMULTIPLE2_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_RADIOSIMPLE', "Single answer question");
define('_CO_QUIZMAKER_TYPE_RADIOSIMPLE_DESC', "This slide is composed of several answers from which only one can be chosen.");
define('_CO_QUIZMAKER_TYPE_RADIOSIMPLE_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_COMBOBOXSORTLIST', "Sort multiple list");
define('_CO_QUIZMAKER_TYPE_COMBOBOXSORTLIST_DESC', "This slide is made up of several lists made up of the same terms.<br>You must indicate the specific order in the question<br>The order can be reversed if the option has been defined." );
define('_CO_QUIZMAKER_TYPE_COMBOBOXSORTLIST_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_TEXTAREASIMPLE', "Correct text");
define('_CO_QUIZMAKER_TYPE_TEXTAREASIMPLE_DESC', "This slide is composed of a text area that must be corrected directly.");
define('_CO_QUIZMAKER_TYPE_TEXTAREASIMPLE_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_TEXTAREAINPUT', "Fill in missing words");
define('_CO_QUIZMAKER_TYPE_TEXTAREAINPUT_DESC', "This slide is composed of a non-enterable text area and several enterable areas.<br>You must enter the words to replace in the text.<br>The terms in the text have been replaced by numbers in braces: {1} {2} {3} ... ");
define('_CO_QUIZMAKER_TYPE_TEXTAREAINPUT_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_TEXTAREALISTBOX', "Find the missing words");
define('_CO_QUIZMAKER_TYPE_TEXTAREALISTBOX_DESC', "This slide is composed of a text and several lists of words.<br>The missing words have been replaced by numbers between braces: {1} {2} {3} ... < br>Each list is made up of the words to find.<br>It is possible to add strange words to the text");
define('_CO_QUIZMAKER_TYPE_TEXTAREALISTBOX_EXAMPLE', "");

define('_CO_QUIZMAKER_TYPE_LISTBOXSORTITEMS', "Sort a list of terms");
define('_CO_QUIZMAKER_TYPE_LISTBOXSORTITEMS_DESC', "This slide is made up of a list and move buttons.<br>The sorting can be reversed.");
define('_CO_QUIZMAKER_TYPE_LISTBOXSORTITEMS_EXAMPLE', "");


?>