<?php

namespace XoopsModules\Quizmaker;

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

use XoopsModules\Quizmaker AS FQUIZMAKER;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object PermissionsHandler
 */
class PermissionsHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
	}
	/**
	 * @public function permGlobalApprove
	 * returns right for global approve
	 *
	 * @param null
	 * @return bool
	 */
	public function getPermGlobal($itemId, $name ='quizmaker_ac')
	{
		global $xoopsUser, $xoopsModule;
		$currentuid = 0;
		if (isset($xoopsUser) && is_object($xoopsUser)) {
			if ($xoopsUser->isAdmin($xoopsModule->mid())) {
				return true;
			}
            $currentuid = ($xoopsUser) ? $xoopsUser->uid() : 2;
		}
		$grouppermHandler = xoops_getHandler('groupperm');
		$mid = $xoopsModule->mid();
		$memberHandler = xoops_getHandler('member');
		if (0 == $currentuid) {
			$my_group_ids = [XOOPS_GROUP_ANONYMOUS];
		} else {
			$my_group_ids = $memberHandler->getGroupsByUser($currentuid);;
		}
		if ($grouppermHandler->checkRight($name, $itemId, $my_group_ids, $mid)) {
			return true;
		}
		return false;
	}

	/**
	 * @public function permGlobalApprove
	 * returns right for global approve
	 *
	 * @param null
	 * @return bool
	 */
	public function getPermGlobalApprove()
	{
        return $this->getPermGlobal(4);
	}

	/**
	 * @public function permGlobalSubmit
	 * returns right for global submit
	 *
	 * @param null
	 * @return bool
	 */
	public function getPermGlobalSubmit()
	{
        return $this->getPermGlobal(8);
	}

	/**
	 * @public function permGlobalView
	 * returns right for global view
	 *
	 * @param null
	 * @return bool
	 */
	public function getPermGlobalView()
	{
        return $this->getPermGlobal(16);
	}
    
	/**
	 * @public function getPermForm
	 * returns form for perm
	 *
	 * @param $formTitle Titre du formulaire
	 * @param $permName  Nom des permissions
	 * @param $permDesc  Description des permissions
	 * @param $permArr   Tableau itemId/name des permission
	 * @return XoopsGroupPermForm
	 */
     
	public function getPermForm($formTitle, $permName, $permDesc, $permArr)
	{
    global $xoopsModule;
		$permName = $xoopsModule->getVar('dirname') . '_' . $permName;
		//$handler = $quizmakerHelper->getHandler('quiz');
        $moduleId = $xoopsModule->getVar('mid');
        $permform = new \XoopsGroupPermForm($formTitle, $moduleId, $permName, $permDesc, 'admin/permissions.php');
    	foreach($permArr as $permId => $permName) {
    		$permform->addItem($permId, $permName);
    	}
        return $permform;
	}
}

