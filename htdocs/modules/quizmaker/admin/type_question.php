<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Quizmaker module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        quizmaker
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */

use Xmf\Request;
use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Constants;
//use JJD;

require __DIR__ . '/header.php';
// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request type_id
$typeId = Request::getInt('type_id');



$sender  = Request::getString('sender', '');
$catTypeQuestion   = Request::getString('catTypeQuestion', QUIZMAKER_ALL);


switch($op) {
	case 'list':
	default:
        $GLOBALS['xoopsTpl']->assign('buttons', '');
        // Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $quizmakerHelper->getConfig('adminpager'));
		$templateMain = 'quizmaker_admin_type_question.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('type_question.php'));    
        
		$type_questionAll = $type_questionHandler->getAll($catTypeQuestion);
		$type_questionCount = count($type_questionAll);
//echo "<hr>type_questions<pre>" . print_r($type_questionAll, true) . "</pre><hr>";        
		$GLOBALS['xoopsTpl']->assign('type_question_count', $type_questionCount);
		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL_MODULE);
		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_URL_UPLOAD);
		$GLOBALS['xoopsTpl']->assign('catTypeQuestion', $catTypeQuestion);
        
        // ----- Listes de selection pour filtrage des type de questions par categorie-----  
        //if ($catId == 0) $catId = $quiz->getVar('quiz_cat_id');
        //$cat = $categoriesHandler->getListKeyName(null, false, false);
        $inpCatTQ = new \XoopsFormSelect(_AM_QUIZMAKER_CATEGORIES, 'catTypeQuestion', $catTypeQuestion);
        $inpCatTQ->addOptionArray($type_questionHandler->getCategories(true));
        $inpCatTQ->setExtra('onchange="document.quizmaker_select_filter.sender.value=this.name;document.quizmaker_select_filter.submit();"' . FQUIZMAKER\getStyle(QUIZMAKER_BG_LIST_TYPEQUEST));
  	    $GLOBALS['xoopsTpl']->assign('inpCatTQ', $inpCatTQ->render());

        $catId = $categoriesHandler->getId(_AM_QUIZMAKER_CAT_EXEMPLES);
        $allQuiz = $quizHandler->getKeysByCat($catId, 'quiz_name');
        //echoArray($allQuiz,"=========================================");
        
        //recuperation du quiz si il existe
        foreach($type_questionAll AS $key=>$tQuestions){
             $plugin = 'slide_' . $type_questionAll[$key]['type'];
             if (isset($allQuiz[$plugin])){
                 $type_questionAll[$key]['quiz_id'] =  $allQuiz[$plugin];    
             }else{$type_questionAll[$key]['quiz_id'] = 0;}
        }        
		$GLOBALS['xoopsTpl']->assign('type_question_list', $type_questionAll);
        //echoArray($type_questionAll);
                        
\JJD\include_highslide();
	break;

	case 'view':
    
/*
<a href='' onclick="javascript:openWithSelfMain('<{$smarty.const._MED_URL}>/diapo.php?idMedia=<{$media.idMedia}>','',900,600);return false;"
http://127.0.0.16/modules/quizmaker/plugins/alphaSimple/language/french/help.html
 
<a href='' onclick="javascript:openWithSelfMain('<{$smarty.const.XOOPS_URL}>//modules/quizmaker/plugins/alphaSimple/language/french/help.html>','',900,600);return false;"
http://127.0.0.16/modules/quizmaker/plugins/alphaSimple/language/french/help.html
    public function 
		$type_questionAll = $type_questionHandler->getAll($catTypeQuestion);    
*/    
      $templateMain = 'quizmaker_admin_type_question_help.tpl';
      $plugin = Request::getString('plugin', '');
      //echo "<hr>plugin : {$plugin}<hr>";
      $clsTypeQuestion = $type_questionHandler->getTypeQuestion($plugin);
      $GLOBALS['xoopsTpl']->assign('viewHelpTypeQuestion', $clsTypeQuestion->getViewType_question());

        
        break;

	case 'play':
    echoGPF();
      $plugin = Request::getString('plugin', '');
      //$ok = Request::getString('ok', 0);
      $clsTypeQuestion = $type_questionHandler->getTypeQuestion($plugin);
        $clsTypeQuestion->playQuizExemple($plugin);
//       
//       
//       if($ok){
//         $clsTypeQuestion->installQuizExemple($plugin);
//       }else{
//         $clsTypeQuestion->playQuizExemple($plugin);
//       }
      
      break;
      
	case 'install':
      $plugin = Request::getString('plugin', '');
      $ok = Request::getString('ok', 0);
      //$clsTypeQuestion = $type_questionHandler->getTypeQuestion($plugin);
      //$clsTypeQuestion->installQuizExemple($plugin, $ok);
      
      
      $url = "type_question.php?catTypeQuestion={$catTypeQuestion}";
      $clsTypeQuestion = $type_questionHandler->getTypeQuestion($plugin);
      switch($clsTypeQuestion->installQuizExemple($plugin, $ok)){
        case 1: // demande de confirmqtion
            $msg = sprintf(_AM_QUIZMAKER_PLUGIN_EXIST, $plugin);
            xoops_confirm(['ok' => 1, 'plugin' => $plugin, 'op' => 'install'], $url, $msg);//$_SERVER['REQUEST_URI']
            break;
        case 2: // l'archive n'existe pas
            redirect_header($url, 5, _AM_QUIZMAKER_PLUGIN_ARCHIVE_EXEMPLE_NOT);
            break;
        
        case 0:
        default: //tout va bien
            redirect_header($url, 5, _AM_QUIZMAKER_PLUGIN_INSTALL_OK);
        
        break;
      }
      
      
      
      
      break;

	case 'edit':
      $plugin = Request::getString('plugin', '');
      //$criteria = new CriteriaCompo(new Criteria('quiz_name', '', "="))
      $quizId = Request::getInt('quiz_id', '');
      $url = "suestions.php?quiz_id={$quizId}";
      redirect_header($url, 0, '');
    break;

}
require __DIR__ . '/footer.php';
