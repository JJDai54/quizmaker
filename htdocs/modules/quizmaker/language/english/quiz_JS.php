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
 * @Module : Xoops FAQ
 * @subpackage : Menu Language
 * @since 2.5.7
 * @author John Neill
 * @version $Id: modinfo.php 0000 10/04/2009 09:08:46 John Neill $
 * Traduction: LionHell 
 * upgrade to xoops 2.5.10 by Jean-Jacques DELALANDRE
 */
 
defined( 'XOOPS_ROOT_PATH' ) or die( 'Accès restreint' );

define('_JS_QUIZMAKER_MESSAGE01', "Be careful to read the instruction carefully, and the sorting direction because only the exact order gives points.");
define('_JS_QUIZMAKER_RESULTS', "Results");
define('_JS_QUIZMAKER_SORTCOMBOBOX', "Pay attention to the order.");
define('_JS_QUIZMAKER_RADIO', "Attention, several answers are possible but the points differ according to the answer. The least obvious have the highest number of points");
define('_JS_QUIZMAKER_CHECKBOX', "Each answer is associated with a number of points depending on the difficulty. The answers are cumulative");
define('_JS_QUIZMAKER_TEXTBOX', "Attention, several answers are possible but the points differ according to the answer. The least obvious have the highest number of points");
define('_JS_QUIZMAKER_ALLTYPE', "Important: In some quizzes there may be answers with a number of negative points.");
define('_JS_QUIZMAKER_FORPOINTS', "For {pointsMin} to {pointsMax} points");
define('_JS_QUIZMAKER_FORCHRONO', " in {timer} seconds");
define('_JS_QUIZMAKER_FORPOINTSTIMER', "For {points} points in {timer} seconds");
define('_JS_QUIZMAKER_RESULTONSLIDE', "result: for {answers} answers on {questions} questions,<br>Your score is {points} points out of {totalPoints} points");
define('_JS_QUIZMAKER_BTNNEXT', "Next");
define('_JS_QUIZMAKER_BTNPREVIOUS', "Previous");
define('_JS_QUIZMAKER_BTNSUBMIT', "Solutions");
define('_JS_QUIZMAKER_BTNCONTINUE', "Continue");
define('_JS_QUIZMAKER_BTNRELOAD', "Reload");
define('_JS_QUIZMAKER_BTNANTISECHE', "?");
define('_JS_QUIZMAKER_SHOWREPONSES', "Display for the dev of the possible answers (answer ===> points)");
define('_JS_QUIZMAKER_BTNRELOADLIST', "Reload List");
define('_JS_QUIZMAKER_BTNRELOADTEXT', "Reload text");
define('_JS_QUIZMAKER_RESULTBRAVO0', "Your result for this question:");
define('_JS_QUIZMAKER_RESULTBRAVO1', "You got the maximum score");
define('_JS_QUIZMAKER_RESULTBRAVO2', "You did not get the maximum score");
define('_JS_QUIZMAKER_RESULTBRAVO3', "You got the minimum score");
define('_JS_QUIZMAKER_RESULTSCORE', "Your score is {points} / {totalPoints} points");
define('_JS_QUIZMAKER_POINTS', "point(s)");
define('_JS_QUIZMAKER_BONNEREPONSE', "The correct answer for {points} point(s) is:");
define('_JS_QUIZMAKER_TPLWORD', "<span style='color: red;'>{word}</span> ");
define('_JS_QUIZMAKER_TPLWORD2', "<span style='color: white;'>{word}</span> ");
define('_JS_QUIZMAKER_TPLREPONSETABLE', "<table>{content}</table>");
define('_JS_QUIZMAKER_TPLREPONSEDBLTABLE', "<table><tr>{content}</tr></table>");
define('_JS_QUIZMAKER_TPLREPONSEDBLTD', "<td>{content}</td>");
define('_JS_QUIZMAKER_TPLREPONSETD', "<tr><td>{word}</td><td>{sep}</td><td>{points}</td></tr>");
define('_JS_QUIZMAKER_MULTITEXTBOX', "Multiple text");
?>