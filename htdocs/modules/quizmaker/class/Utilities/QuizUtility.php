<?php

namespace XoopsModules\Quizmaker\Utilities;

/*
 Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Module:  quizmaker
 *
 * @package      \module\quizmaker\class
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       ZySpec <owners@zyspec.com>
 * @author       Mamba <mambax7@gmail.com>
 * @since        
 */

use XoopsModules\Quizmaker AS FQUIZMAKER;
use Xmf\Request;
use JANUS;
//include_once XOOPS_ROOT_PATH . "/modules/quizmaker/class/Utility.php";
                            
//$utility = new \XoopsModules\Quizmaker\Utility();

/**
 * Class Utility
 */
trait QuizUtility
{
// =========================================================
// ============ Fonctions Generales              ===========
// =========================================================

/**************************************************************
 * copy_quest_images : copie le tableau des images d'une question 
 * utilisé pour deplacer ou compié une question dans un autre quiz.
 * @$imgArray : tableau des imaes à copier ou déplacer
 * $pathFrom : chemin sources des images
 * $pathTo : cheminde destination des images
 * $moveImages : true = déplacement des images, falws = copie des images
 * ************************************************************/
public static function copy_quest_images($imgArray, $pathFrom, $pathTo, $moveImages=false){
    foreach($imgArray AS $key=>$name){
        $imgFrom = $pathFrom . '/' . $name;
        $imgto   = $pathTo . '/' . $name;
        copy($imgFrom, $imgto);
        if($moveImages){
            unlink($imgFrom);
        }
    }
}


}  //fin de la class
