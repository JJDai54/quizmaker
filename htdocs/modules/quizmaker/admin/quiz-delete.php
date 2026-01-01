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
//use JANUS;

        $clPerms->checkAndRedirect('delete_quiz', $quizCat_id,'$quizCat_id', "quiz.php?op=list&cat_id={$quizCat_id}", QUIZMAKER_ADMIN_PERM);
        $delCatId = $quizmakerHelper->getConfig('action_on_del_cat');
        //verifie si la catégorie de suppression defini dans les options du module existe
        //si elle n'existe pas retour à la liste
        if ($delCatId > 0 && !$categoriesHandler->exists($delCatId)) {
            redirect_header("quiz.php?cat_id={$quizCat_id}", 5, _AM_QUIZMAKER_QUIZ_DEL_CAT_NOT_EXISTS);
        }
        //-------------------------------------------------------
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if ($delCatId > 0 && $quizCat_id != $delCatId) {
//                 $quizObj->setVar('quiz_cat_id', $delCatId);
//                 ($quizHandler->insert($quizObj));
                $quizHandler->updatField('quiz_cat_id', $delCatId, $quizId);
//echo "quizCat_id = {$quizCat_id} - delCatId = {$delCatId}";exit;        
                redirect_header("quiz.php?cat_id={$quizCat_id}", 3, _AM_QUIZMAKER_QUIZ_ON_CAT_OK);
            }else{
    			if (!$GLOBALS['xoopsSecurity']->check()) {
    				redirect_header('quiz.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
    			}
    			if ($quizHandler->delete($quizObj)) {
    				redirect_header("quiz.php?cat_id={$quizCat_id}", 3, _AM_QUIZMAKER_FORM_DELETE_OK);
    			} else {
    				$GLOBALS['xoopsTpl']->assign('error', $quizObj->getHtmlErrors());
    			}
            }                          
		} else {
            if ($delCatId > 0 && $quizCat_id != $delCatId) {
                $msg =  sprintf(_AM_QUIZMAKER_SURE_TRANSFERT_QUIZ, $quizObj->getVar('quiz_id'), $quizObj->getVar('quiz_name'));
            }else{
                $msg =  sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $quizObj->getVar('quiz_id'), $quizObj->getVar('quiz_name'));
            }
			xoops_confirm(['ok' => 1, 'quiz_id' => $quizId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], $msg);
		}
