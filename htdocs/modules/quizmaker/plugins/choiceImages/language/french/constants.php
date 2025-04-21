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
define('_LG_PLUGIN_CHOICEIMAGES', "Cliquer les bonnes images");
define('_LG_PLUGIN_CHOICEIMAGES_DESC', "Ce slide est composé d'une séquence d'images dont une ou bien plusieurs peuvent est correctes, à l'instar des boutons radio ou des cases à cocher.");
define('_LG_PLUGIN_CHOICEIMAGES_CONSIGNE', "Consigne à complèter");

define('_LG_PLUGIN_CHOICEIMAGES_TYPE', "Type de sélection");
define('_LG_PLUGIN_CHOICEIMAGES_TYPE_DESC', "");
define('_LG_PLUGIN_CHOICEIMAGES_TYPE_0', "Choix multiple");
define('_LG_PLUGIN_CHOICEIMAGES_TYPE_1', "Choix unique");
define('_LG_PLUGIN_CHOICEIMAGES_TYPE_2', "Choix unique et passage au slide suivant");

define('_LG_PLUGIN_CHOICEIMAGES_IMG_HEIGHT', "Hauteur des images des propositions");
define('_LG_PLUGIN_CHOICEIMAGES_COCHE', "Coche");
define('_LG_PLUGIN_CHOICEIMAGES_COCHE_IMG_HEIGHT', "Hauteur des coches");
define('_LG_PLUGIN_CHOICEIMAGES_IMG_TOP', "Position du libelle en hauteur");
define('_LG_PLUGIN_CHOICEIMAGES_FONT_SIZE', "Taille de la police (en em, defaut=1.1)");
define('_LG_PLUGIN_CHOICEIMAGES_FONT_SIZE_DESC', "Taille de la police");

define('_LG_PLUGIN_CHOICEIMAGES_DISPOSITION', "Disposition");
define('_LG_PLUGIN_CHOICEIMAGES_DISPOSITION_DESC', "Chaque chiffre correspond au nombre de boutons sur chaque ligne.<br>exemple :123 = 1 bouton sur la première ligne, 2 boutons sur la deuxième ligne et 3 boutons sur la troisième ligne.<br>Sil il y a plus de boutons que la somme des chiffres, les derniers boutons seront tous sur la dernière ligne.");
define('_LG_PLUGIN_CHOICEIMAGES_IMG1_OR_IMG2', "=> Ou =>");

define('_LG_PLUGIN_CHOICEIMAGES_MSG_NEXT_SLIDE', "Message");
define('_LG_PLUGIN_CHOICEIMAGES_MSG_NEXT_SLIDE_DESC', "Message affiché si c'est une question à choix unique avec passage au slide suivant.");
define('_LG_PLUGIN_CHOICEIMAGES_MSGBG', "Couleur de fond du message.");

define('_LG_PLUGIN_CHOICEIMAGES_NEXT_QUESTION1', "Question suivante");
define('_LG_PLUGIN_CHOICEIMAGES_NEXT_QUESTION1_OPTIONS', _LG_PLUGIN_CHOICEIMAGES_NEXT_QUESTION1 . ",On passe à la suite,Persévérez,Score cumulé : {score} / {scoreMaxi}");
?>
