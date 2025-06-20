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


require_once __DIR__ . '/header.php';

use Xmf\Request;
use XoopsModules\Quizmaker AS FQUIZMAKER;
use XoopsModules\Quizmaker\Constants;
use XoopsModules\Quizmaker\Utility;
//use JANUS;
//$clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_IMPORTG,'QUIZMAKER_PERMIT_IMPORTG', "index.php");
//-----------------------------------------------------------

require __DIR__ . '/footer.php';

        //sleep(5);
        $catId  = Request::getInt('cat_id',0);
        $inUse = Request::getInt('in_use',0);
        $nbdone = Request::getInt('nbdone',0) + 1;
        
        if(!$inUse){
          //modules/quizmaker/admin/quiz.php?op=build_quiz&quiz_id=3&cat_id=2
          $quizHandler->updateAll('quiz_flag', 0,null,true);
          $criteria = new Criteria("quiz_cat_id",$catId,"=");
          $quizHandler->updateAll('quiz_flag', 1, $criteria, true);
          if($nbdone > 2) exit;
        }
        

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria("quiz_cat_id",$catId,"="));
        $criteria->add(new Criteria("quiz_flag",1,"="));
        
        $countQuiz = $quizHandler->getCountQuiz($criteria, 0,1);
        if ($countQuiz > 0){
            $allQuiz = $quizHandler->getAllQuiz($criteria, 0,1);
            $quiz = array_pop($allQuiz);

            //echoArray($quiz);
            $quizId = $quiz->getVar('quiz_id');
            //echo "<hr>" . $quiz->getVar('quiz_id') ."-" . $quiz->getVar('quiz_name') . "<hr>";
            $nbdone++;
            $quizHandler->setValue($quizId,'quiz_flag', 0);
            $url = "build_quiz_cat.php?op=build_all_quiz_cat&cat_id={$catId}&in_use=1&nbdone={$nbdone}";
            //echo $url . '<br>';//exit;
            redirect_header($url, 5, sprintf(_AM_QUIZMAKER_QUIZ_BUILD_ALL_QUIZ_ID,$quizId) );
        }else{
            redirect_header("quiz.php?op=list&cat_id={$catId}", 5, _AM_QUIZMAKER_QUIZ_BUILD_ALL_OK);
        }
