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
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<https://xoopsfr.kiolo.fr>
 */

use Xmf\Request;
use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Constants;
use XoopsModules\Quizmaker\Utility;

//echoArray('gp',"ici->$typeImport");exit;
// $reponse2 = mysql_query('SHOW COLUMNS FROM "'.htmlspecialchars($_GET['table']).'"') or die(mysql_error());
// while ($donnees2 = mysql_fetch_array($reponse2))
// {
//   print_r($donnees2);
// }

//SELECT column_name FROM information_schema.columns WHERE table_name = 'ma_table' AND table_schema='ma_base';

$fromCatId = Request::getInt('from_cat_id', 0);
if($fromCatId == 0) $fromCatId = array_key_first($catArr);
$fromQuizSet = Request::getString('from_quiz_subject', '');
$fromQuizId = Request::getInt('from_quiz_id', 0);

$toCatId = Request::getInt('to_cat_id', 0);
if($toCatId == 0) $toCatId = array_key_first($catArr);
$toQuizSet = Request::getString('to_quiz_subject', '');

    switch($op){
    case 'getform':
        if(!isset($errors)) {
          if($objError->getErrors())
              $errors = $objError->getHtmlErrors();
          else
              $errors = '';
        }
        
      $GLOBALS['xoopsTpl']->assign('error', $errors);
      $objError = new \XoopsObject();     
      //----------------------------------------------------
        $GLOBALS['xoopsTpl']->assign('buttons', '');        
    
		$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		// Permissions for uploader
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		
        
        // Title
		$title = _AM_QUIZMAKER_IMPORT;        
		// Get Theme Form
		//xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, $formName, $importMainFile, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// To Save
		$form->addElement(new \XoopsFormHidden('op', 'import'));
        $form->addElement(new \XoopsFormHidden('sender', ''));
        addXformImportType($form, $typeImportName, $typeImport);

        // ----- Listes de selection From pour filtrage -----  
  	    $form->insertBreak(sprintf($styleBreakLine, _AM_QUIZMAKER_SELECT_QUIZ_TO_CLONE));
        
        addXformCat  ($form, 'from_cat_id',   $fromCatId, true);
        addXformSet  ($form, 'from_quiz_subject', $fromQuizSet, $fromCatId, true);
        addXformQuiz ($form, 'from_quiz_id',  $fromQuizId, $fromCatId, $fromQuizSet, $addEvent = false);

    //-------------------------------------------------------------------

        // ----- Listes de selection To pour filtrage -----  
  	    $form->insertBreak(sprintf($styleBreakLine, _AM_QUIZMAKER_SELECT_DEST_TO_CLONE));

        addXformCat  ($form, 'to_cat_id',   $toCatId, true);
        addXformSet  ($form, 'to_quiz_subject', $toQuizSet, $toCatId, true);
        //-------------------------------------------------------------------
        addXformBtnSubmit($form);

		$GLOBALS['xoopsTpl']->assign('form', $form->render());        
        break;
        
    case 'confirm':
        break;
        
    case 'import':
          $tblQuiz = $xoopsDB->prefix('quizmaker_quiz');    
          $tblQuest = $xoopsDB->prefix('quizmaker_questions');    
          $tblAns = $xoopsDB->prefix('quizmaker_answers');  
          
            //Mise à zéro du champ flag pour tous les quiz  
            $sql= "UPDATE {$tblQuiz} set quiz_flag=0"; 
            $xoopsDB->query($sql);
            
            //un nombre aléatoire qui sera ajouter au nom et au dossier javascript du nouveau quiz 
            $rnd = rand(10000,99999);
            
            //liste des champs a dupliquer tel quel en excluant ceux qui recevront les vaaleurs modifiées du nouveau quiz
            $fieldsArr = \JANUS\FSO\getFieldsFromTable('quizmaker_quiz', false, ['quiz_id', 'quiz_cat_id', 'quiz_flag','quiz_name','quiz_folderJS', 'quiz_subject']);
            $columns = implode(',', $fieldsArr);
            
            //duplication du quiz source "$fromQuizId" avec maj due quest_flad, quiz_name et quiz_quiz_folderJS
            $setName = ($toQuizSet == QUIZMAKER_ALL_ITEMS_KEY) ? 'quiz_subject' : "'$toQuizSet'";
            $sql = "INSERT INTO {$tblQuiz} (quiz_flag,quiz_cat_id,quiz_name,quiz_folderJS,quiz_subject,{$columns}) "
                 . " SELECT quiz_id,{$toCatId}, concat(quiz_name, '_{$rnd}'), concat(quiz_folderJS, '_{$rnd}'), {$setName}, {$columns}"
                 . " FROM {$tblQuiz} WHERE quiz_id = {$fromQuizId};"; 
            $xoopsDB->query($sql);

            // Recuperation du nouveau quizId à partir du champ quiz_flag qui contient le quizId de l'original
            $sql = "SELECT quiz_id FROM {$tblQuiz} WHERE quiz_flag = {$fromQuizId}";
            $rst = $xoopsDB->query($sql);
            if ($xoopsDB->getRowsNum($rst)>0){
              $t   = $xoopsDB->fetchArray($rst);
              $toQuizId = $t['quiz_id'];
              //echo "===> newId = {$toQuizId}<hr>";jexit;
            }else{
              $url = "import.php?{$typeImportName}={$typeImport}";
              redirect_header($url, 5, _AM_QUIZMAKER_IMPORT_ERROR_CLONE_1);
            }

            // recherche de la liste des quest_id lié au quiz
            $criteria = new Criteria('quest_quiz_id', $fromQuizId);
            $questIdsArr = \JANUS\FSO\comaList('quizmaker_questions', 'quest_id', $criteria, "quest_id");
            
            
           
            //$utility::quiz_import_sql(explode(',',$questIdsArr), $quizIdFrom, $quizIdTo);
            //réutilisation de la fonction quiz_import_sql pour dupliquer les question et les proposition (answers)
            $utility::quiz_import_sql(explode(',',$questIdsArr), $fromQuizId, $toQuizId);

            //exit('Import clone en cours de developpement' . "\n ids question : {$questIdsArr}");
            //redirection vers les questions du nouveau quiz cloné
            $url = "questions.php?op=list&quiz_id={$toQuizId}";
            $msg = "Importation ok !";
            redirect_header($url, 5, $msg);

    
        break;
    default : 
        //echoArray('gpf','import',true);
        break;
    }

        

