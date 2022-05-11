<?php
/**
 * extcal module.
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright           XOOPS Project (https://xoops.org)
 * @license             http://www.fsf.org/copyleft/gpl.html GNU public license
 *
 * @since               2.2
 *
 * @author              JJDai <http://xoops.kiolo.com>
 **/

//----------------------------------------------------
class Quizmaker_2_92
{
    //----------------------------------------------------

    /**
     * @param \XoopsModule $module
     * @param             $options
     */
    public function __construct(\XoopsModule $module, $options)
    {
        global $xoopsDB;

        $this->alterTable_questions();
    }

    //----------------------------------------------------
    public function alterTable_questions()
    {
        global $xoopsDB;
        $tbl = $xoopsDB->prefix('quizmaker_questions');

        $sql = <<<__sql__
ALTER TABLE `{$tbl}` 
ADD `quest_learn_more` VARCHAR(255) NOT NULL,
ADD `quest_see_also` VARCHAR(255) NOT NULL;
__sql__;

        $xoopsDB->queryF($sql);
    }


    //-----------------------------------------------------------------
}   // fin de la classe
