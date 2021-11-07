<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright      {@link https://xoops.org/ XOOPS Project}
 * @license        {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */
use Xmf\Module\Helper;
// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

/**
 * @return array
 */
 include_once (dirname(__DIR__) . '/class/xoopstree.php');
 include_once (dirname(__DIR__) . '/class/xoopstopic.php');
 include_once (dirname(__DIR__) . '/class/class.newstopic.php');
 //include_once (dirname(__DIR__) . '/class/news_topics.php');


function b_quizmaker_menu_xbootstrap_show()
{
global $xoopsDB, $xoTheme;
    $block = [];

// Get instance of module
$helper = \XoopsModules\Quizmaker\Helper::getInstance();
$categoriesHandler = $helper->getHandler('Categories');
    
    //$catAll = $categoriesHandler->getListKeyName();
    $catAll = b_getChildTreeArray();
    
     $moduleDirName = basename(dirname(__DIR__));
//     //$prefix = '<img src="'.XOOPS_URL.'/modules/'.$moduleDirName.'/assets/images/deco/arrow.gif">';
// 
//     /** @var \xos_opal_Theme $xoTheme */
//     $xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/blocks.css', null);
// 
// 
//     $xt          = new NewsTopic($xoopsDB->prefix('news_topics'), 'topic_id', 'topic_pid');
//     $topics_arr  = $xt->getChildTreeArray(0, 'topic_weight,topic_title');

//echoArray($catAll);
//echo "<hr><pre>" . print_r($catAll, true) . "</pre><HR>";
// $tr = print_r($topics_arr, true);
// echo "<hr><pre>{$tr}</pre><hr>";

      $MenuItems = [];
      $url = XOOPS_URL . "/modules/" . $moduleDirName . "/categories.php?cat_id=";
      $previousKey = "";

// --- Tous les articles
      $k=-1;
      $item = array('id'=>$k, 'lib'=>_MB_QUIZMAKER_ALL_QUIZ, 'url' => $url);
      $MenuItems[$k] = $item;



      foreach ($catAll as $k=>$t){
        $title = $t['cat_name'];

        $item = array('id'=>$k, 'lib'=>$title, 'url' => $url . $t['cat_id']);
        //$item = array('id'=>$k, 'lib'=>$t['topic_title'], 'url' => $url . $t['topic_id']);
        if ($t['cat_parent_id'] > 0){
          $MenuItems[$previousKey]['submenu'][$k] = $item;
        }else{
          $MenuItems[$k] = $item;
          $previousKey = $k;
        }
      }





     $block['MenuCatItems'] = $MenuItems;

// $tr = print_r($MenuItems, true);
// echo "<hr><pre>{$tr}</pre><hr>";





    $block['module']['url'] = XOOPS_URL . "/modules/" . $moduleDirName ;
    $block['module']['lib'] = _MB_QUIZMAKER_QUIZ;


    $block['search']['url'] = XOOPS_URL . "/modules/" . $moduleDirName . "/categories.php?scat_id=";
    $block['search']['lib'] = _MB_QUIZMAKER_ALL_QUIZ;


//---------------------------------------------------------------------------------------


// --- Index des categories
    $block['main']['topicsIndex']['url'] = XOOPS_URL . "/modules/" . $moduleDirName . "/categories.php";
    $block['main']['topicsIndex']['lib'] = _MB_QUIZMAKER_CAT_INDEX;


    $block['module']['nbMainMenu'] = count($block['main']);
//echo "<hr><pre>" . print_r($block, true) . "</pre><HR>";
    return $block;
}
/****************
 *
 ***************/
function block_quizmaker_parse_title($title, $sep='|') {
      $h = strpos($title, $sep);
      if (!($h === false)) $title = substr($title,$h+1);
      return $title;

}

/**
 * @param $options
 */
function b_quizmaker_menu_xbootstrap_edit($options)
{
}

    /**
     * Enter description here...
     *
     * @param int|mixed    $sel_id
     * @param string|mixed $order
     * @param array|mixed  $parray
     * @param string|mixed $r_prefix
     *
     * @return mixed
     */
function b_getChildTreeArray($parentId = 0, $order = '', $parray = [], $r_prefix = '')
    {
        global $xoopsDB;
        
        $parentId = (int)$parentId;
        $sql    = "SELECT * FROM " . $xoopsDB->prefix('quizmaker_categories') . " WHERE cat_parent_id = {$parentId}";
        if ('' !== $order) {
            $sql .= " ORDER BY $order";
        }
        $result = $xoopsDB->query($sql);
        $count  = $xoopsDB->getRowsNum($result);
        if (0 == $count) {
            return $parray;
        }
        while ($row = $xoopsDB->fetchArray($result)) {
            $row['prefix'] = $r_prefix . '.';
            array_push($parray, $row);
            $parray = b_getChildTreeArray($row['cat_id'], $order, $parray, $row['prefix']);
        }

        return $parray;
    }

