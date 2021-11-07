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

define('_MA_QUIZMAKER_INDEX', "Home");
define('_MA_QUIZMAKER_TITLE', "QuizMaker");
define('_MA_QUIZMAKER_DESC', "Ce module permet de créer des quiz et des QCM");
define('_MA_QUIZMAKER_INDEX_DESC', "Welcome to the homepage of your new module QuizMaker!<br>

As you can see, you have created a page with a list of links at the top to navigate between the pages of your module. This description is only visible on the homepage of this module, the other pages you will see the content you created when you built this module with the module ModuleBuilder, and after creating new content in admin of this module. In order to expand this module with other resources, just add the code you need to extend the functionality of the same. The files are grouped by type, from the header to the footer to see how divided the source code.<br><br>If you see this message, it is because you have not created content for this module. Once you have created any type of content, you will not see this message.<br><br>If you liked the module ModuleBuilder and thanks to the long process for giving the opportunity to the new module to be created in a moment, consider making a donation to keep the module ModuleBuilder and make a donation using this button <a href='http://www.txmodxoops.org/modules/xdonations/index.php' title='Donation To Txmod Xoops'><img src='https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif' alt='Button Donations' /></a><br>Thanks!<br><br>Use the link below to go to the admin and create content.");
define('_MA_QUIZMAKER_NO_PDF_LIBRARY', "Libraries TCPDF not there yet, upload them in root/Frameworks");
define('_MA_QUIZMAKER_NO', "No");
define('_MA_QUIZMAKER_DETAILS', "Show details");
define('_MA_QUIZMAKER_BROKEN', "Notify broken");
define('_MA_QUIZMAKER_QUIZ', "Quiz");
define('_MA_QUIZMAKER_QUIZ_TITLE', "Quiz title");
define('_MA_QUIZMAKER_QUIZ_DESC', "Quiz description");
define('_MA_QUIZMAKER_QUIZ_LIST', "List of Quiz");
define('_MA_QUIZMAKER_QUIZ_ID', "Id");
define('_MA_QUIZMAKER_QUIZ_CAT_ID', "Cat_id");
define('_MA_QUIZMAKER_QUIZ_NAME', "Name");
define('_MA_QUIZMAKER_QUIZ_DESCRIPTION', "Description");
define('_MA_QUIZMAKER_QUIZ_CREATION', "Creation");
define('_MA_QUIZMAKER_QUIZ_UPDATE', "Update");
define('_MA_QUIZMAKER_QUIZ_COMMENTS', "Comments");
define('_MA_QUIZMAKER_QUIZ_DATEBEGIN', "DateBegin");
define('_MA_QUIZMAKER_QUIZ_DATEEND', "DateEnd");
define('_MA_QUIZMAKER_QUIZ_ONCLICK', "OnClick");
define('_MA_QUIZMAKER_QUIZ_THEME', "Thème");
define('_MA_QUIZMAKER_QUIZ_ANSWERBEFORENEXT', "AnswerBeforeNext");
define('_MA_QUIZMAKER_QUIZ_ALLOWEDPREVIOUS', "AllowedPrevious");
define('_MA_QUIZMAKER_QUIZ_ALLOWEDSUBMIT', "AllowedSubmit");
define('_MA_QUIZMAKER_QUIZ_TIMER', "Timer");
define('_MA_QUIZMAKER_QUIZ_SHOWRESULTALLWAYS', "ShowResultAllways");
define('_MA_QUIZMAKER_QUIZ_SHOWREPONSES', "ShowReponses");
define('_MA_QUIZMAKER_QUIZ_SHOWLOG', "ShowLog");
define('_MA_QUIZMAKER_QUIZ_LEGEND', "Legend");
define('_MA_QUIZMAKER_QUIZ_DATEBEGINOK', "DateBeginOk");
define('_MA_QUIZMAKER_QUIZ_DATEENDOK', "DateEndOk");
define('_MA_QUIZMAKER_QUESTIONS', "Questions");
define('_MA_QUIZMAKER_QUESTIONS_TITLE', "Questions title");
define('_MA_QUIZMAKER_QUESTIONS_DESC', "Questions description");
define('_MA_QUIZMAKER_QUESTIONS_LIST', "List of Questions");
define('_MA_QUIZMAKER_QUESTIONS_ID', "Id");
define('_MA_QUIZMAKER_QUESTIONS_QUIZ_ID', "Quiz_id");
define('_MA_QUIZMAKER_QUESTIONS_QUESTION', "Question");
define('_MA_QUIZMAKER_QUESTIONS_COMMENT1', "Comment1");
define('_MA_QUIZMAKER_QUESTIONS_TYPE_QUESTION', "Type_question");
define('_MA_QUIZMAKER_QUESTIONS_MINREPONSE', "MinReponse");
define('_MA_QUIZMAKER_QUESTIONS_CREATION', "Creation");
define('_MA_QUIZMAKER_QUESTIONS_UPDATE', "Update");
define('_MA_QUIZMAKER_QUESTIONS_COMMENT2', "Comment2");
define('_MA_QUIZMAKER_CATEGORIES', "Categories");
define('_MA_QUIZMAKER_CATEGORIES_TITLE', "Categories title");
define('_MA_QUIZMAKER_CATEGORIES_DESC', "Categories description");
define('_MA_QUIZMAKER_CATEGORIES_LIST', "List of Categories");
define('_MA_QUIZMAKER_CATEGORIES_ID', "Id");
define('_MA_QUIZMAKER_CATEGORIES_NAME', "Name");
define('_MA_QUIZMAKER_CATEGORIES_DESCRIPTION', "Description");
define('_MA_QUIZMAKER_CATEGORIES_CREATION', "Creation");
define('_MA_QUIZMAKER_CATEGORIES_UPDATE', "Update");
define('_MA_QUIZMAKER_TYPEDEQUESTION', "Typedequestion");
define('_MA_QUIZMAKER_TYPE_QUESTION', "Type_question");
define('_MA_QUIZMAKER_TYPE_QUESTION_TITLE', "Type_question title");
define('_MA_QUIZMAKER_TYPE_QUESTION_DESC', "Type_question description");
define('_MA_QUIZMAKER_TYPE_QUESTION_LIST', "List of Type_question");
define('_MA_QUIZMAKER_TYPEDEQUESTION_ID', "Id");
define('_MA_QUIZMAKER_TYPEDEQUESTION_NAME', "Name");
define('_MA_QUIZMAKER_TYPEDEQUESTION_DESCRIPTION', "Description");
define('_MA_QUIZMAKER_TYPEDEQUESTION_SHORTDESC', "Shortdesc");
define('_MA_QUIZMAKER_ANSWERS', "Answers");
define('_MA_QUIZMAKER_ANSWERS_TITLE', "Answers title");
define('_MA_QUIZMAKER_ANSWERS_DESC', "Answers description");
define('_MA_QUIZMAKER_ANSWERS_LIST', "List of Answers");
define('_MA_QUIZMAKER_ANSWERS_ID', "Id");
define('_MA_QUIZMAKER_ANSWERS_QUESTION_ID', "Question_id");
define('_MA_QUIZMAKER_ANSWERS_LETTER', "Letter");
define('_MA_QUIZMAKER_ANSWERS_PROPOSITION', "Proposition");
define('_MA_QUIZMAKER_ANSWERS_POINTS', "Points");
define('_MA_QUIZMAKER_RESULTS', "Results");
define('_MA_QUIZMAKER_RESULTS_TITLE', "Results title");
define('_MA_QUIZMAKER_RESULTS_DESC', "Results description");
define('_MA_QUIZMAKER_RESULTS_LIST', "List of Results");
define('_MA_QUIZMAKER_RESULTS_ID', "Id");
define('_MA_QUIZMAKER_RESULTS_QUIZ_ID', "Quiz_id");
define('_MA_QUIZMAKER_RESULTS_UID', "Uid");
define('_MA_QUIZMAKER_RESULTS_SCORE', "Score");
define('_MA_QUIZMAKER_RESULTS_SOREMAX', "Soremax");
define('_MA_QUIZMAKER_RESULTS_NBANSWERS', "Nb answers");
define('_MA_QUIZMAKER_RESULTS_DURATION', "Duration");
define('_MA_QUIZMAKER_RESULTS_NOTE', "Note");
define('_MA_QUIZMAKER_RESULTS_CREATION', "Creation");
define('_MA_QUIZMAKER_MESSAGES', "Messages");
define('_MA_QUIZMAKER_MESSAGES_TITLE', "Messages title");
define('_MA_QUIZMAKER_MESSAGES_DESC', "Messages description");
define('_MA_QUIZMAKER_MESSAGES_LIST', "List of Messages");
define('_MA_QUIZMAKER_MESSAGES_ID', "Id");
define('_MA_QUIZMAKER_MESSAGES_CODE', "Code");
define('_MA_QUIZMAKER_MESSAGES_MESSAGE', "Message");
define('_MA_QUIZMAKER_INDEX_THEREARE', "There are %s Messages");
define('_MA_QUIZMAKER_INDEX_LATEST_LIST', "Last QuizMaker");
define('_MA_QUIZMAKER_SUBMIT', "Submit");
define('_MA_QUIZMAKER_SUBMIT_MESSAGES', "Submit Messages");
define('_MA_QUIZMAKER_SUBMIT_ALLPENDING', "All Messages/script information are posted pending verification.");
define('_MA_QUIZMAKER_SUBMIT_DONTABUSE', "Username and IP are recorded, so please do not abuse the system.");
define('_MA_QUIZMAKER_SUBMIT_ISAPPROVED', "Your Messages has been approved");
define('_MA_QUIZMAKER_SUBMIT_PROPOSER', "Submit a Messages");
define('_MA_QUIZMAKER_SUBMIT_RECEIVED', "We have received your Messages info. Thank you !");
define('_MA_QUIZMAKER_SUBMIT_SUBMITONCE', "Submit your Messages/script only once.");
define('_MA_QUIZMAKER_SUBMIT_TAKEDAYS', "This will take many days to see your Messages/script added successfully in our database.");
define('_MA_QUIZMAKER_FORM_OK', "Successfully saved");
define('_MA_QUIZMAKER_FORM_DELETE_OK', "Successfully deleted");
define('_MA_QUIZMAKER_FORM_SURE_DELETE', "Are you sure to delete: <b><span style='color : Red;'>%s </span></b>");
define('_MA_QUIZMAKER_FORM_SURE_RENEW', "Are you sure to update: <b><span style='color : Red;'>%s </span></b>");
define('_MA_QUIZMAKER_INVALID_PARAM', "Invalid parameter");
define('_MA_QUIZMAKER_ADMIN', "Admin");
define('_MA_QUIZMAKER_RUN_QUIZ', "Lancer le quiz");
define('_MA_QUIZMAKER_UID', "UID");
define('_MA_QUIZMAKER_SCORE_ACHIVED', "Score");
define('_MA_QUIZMAKER_SCORE_MAX', "Score max");
define('_MA_QUIZMAKER_DURATION', "Durée");
define('_MA_QUIZMAKER_DATE', "Date");
define('_MA_QUIZMAKER_SCORE', "Score");
define('_MA_QUIZMAKER_UNAME', "Pseudo");
define('_MA_QUIZMAKER_STILL_ANSWER', "Vous avez déjà participé à ce quiz.<br>Veuillez réessayer plus tard.");
define('_MA_QUIZMAKER_NOTE', "Note");
define('_MA_QUIZMAKER_SELECTION', "Sélection");
define('_MA_QUIZMAKER_NO_RESULTS', "Il n'y a pas encore de résultats pour ce quiz");
define('_MA_QUIZMAKER_RESULT_5', "Expert");
define('_MA_QUIZMAKER_RESULT_4', "Connaisseur");
define('_MA_QUIZMAKER_RESULT_3', "Quelques progrès à faire");
define('_MA_QUIZMAKER_RESULT_2', "Moyen");
define('_MA_QUIZMAKER_RESULT_1', "peut mieux faire");
define('_MA_QUIZMAKER_RESULT_0', "Nullos");
define('_MA_QUIZMAKER_', "");
define('_MA_QUIZMAKER_CATEGORIES_THEME', "Theme");

?>