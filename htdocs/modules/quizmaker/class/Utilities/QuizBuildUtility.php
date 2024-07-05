<?php

namespace XoopsModules\Quizmaker\Utilities;

/*
 Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Module:  quizmaker
 *
 * @package      \module\quizmaker\class
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       ZySpec <owners@zyspec.com>
 * @author       Mamba <mambax7@gmail.com>
 * @since        
 */

use XoopsModules\Quizmaker AS FQUIZMAKER;
use Xmf\Request;
use JJD;
//include_once XOOPS_ROOT_PATH . "/modules/quizmaker/class/Utility.php";
                            
//$utility = new \XoopsModules\Quizmaker\Utility();

/**
 * Class Utility
 */
trait QuizBuildUtility
{
// =========================================================
// ============ Fonctions de generation des quiz ===========
// =========================================================
/* ************************************************
*
* ************************************************* */
public static function buildQuiz($quizId){
global $quizHandler, $questionsHandler, $answersHandler;
///quiz-questions.js
    
    //Au cas ou cela aurait été oublié
    $questionsHandler->incrementeWeight($quizId);
    
    // --- Dossier de destination
    $quiz = $quizHandler->get($quizId);
//echo "<hr>quiz<pre>" . print_r($quiz, true) . "</pre><hr>";
    $name = $quiz->getVar('quiz_folderJS');   
    $path = QUIZMAKER_PATH_UPLOAD_QUIZ . "/{$name}";
    self::create_quiz_arborescense($path);     
    
    
    // --- copies du dossier des images si il n'existe pas---
    //copie des images utilisées notamment pour pour les liste a trier
    self::copie_ressources_images(QUIZMAKER_PATH_UPLOAD_QUIZ . '/images');
    \JJD\FSO\setChmodRecursif(QUIZMAKER_PATH_UPLOAD_QUIZ . '/images', 0777);   
     
    // --- Génération du fichier d'option ---
    self::export_options2Jason($quiz, $path);
    
    self::export_consignes($path);    
        
    // --- Génération du fichier de questions ---
    self::export_questions2Jason($quizId, $path, $typesQuestionsArr);

//    self::export_classLoader($typesQuestionsArr, $path);
    
    
    // --- Génération du fichier d'HTML ---
    self::build_quizinline($quiz, QUIZMAKER_PATH_UPLOAD_QUIZ, $name);
    

    // incrementer la version => quiz_build   
    $quiz->setVar('quiz_build', $quiz->getVar('quiz_build') + 1);
    $quizHandler->insert($quiz);
}


/* ************************************************
*
* ************************************************* */
static function copie_ressources_images($pathDest){
    //if (is_dir($pathDest . "images")) return true;
    $pathSource = QUIZMAKER_PATH_QUIZ_JS . "/images";
//             xoops_load('XoopsFile');
//         $folderHandler   = XoopsFile::getHandler('folder');
//             
//     $folderHandler->copy($pathSource,$pathDest);
self:: copyFolder ($pathSource,$pathDest) ;
    return true;
}

/* ************************************************
*
* ************************************************* */
public static function build_quizinline($quiz, $path, $name){
global $utility, $xoopsConfig;    

    include_once XOOPS_ROOT_PATH.'/class/template.php';
    $tpl = new \xoopsTpl();
    $rootApp = QUIZMAKER_PATH_QUIZ_JS ;
    $urlApp  = QUIZMAKER_URL_QUIZ_JS  ;
    //----------------------------------------------
    //insertion des CSS
    $tCss = \JJD\FSO\getFilePrefixedBy($rootApp.'/css', array('css'), '', false, false,false);
//echo "<hr><pre>CSS : " . print_r($tCss, true) . "</pre><hr>";
    $urlCss = QUIZMAKER_URL_QUIZ_JS. "/css";
    $tpl->assign('urlCss', $urlCss);
    $tpl->assign('allCss', $tCss);
    
    //----------------------------------------------
    //insertion du prototype des tpl
    $urlApp = QUIZMAKER_URL_QUIZ_JS. "";    
    $urlPlugins = QUIZMAKER_URL_QUIZ_JS.QUIZMAKER_FLD_PLUGINS_JS;    
    $tpl->assign('prototype', 'slide__prototype.js');
    
    
    //----------------------------------------------
    $allPlugins = \JJD\FSO\getFolder2 ($rootApp.QUIZMAKER_FLD_PLUGINS_JS, false);
    $tpl->assign('allPlugins', $allPlugins);
    //echoArray($allPlugins, 'plugins', false);

    //----------------------------------------------
    //insertion du fichier de langue
    $language = $xoopsConfig['language'];
    $langFile = $rootApp . QUIZMAKER_FLD_LANGUAGE_JS . "/quiz-" . $language . ".js";
    if (!file_exists($langFile)) { //JJDai : peut-etre forcer overwrite
        //self::buildJsLanguage($langFile);
        //$language = english;
        $messagesHandler->buildJsLanguage($language);
    }
    $tpl->assign('urlApp', $urlApp);
    $tpl->assign('urlPlugins', $urlPlugins);
    $tpl->assign('language', $language);
    

    //----------------------------------------------
    $quizUrl = QUIZMAKER_URL_UPLOAD_QUIZ . "/{$name}";
    $tpl->assign('quizUrl', $quizUrl);
    $tpl->assign('questions', 'quiz-questions');
    $tpl->assign('options', 'quiz-options');
 

    $tpl->assign('quiz_functions', 'quiz-functions');
    $tpl->assign('quiz_events', 'quiz-events');
    $tpl->assign('quiz_main', 'quiz-main');

    //-------------------------------------------------
    $tpl->assign('outline', true);
    $tpl->assign('quiz_execution', 2);
    $content = $tpl->fetch('db:quizmaker_admin_quiz_inline.tpl' );    
    $fileName =  "{$path}/{$name}/index.html";
    \JJD\FSO\saveTexte2File($fileName, $content);   
    
    //-------------------------------------------------
    $tpl->assign('outline', false);
    $tpl->assign('quiz_execution', 1);
    $content = $tpl->fetch('db:quizmaker_admin_quiz_inline.tpl' );    
    $fileName =  "{$path}/{$name}/index.tpl";
    \JJD\FSO\saveTexte2File($fileName, $content);   
     
}

/* ************************************************
*
* ************************************************* */
public static function export_options2Jason($quiz, $path){
global $categoriesHandler, $quizHandler, $questionsHandler, $answersHandler, $utility;
    $quizValues = $quiz->getValuesQuiz();
    
    if($quizValues['theme'] == ''){
        $theme = $categoriesHandler->getValue($quizValues['cat_id'], 'cat_theme', 'default');
    }else{
        $theme = $quizValues['theme'];
    }
    
    $optionsArr = array();

    $optionsArr['quizId']                   = $quizValues['id'];
    $optionsArr['name']                     = $quizValues['name']; 
    $optionsArr['description']              = $quizValues['description']; 
    $optionsArr['legend']                   = $quizValues['legend'];//"{allType}"
    $optionsArr['theme']                    = $theme;
    $optionsArr['libBegin']                 = $quizValues['libBegin'];
    $optionsArr['libEnd']                   = $quizValues['libEnd'];
    $optionsArr['onClickSimple']            = $quizValues['onClickSimple'];
    $optionsArr['questPosComment1']         = $quizValues['questPosComment1'];
    $optionsArr['showConsigne']             = $quizValues['showConsigne'];
    //$optionsArr['minusOnShowGoodAnswers']   = $quizValues['minusOnShowGoodAnswers'];
    $optionsArr['build']                    = $quizValues['quiz_build'];
    $optionsArr['folderJS']                 = $quizValues['folderJS'];
    $optionsArr['url']                      = QUIZMAKER_URL_UPLOAD_QUIZ;
    $optionsArr['urlMain']                  = QUIZMAKER_URL_QUIZ_JS;
    $optionsArr['execution']                = $quizValues['actif'];
          
    //options d'interface
    $optionsArr['optionsIhm']               = $quizValues['optionsIhm'];

    //options de developpement
    $optionsArr['optionsDev']               = $quizValues['optionsDev'];
    
    $optionsArr['mode']                     = 0;

      

    //---------------------------------------------------------
//echo "<hr><pre>optionsArr : " . print_r($optionsArr, true) . "</pre><hr>";
//echo json_encode($optionsArr);
 
    $exp ="var quiz = JSON.parse(`" .  json_encode($optionsArr) . "`);";
    $exp = str_replace('"', '\"', $exp);
//     $exp = str_replace("\n", '<br>', $exp);
    
    $br = '<br>' ;     //
    $exp = str_replace('\r\n', $br, $exp);
    $exp = str_replace('\n', $br, $exp);
    $exp = str_replace('\r', $br, $exp);
    
    $exp = FQUIZMAKER\quizmaker_utf8_encode($exp);
    $fileName = $path . "/js/quiz-options.js";
    
//    echo "Export ===>{$path}<br>";
//    echo "{$exp}";
    \JJD\FSO\saveTexte2File($fileName, $exp);

}
/* ************************************************
*
* ************************************************* */
public static function export_questions2Jason($quizId, $path, &$typesQuestionsArr){
global $quizHandler, $questionsHandler, $answersHandler, $utility,$type_questionHandler;
    
    $questionsArr = array();
    $typesQuestionsArr = array();
    
    $criteria = new \CriteriaCompo(new \Criteria('quest_quiz_id', $quizId, '='));
    //double emploi avec actif, champ inutile a virer
    $criteria->add(new \Criteria('quest_actif', 1, '='));
    $criteria->setsort("quest_weight");
    $criteria->setOrder("ASC");
    $questions = $questionsHandler->getObjects($criteria);
//    echoArray($questions,'',true);
    foreach (array_keys($questions) as $i) {
        $values=$questions[$i]->getValuesQuestions();
        $clTypeQuestion = $type_questionHandler->getTypeQuestion($values['type_question']);
        $typesQuestionsArr[] = $values['type_question'];  
              
        $tQuest = array();
        $tQuest['quizId']         = $quizId;
        $tQuest['questId']        = $values['quest_id'];
        $tQuest['parentId']       = $values['quest_parent_id'];
        $tQuest['type']           = $values['type_question'];
        $tQuest['typeQuestion']   = $values['type_question'];
        $tQuest['typeForm']       = $values['typeForm'];
        $tQuest['question']       = self::sanitise($values['quest_question']);
        $tQuest['identifiant']    = self::sanitise($values['identifiant']);
 
        //$tQuest['options']        = json_decode($values['quest_options'],true);    
        $tQuest['options']        = json_decode($values['options'],true);    
        $tQuest['optionsDefault'] =  $clTypeQuestion->optionsDefaults;    
/*
if($values['options'] != $values['quest_options']) {
echo "<hr><pre>{$values['quest_id']} - options : {$values['options']}<br>quest_options : {$values['quest_options']}</pre><hr>";
for($h=0; $h<strlen($values['options']); $h++){
    echo $values['options'][$h] . '-' . ord($values['options'][$h]) . '-' . $values['quest_options'][$h] . '-' . ord($values['quest_options'][$h]) . '<br>';
}
        
}
*/
        $tQuest['comment1']       = self::sanitise($values['quest_comment1']);
        $tQuest['pos_comment1']   = self::sanitise($values['quest_posComment1']);
        $tQuest['explanation']    = self::sanitise($values['quest_explanation']);
        $tQuest['consigne']       = self::sanitise($values['quest_consigne'],0,'UTF-8');
        //echo "<hr><pre>{$tQuest['consigne']}</pre><hr>" ;
        $tQuest['learn_more']     = self::sanitise($values['quest_learn_more']);
        $tQuest['see_also']       = self::sanitise($values['quest_see_also']);
        $tQuest['image']          = self::sanitise($values['quest_image']);
        $tQuest['height']         = self::sanitise($values['quest_height']);
        $tQuest['points']         = $values['points'];
        $tQuest['numbering']      = $values['numbering'];
//        $tQuest['shuffleAnswers'] = $values['shuffleAnswers'];
        $tQuest['isQuestion']     = $values['isQuestion'];
        $tQuest['timer']          = $values['timer'];
        $tQuest['startTimer']     = $values['start_timer'];
        $tQuest['timestamp']      = date("H:i:s");
        $tQuest['urlPlugin']      = QUIZMAKER_URL_PLUGINS_JS . '/' . $values['type_question'];
    
        $tQuest['answers']        = self::exportAnswers2Jason($values['id']);
        $questionsArr[] = $tQuest;

//echoArray($tQuest,'tQuest');   
    }
//echoArray($questionsArr,'tQuest',true);   

   //echo "<hr>tQuest<pre>" . print_r($questionsArr, true) . "</pre><hr>";exit;
   //echo "<hr>tQuest<pre>" . json_encode($questionsArr)  . "</pre><hr>";exit;
    $exp ="var myQuestions = JSON.parse(`" .  json_encode($questionsArr) . "`);";
     $exp = str_replace('"', '\"', $exp);
//     $exp = str_replace("\n", '<br>', $exp);

    
    $br = '<br>' ;     //
    $exp = str_replace('\r\n', $br, $exp);
    $exp = str_replace('\n', $br, $exp);
    $exp = str_replace('\r', $br, $exp);
    
    $exp = FQUIZMAKER\quizmaker_utf8_encode($exp);

    //$exp ="var myQuestions = JSON.parse('" .  json_encode($questionsArr) . "');";
    //$path = QUIZMAKER_PATH_UPLOAD . "/quiz-questions-01.js";
    //$path = QUIZMAKER_PATH_QUIZ_JS . "/quiz-js/data/quiz-questions-01.js";
//    $path = QUIZMAKER_PATH_UPLOAD . "/quiz-js/togodo/quiz-questions.js";
    $fileName = $path . "/js/quiz-questions.js";
    
//    echo "Export ===>{$path}<br>";
//    echo "{$exp}";
    \JJD\FSO\saveTexte2File($fileName, $exp);

//json_encode
}

/* ************************************************
*
* ************************************************* */
public static function exportAnswers2Jason($questId){
global $quizHandler, $questionsHandler, $answersHandler;

    $answersArr = array();
    $criteria = new \CriteriaCompo(new \Criteria('answer_quest_id', $questId, '='));
    //$criteria->add(new \Criteria('answer_proposition', '', '<>'));
    
    $criteria->setsort("answer_weight");
    $criteria->setOrder("ASC");
    $answers = $answersHandler->getObjects($criteria);
    foreach (array_keys($answers) as $i) {
        $values = $answers[$i]->getValuesAnswers();
        $tVals = array();
        if(!$values['proposition']) continue;
        $tVals['answerId']      = $values['answer_id'];
        $tVals['proposition']   = self::sanitise($values['proposition']);
        //$tVals['reponse']       = 0;//$values[''];
        $tVals['inputs']        = $values['inputs'];
        $tVals['points']        = $values['points'];
        $tVals['caption']       = self::sanitise(($values['caption']) ? $values['caption'] : "");
        $tVals['color']         = $values['color'];
        $tVals['background']    = $values['background'];
        $tVals['buffer']        = $values['buffer'];
        $tVals['image1']        = ($values['image1']) ? $values['image1'] : "";
        $tVals['image2']        = ($values['image2']) ? $values['image2'] : "";
        $tVals['group']         = $values['group'];
        
        $answersArr[] = $tVals;
   
    }
    return $answersArr;    
}

/* ************************************************
*
* ************************************************* */
public static function export_consignes($path){
global $type_questionHandler;
    
    $allPlugins =  $type_questionHandler->getAllPlugins();
    
    $consignes = array();
    foreach($allPlugins as $key=>$plugin){
        $consignes[$plugin['type']] = $plugin['consigne'];
    }
    echoArray($consignes);
    
    $exp ="var quiz_consignes = JSON.parse(`" .  json_encode($consignes) . "`);";  
    $exp = str_replace('"', '\"', $exp);
    $br = '<br>' ;     //
    $exp = str_replace('\r\n', $br, $exp);
    $exp = str_replace('\n', $br, $exp);
    $exp = str_replace('\r', $br, $exp);
      
    $exp = FQUIZMAKER\quizmaker_utf8_encode($exp);
    $fileName = $path . "/js/quiz-consignes.js";
    \JJD\FSO\saveTexte2File($fileName, $exp);
   
}

}  //fin de la classe
