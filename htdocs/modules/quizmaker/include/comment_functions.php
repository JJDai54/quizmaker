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

/**
 * CommentsUpdate 
 *
 * @param mixed  $itemId 
 * @param mixed  $itemNumb 
 * @return bool
 */
function quizmakerCommentsUpdate($itemId, $itemNumb)
{
	// Get instance of module
	$quizHelper = \XoopsModules\Quizmaker\Helper::getInstance();
	$messagesHandler = $quizHelper->getHandler('Messages');
	$msgId = (int)$itemId;
	$messagesObj = $messagesHandler->get($msgId);
	$messagesObj->setVar('msg_comments', (int)$itemNumb);
	if ($messagesHandler->insert($messagesObj)) {
		return true;
	}
	return false;
}

/**
 * CommentsApprove 
 *
 * @param mixed $comment
 * @return bool
 */
function quizmakerCommentsApprove(&$comment)
{
	// Notification event
	// Get instance of module
	$quizHelper = \XoopsModules\Quizmaker\Helper::getInstance();
	$messagesHandler = $quizHelper->getHandler('Messages');
	$msgId = $comment->getVar('com_itemid');
	$messagesObj = $messagesHandler->get($msgId);
	$msgCode = $messagesObj->getVar('msg_code');

	$tags = [];
	$tags['ITEM_NAME'] = $msgCode;
	$tags['ITEM_URL']  = XOOPS_URL . '/modules/quizmaker/messages.php?op=show&msg_id=' . $msgId;
	$notificationHandler = xoops_getHandler('notification');
	// Event modify notification
	$notificationHandler->triggerEvent('global', 0, 'global_comment', $tags);
	$notificationHandler->triggerEvent('messages', $msgId, 'Messages_comment', $tags);
	return true;

}
