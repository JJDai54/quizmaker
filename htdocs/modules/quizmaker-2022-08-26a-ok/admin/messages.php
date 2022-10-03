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
 * QuizMaker module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        quizmaker
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */

use Xmf\Request;
use XoopsModules\Quizmaker;
use XoopsModules\Quizmaker\Constants;

require __DIR__ . '/header.php';
// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
// Request msg_id
$msgId = Request::getInt('msg_id');
switch($op) {
	case 'list':
	default:
		// Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
		$templateMain = 'quizmaker_admin_messages.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('messages.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_ADD_MESSAGES, 'messages.php?op=new', 'add');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		$messagesCount = $messagesHandler->getCountMessages();
		$messagesAll = $messagesHandler->getAllMessages($start, $limit);
		$GLOBALS['xoopsTpl']->assign('messages_count', $messagesCount);
		$GLOBALS['xoopsTpl']->assign('quizmaker_url', QUIZMAKER_URL);
		$GLOBALS['xoopsTpl']->assign('quizmaker_upload_url', QUIZMAKER_UPLOAD_URL);
		// Table view messages
		if ($messagesCount > 0) {
			foreach(array_keys($messagesAll) as $i) {
				$Messages = $messagesAll[$i]->getValuesMessages();
				$GLOBALS['xoopsTpl']->append('messages_list', $Messages);
				unset($Messages);
			}
			// Display Navigation
			if ($messagesCount > $limit) {
				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
				$pagenav = new \XoopsPageNav($messagesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
			}
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_QUIZMAKER_THEREARENT_MESSAGES);
		}
	break;
	case 'new':
		$templateMain = 'quizmaker_admin_messages.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('messages.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_MESSAGES_LIST, 'messages.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Form Create
		$messagesObj = $messagesHandler->create();
		$form = $messagesObj->getFormMessages();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'save':
		// Security Check
		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('messages.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if ($msgId > 0) {
			$messagesObj = $messagesHandler->get($msgId);
		} else {
			$messagesObj = $messagesHandler->create();
		}
		// Set Vars
		$messagesObj->setVar('msg_code', Request::getString('msg_code', ''));
		$messagesObj->setVar('msg_constant', Request::getString('msg_constant', ''));
		//$messagesObj->setVar('msg_editable', Request::getString('msg_editable', 1));
		// Insert Data
		if ($messagesHandler->insert($messagesObj)) {
			redirect_header('messages.php?op=list', 2, _AM_QUIZMAKER_FORM_OK);
		}
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $messagesObj->getHtmlErrors());
		$form = $messagesObj->getFormMessages();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'edit':
		$templateMain = 'quizmaker_admin_messages.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('messages.php'));
		$adminObject->addItemButton(_AM_QUIZMAKER_ADD_MESSAGES, 'messages.php?op=new', 'add');
		$adminObject->addItemButton(_AM_QUIZMAKER_MESSAGES_LIST, 'messages.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$messagesObj = $messagesHandler->get($msgId);
		$form = $messagesObj->getFormMessages();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'delete':
		$messagesObj = $messagesHandler->get($msgId);
		$msgCode = $messagesObj->getVar('msg_code');
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('messages.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
			if ($messagesHandler->delete($messagesObj)) {
				redirect_header('messages.php', 3, _AM_QUIZMAKER_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $messagesObj->getHtmlErrors());
			}
		} else {
			xoops_confirm(['ok' => 1, 'msg_id' => $msgId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], sprintf(_AM_QUIZMAKER_FORM_SURE_DELETE, $messagesObj->getVar('msg_code')));
		}
	break;
}
require __DIR__ . '/footer.php';
