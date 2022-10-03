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
 * xoModuleIcons32 Smarty compiler plug-in
 *
 * @copyright       (c) 2000-2016 XOOPS Project (www.xoops.org)
 * @license             GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author              Andricq Nicolas (AKA MusS)
 * @since               2.5.2
 * @param $argStr
 * @param $smarty
 * @return string
 */
/*
function smarty_compiler_xoModuleIcons32($argStr, &$smarty)
{
    global $xoops, $xoTheme;

    if (file_exists($xoops->path('Frameworks/moduleclasses/icons/32/index.html'))) {
        $url = $xoops->url('Frameworks/moduleclasses/icons/32/' . $argStr);
    } else {
        if (file_exists($xoops->path('modules/system/images/icons/default/' . $argStr))) {
            $url = $xoops->url('modules/system/images/icons/default/' . $argStr);
        } else {
            $url = $xoops->url('modules/system/images/icons/default/xoops/xoops.png');
        }
    }

    return "\necho '" . addslashes($url) . "';";
}

function smarty_function_quizFlagAscii($flag, $argStr, $color0='red', $color1='green')
{
    $color = ($flag) ? $color0 : $color1;
    $fontSize = '12px';
    echo "togodo";
    echo "<span style='font-family: Arial Rounded MT Bold; font-size:{$fontSize}; color: {$color};'>{$argStr}</span>";
    //return "<span style='font-family: Arial Rounded MT Bold; font-size:{$fontSize}; color: {$color};'>{$argStr}</span>";
}
*/

function smarty_function_quizFlagAscii($args, &$smarty)
{
    $tColors = (isset($args['colors'])) ? explode('|',$args['colors']) : array('red', 'green', 'blue');
    $exp = $args['exp'];
    $color = $tColors[$args['flag']];
    $fontSize = '16px';
    //echo "togodo";
    echo "<span style='font-family: Arial Rounded MT Bold; font-size:{$fontSize}; color: {$color};'>{$exp}</span>";
    //return "<span style='font-family: Arial Rounded MT Bold; font-size:{$fontSize}; color: {$color};'>{$argStr}</span>";
}

function smarty_function_quizFlagAlpha($args, &$smarty)
{
    $tExp = (isset($args['exp'])) ? explode('|',$args['exp']) : array('Yes','No','Auto');
    $tColors = (isset($args['colors'])) ? explode('|',$args['colors']) : array('red', 'green', 'blue');
    $exp = $tExp [$args['flag']];
    $color = $tColors[$args['flag']];
    $fontSize = '16px';
    echo "<span style='font-family: Arial Rounded MT Bold; font-size:{$fontSize}; color: {$color};'>{$exp}</span>";
}

/* **************************************************** */
function quizFlagAscii($flag, $exp, $color = 'red|green|blue')
{
    $tColors = (isset($colors)) ? explode('|',$colors) : array('red', 'green', 'blue');
    //$exp = implode('|', $exp);
    $color = $tColors[$flag];
    $fontSize = '16px';
    //echo "togodo";
    return "<span style='font-family: Arial Rounded MT Bold; font-size:{$fontSize}; color: {$color};'>{$exp}</span>";
    //return "<span style='font-family: Arial Rounded MT Bold; font-size:{$fontSize}; color: {$color};'>{$argStr}</span>";
}

function quizFlagAlpha($flag, $exps, $colors = 'red|green|blue')
{
    $tExp = (isset($exps)) ? explode('|',$exps) : array('Yes','No','Auto');
    $tColors = (isset($colors)) ? explode('|',$colors) : array('red', 'green', 'blue');

    $exp = $tExp [$flag];
    $color = $tColors[$flag];
    $fontSize = '16px';
    return "<span style='font-family: Arial Rounded MT Bold; font-size:{$fontSize}; color: {$color};'>{$exp}</span>";
}


