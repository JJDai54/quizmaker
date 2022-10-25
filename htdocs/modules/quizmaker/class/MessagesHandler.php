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

use XoopsModules\Quizmaker;


/**
 * Class Object Handler Messages
 */
class MessagesHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct(\XoopsDatabase $db)
	{
		parent::__construct($db, 'quizmaker_messages', Messages::class, 'msg_id', 'msg_code');
	}

	/**
	 * @param bool $isNew
	 *
	 * @return object
	 */
	public function create($isNew = true)
	{
		return parent::create($isNew);
	}

	/**
	 * retrieve a field
	 *
	 * @param int $i field id
	 * @param null fields
	 * @return mixed reference to the {@link Get} object
	 */
	public function get($i = null, $fields = null)
	{
		return parent::get($i, $fields);
	}

	/**
	 * get inserted id
	 *
	 * @param null
	 * @return integer reference to the {@link Get} object
	 */
	public function getInsertId()
	{
		return $this->db->getInsertId();
	}

	/**
	 * Get Count Messages in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCountMessages($start = 0, $limit = 0, $sort = 'msg_id ASC, msg_code', $order = 'ASC')
	{
		$crCountMessages = new \CriteriaCompo();
		$crCountMessages = $this->getMessagesCriteria($crCountMessages, $start, $limit, $sort, $order);
		return parent::getCount($crCountMessages);
	}

	/**
	 * Get All Messages in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
	public function getAllMessages($start = 0, $limit = 0, $sort = 'msg_id ASC, msg_code', $order = 'ASC')
	{
		$crAllMessages = new \CriteriaCompo();
		$crAllMessages = $this->getMessagesCriteria($crAllMessages, $start, $limit, $sort, $order);
		return parent::getAll($crAllMessages);
	}

	/**
	 * Get Criteria Messages
	 * @param        $crMessages
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	private function getMessagesCriteria($crMessages, $start, $limit, $sort, $order)
	{
		$crMessages->setStart( $start );
		$crMessages->setLimit( $limit );
		$crMessages->setSort( $sort );
		$crMessages->setOrder( $order );
		return $crMessages;
	}

/* ******************************
 * renvoie une liste "id=>name" pour les formSelect 
 * *********************** */
    public function getListKeyName(CriteriaElement $criteria = null, $addAll=false, $addNull=false)    {
        $ret     = array();
        if ($addAll) $ret[0] = "(*)";
//         if ($addNull) $inpList->addOption('_NULL_', _AM_CARTOUCHES_NULL);
        $obs = $this->getObjects($criteria, true);
        foreach (array_keys($obs) as $i) {
                $ret[$obs[$i]->getVar('msg_id')] = $obs[$i]->getVar('msg_code' . " | " . $obs[$i]->getVar('msg_constant'));
        
        }

        return $ret;
    }

    
}
