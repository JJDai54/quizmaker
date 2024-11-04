<?php
   /**
 * Name: modinfo.php
 * Description:
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package : XOOPS
 * @Module : 
 * @subpackage : Menu Language
 * @since 2.5.7
 * @author Jean-Jacques DELALANDRE (jjdelalandre@orange.fr)
 * @version {version}
 * Traduction:  
 */
 
defined( 'XOOPS_ROOT_PATH' ) or die( 'Accès restreint' );

//------------------------------------------------------------------
define('_LG_PLUGIN_CHOICESIMPLE', "Question à réponses multiples");
define('_LG_PLUGIN_CHOICESIMPLE_DESC', "Ce slide est composé d'une question et de plusieurs cases à cocher.");
define('_LG_PLUGIN_CHOICESIMPLE_CONSIGNE', "Vous devez cocher une ou plusieurs options qui correspondent à la question.<br>Important : Selon les options choisies le nombre de points peut augmenter ou diminuer selon les bonnes ou mauvaises options.");

define('_LG_PLUGIN_CHOICESIMPLE_TYPE', "Type de sélection");
define('_LG_PLUGIN_CHOICESIMPLE_TYPE_DESC', "");
define('_LG_PLUGIN_CHOICESIMPLE_TYPE_0', "Choix multiple");
define('_LG_PLUGIN_CHOICESIMPLE_TYPE_1', "Choix unique");
define('_LG_PLUGIN_CHOICESIMPLE_TYPE_2', "Choix unique et passage au slide suivant");

define('_LG_PLUGIN_CHOICESIMPLE_MSG_NEXT_SLIDE', "Message");
define('_LG_PLUGIN_CHOICESIMPLE_MSG_NEXT_SLIDE_DESC', "Message affiché si c'est une question à choix unique avec passage au slide suivant.");
define('_LG_PLUGIN_CHOICESIMPLE_MSGBG', "Couleur de fond du message.");

define('_LG_PLUGIN_CHOICESIMPLE_NEXT_QUESTION1', "Question suivante");
define('_LG_PLUGIN_CHOICESIMPLE_NEXT_QUESTION1_OPTIONS', _LG_PLUGIN_CHOICESIMPLE_NEXT_QUESTION1 . ",Question suivante,On passe à la suite,Persévérez");
?>
