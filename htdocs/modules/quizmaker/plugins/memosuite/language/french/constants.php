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
define('_LG_PLUGIN_MEMOSUITE', "Memorisez une suite d'image et reproduisez la !, ...");
define('_LG_PLUGIN_MEMOSUITE_DESC', "Il faut reproduire une suite d'mages qui s'affiche un bref moment.");
define('_LG_PLUGIN_MEMOSUITE_CONSIGNE', "Vous devez cliquer sur les pièces pour reproduire la suite d'images.");

define('_LG_PLUGIN_MEMOSUITE_NB_IMG', "Nombre d'images");      
define('_LG_PLUGIN_MEMOSUITE_NB_IMG_DESC', "Nombre d'image maximum de la suite à trouver.");   
   
define('_LG_PLUGIN_MEMOSUITE_OPTIONS_SEQUENCE', "Options de la séquence à trouver.");   
define('_LG_PLUGIN_MEMOSUITE_OPTIONS_GRILLE_SOURCE', "Options de la grille d'images source proposée.");   
define('_LG_PLUGIN_MEMOSUITE_OPTIONS_GRILLE_CIBLE', "Options de la grille d'images affichée.");   
define('_LG_PLUGIN_MEMOSUITE_OPTIONS_GAME', "Options du jeu et de son comportement.");   
define('_LG_PLUGIN_MEMOSUITE_TEMPO_SUITE', "Temporisation de la séquence.");   
define('_LG_PLUGIN_MEMOSUITE_TEMPO_SUITE_DESC', "Délai d'affichage en secondes entre chaque image de la séquence à trouver.");   
define('_LG_PLUGIN_MEMOSUITE_POINTS_BY_IMG', "Indiquez ici le nombre de points par image trouvée.<br>Si vous voulez affectez un nombre de points global au jeu, utilisez le champs \"Points\" plus haut dans le formulaire.");

define('_LG_PLUGIN_MEMOSUITE_SEQUENCE_HEIGHT', "Hauteur de la séquence de la séquence à trouver.");   
define('_LG_PLUGIN_MEMOSUITE_SEQUENCE_HEIGHT_DESC', "Hauteur en pixel ou s'affiche la suite d'image sélectionnée.");   


define('_LG_PLUGIN_MEMOSUITE_MODE', "Generation de la séquence");   
define('_LG_PLUGIN_MEMOSUITE_MODE_DESC', "");   
define('_LG_PLUGIN_MEMOSUITE_MODE_0', "Une seule génération avec possibilité de la revoir");   
define('_LG_PLUGIN_MEMOSUITE_MODE_1', "Une seule génération sans possibilité de la revoir");   
define('_LG_PLUGIN_MEMOSUITE_MODE_2', "Génération d'une séquence à chaque essai");   

/////////////////////////////////////////////////
define('_LG_PLUGIN_MEMOSUITE_MSG_READY_BTN', "Libellé du touton sur le slide");   
define('_LG_PLUGIN_MEMOSUITE_MSG_READY_BTN_DESC', "Message affiché sur le bouton qui permet de générer la séquence.");   
define('_LG_PLUGIN_MEMOSUITE_MSG_READY_BTN_0', "Générer et mémoriser la séquence à trouver !");   

define('_LG_PLUGIN_MEMOSUITE_MSG_READY', "Générer la nouvelle séquence séquence");   
define('_LG_PLUGIN_MEMOSUITE_MSG_READY_DESC', "Message d'avertissement pour générer la séquence à mémoriser.");   
define('_LG_PLUGIN_MEMOSUITE_MSG_READY_0', "Cliquez sur le bouton sous la grille pour pour générer//et mémoriser la séquence à trouver !");   

define('_LG_PLUGIN_MEMOSUITE_MSG_PLAYER_TURN', "Message \"A vous de jouer !\"");   
define('_LG_PLUGIN_MEMOSUITE_MSG_PLAYER_TURN_DESC', "Message affiché quand la séquence a été générée pour indiquer au joueur qu'il doit maintenant trouver la séquence.");   
define('_LG_PLUGIN_MEMOSUITE_MSG_PLAYER_TURN_0', "A vous de jouer !//Cliquez dans l'ordre d'apparition sur les {sequenceLength} images de la grille ");   

define('_LG_PLUGIN_MEMOSUITE_MSG_SUCCESS', "Message d'  avertissement en cas de victoire");   
define('_LG_PLUGIN_MEMOSUITE_MSG_SUCCESS_DESC', "Message affiché si le joueur a trouver la séquence.");   
define('_LG_PLUGIN_MEMOSUITE_MSG_SUCCESS_0', "Vous avez trouver la bonne séquence,//vous pouvez passer à la question suivante");   

define('_LG_PLUGIN_MEMOSUITE_MSG_GAME_FAILURE', "Message d'avertissement en cas d'échecu");   
define('_LG_PLUGIN_MEMOSUITE_MSG_GAME_FAILURE_DESC', "Message affiché lorsque la séquence est fausse.");   
define('_LG_PLUGIN_MEMOSUITE_MSG_GAME_FAILURE_0', "Perdu !//mais vous pouvez recommencez encore une fois !");   

define('_LG_PLUGIN_MEMOSUITE_MSG_NEXT_SLIDE_BTN', "Libellé du bouton en cas de victoire");   
define('_LG_PLUGIN_MEMOSUITE_MSG_NEXT_SLIDE_BTN_DESC', "Message affiché sur le bouton qui permet de générer la séquence.");   
define('_LG_PLUGIN_MEMOSUITE_MSG_NEXT_SLIDE_BTN_0', "Vous pouvez passer au slide suivant !");   
define('_LG_PLUGIN_MEMOSUITE_MSG_NEXT_SLIDE_BTN_1', "Slide suivant !");   
define('_LG_PLUGIN_MEMOSUITE_MSG_NEXT_SLIDE_BTN_2', "Cliquez sur le bouton Suivant pour continuer");   

/////////////////////////////////////////////////
/*
define('_LG_PLUGIN_MEMOSUITE_MSG_GETSEQENCE', "Générer la séquence");   
define('_LG_PLUGIN_MEMOSUITE_MSG_GETSEQENCE_DESC', "Message affiché si le joueur clique sur les images sans avoir généré la séquence.");   
define('_LG_PLUGIN_MEMOSUITE_MSG_GETSEQENCE_0', "Cliquez sur le bouton afin de générer la séquence à trouver !");   


define('_LG_PLUGIN_MEMOSUITE_MSG_NOSEQUENCE', "Générer d'abord la séquence");   
define('_LG_PLUGIN_MEMOSUITE_MSG_NOSEQUENCE_DESC', "Message affiché si le joueur clique sur les images sans avoir généré la séquence.");   
define('_LG_PLUGIN_MEMOSUITE_MSG_NOSEQUENCE_0', "La séquence n'a pas encore été générée//Cliquer sur le bouton pour la voir !");   


define('_LG_PLUGIN_MEMOSUITE_MSG_ATTEMPTSOUT', "Nombre d'essais atteind");   
define('_LG_PLUGIN_MEMOSUITE_MSG_ATTEMPTSOUT_DESC', "Message affiché lorsque le nombre d'essais est atteind.");   
define('_LG_PLUGIN_MEMOSUITE_MSG_ATTEMPTSOUT_0', "Perdu !//Vous avez atteind le nombre maximum d'essais.//Vous devez passer à la question suivante !");   


define('_LG_PLUGIN_MEMOSUITE_MSG_ATTEMPTSNUM', "Tentative");   
define('_LG_PLUGIN_MEMOSUITE_MSG_ATTEMPTSNUM_DESC', "Utilisé dans l'expression \"Essai n° num / max\".");   
define('_LG_PLUGIN_MEMOSUITE_MSG_ATTEMPTSNUM_0', "Essai n°");   
*/




?>
