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
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 * @param mixed $val
 */

/**
 * @param $val
 * @return float|int
 */
function quizmaker_returnBytes($val)
{
//echo "<hr>val = {$val}<hr>";
    switch (mb_substr($val, -1)) {
        case 'K':
        case 'k':
            return (int)$val * 1024;
        case 'M':
        case 'm':
            return (int)$val * 1048576;
        case 'G':
        case 'g':
            return (int)$val * 1073741824;
        default:
            return $val;
    }
}

function quizmaker_getSizesArr(){
    $step = 1048576;
    $iniPostMaxSize       = quizmaker_returnBytes(\ini_get('post_max_size'));
    $iniUploadMaxFileSize = quizmaker_returnBytes(\ini_get('upload_max_filesize'));
    if($iniPostMaxSize == 0) $iniPostMaxSize = $iniUploadMaxFileSize; 
    $maxSize = min($iniPostMaxSize, $iniUploadMaxFileSize);
    //echo "iniPostMaxSize = {$iniPostMaxSize}<br>iniUploadMaxFileSize = {$iniUploadMaxFileSize}<hr>";
    
         if ($maxSize <=    25 * $step) {$increment =   0.5;}
    else if ($maxSize <=   100 * $step) {$increment =   2;}
    else if ($maxSize <=   500 * $step) {$increment =   5;}
    else if ($maxSize <=  1000 * $step) {$increment =  10;}
    else if ($maxSize <=  2500 * $step) {$increment =  50;}
    else if ($maxSize <=  5000 * $step) {$increment = 100;}
    else if ($maxSize <= 10000 * $step) {$increment = 200;}
    else {$increment = 500;}
    
    $optionMaxsize = []; //52428800
    $i = $increment;
    while ($i * $step <= $maxSize) {
        $optionMaxsize[$i . ' ' . _MI_QUIZMAKER_SIZE_MB] = $i * $step;
        $i += $increment;
    }
//echo "<hr>optionMaxsize = <pre>" .  print_r($optionMaxsize,true) . "</pre><hr>";
//exit;
    
    return $optionMaxsize;
}



