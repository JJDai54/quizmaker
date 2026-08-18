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
define('_LG_PLUGIN_ALLUMETTES', "Allumettes");
define('_LG_PLUGIN_ALLUMETTES_DESC', "Jeux des allumettes. Ce jeu consiste à reproduire une figure géométrique en ne déplaçant que quelques allumlettes.");
define('_LG_PLUGIN_ALLUMETTES_CONSIGNE', "Déplacer le nombre d'allumettes indiqué pour obtenir la figure géométrique décrite.<br>Action possible :<br>Cliquer et déplacer une allumette.<br>Dans certains un clic droit ou gauche permet d'effectuer une rotation de l'allumette. devez déplacer les allumettes pour réaliser une nouvelle configuration.");
//---------------------------------------------------------------

define('_AM_QUIZMAKER_ROTATION', "Rotation");
define('_AM_QUIZMAKER_ROTATION_DESC', "Angle de rotation des allumettes");


 define ('_LG_PLUGIN_ALLUMETTES_GAME_WIDTH', "Largeur du plateau de jeu");     
 define ('_LG_PLUGIN_ALLUMETTES_GAME_HEIGHT', "Hauteur du plateau de jeu");     
 define ('_LG_PLUGIN_ALLUMETTES_GRID_SIZE', "Taile des mailles de la grille");     
 define ('_LG_PLUGIN_ALLUMETTES_ROTATION', "Rotation des allumettes");     
 define ('_LG_PLUGIN_ALLUMETTES_AL_WIDTH', "Largeur des allumettes");     
 define ('_LG_PLUGIN_ALLUMETTES_AL_HEIGHT', "Hauteur des allumettes");     

 define ('_LG_PLUGIN_ALLUMETTES_SAVE', "Mémoriser");     
 define ('_LG_PLUGIN_ALLUMETTES_SHOW', "Montrer"); 
 define ('_LG_PLUGIN_ALLUMETTES_SAVE_DEFI', "Enregistrer le Défi");     
 define ('_LG_PLUGIN_ALLUMETTES_SAVE_SOLUTION', "Enregistrer la Solution");     
 define ('_LG_PLUGIN_ALLUMETTES_SAVE_DEFI_OK', "Le défi est enregistré");     
 define ('_LG_PLUGIN_ALLUMETTES_SAVE_SOLUTION_OK', "La solution est enregistrée");     
 define ('_LG_PLUGIN_ALLUMETTES_RESTAURE_DEFI', "Restaurer le défi");     
 define ('_LG_PLUGIN_ALLUMETTES_RESTAURE_SOLUTION', "Restaurer la solution");     
 define ('_LG_PLUGIN_ALLUMETTES_RESTAURE', "Restaurer");     
 define ('_LG_PLUGIN_ALLUMETTES_DEFI', "Défi");     
 
 define ('_LG_PLUGIN_ALLUMETTES_ADD', "Ajouter une allumette");     
 define ('_LG_PLUGIN_ALLUMETTES_ADD_MOBILE', "Ajouter une allumette mobile");     
 define ('_LG_PLUGIN_ALLUMETTES_ADD_FIXE', "Ajouter une allumette fixe");     
 define ('_LG_PLUGIN_ALLUMETTES_DELETE_OFF', "Mode suppression (Off)");     
 define ('_LG_PLUGIN_ALLUMETTES_DELETE_ON', "Mode suppression (On)");     
 define ('_LG_PLUGIN_ALLUMETTES_RESET_ROTATION', "Redresser les allumettes");     
 define ('_LG_PLUGIN_ALLUMETTES_APPLY_CHANGE', "Appliquer les changements");     
 define ('_LG_PLUGIN_ALLUMETTES_ROTATION2', "Rotation");     
 define ('_LG_PLUGIN_ALLUMETTES_MEMORY', "Mémoire");     
 define ('_LG_PLUGIN_ALLUMETTES_MOVE', "Déplacer");     
 define ('_LG_PLUGIN_ALLUMETTES_LEFT', "Gauche");     
 define ('_LG_PLUGIN_ALLUMETTES_RIGHT', "Droite");     
 define ('_LG_PLUGIN_ALLUMETTES_TOP', "Haut");     
 define ('_LG_PLUGIN_ALLUMETTES_BOTTOM', "Bas");     
 define ('_LG_PLUGIN_ALLUMETTES_MEMORY1_UNDFINED', "Mémoire 1 non définie");    
 define ('_LG_PLUGIN_ALLUMETTES_SOLUTION', "Solution");     
 define ('_LG_PLUGIN_ALLUMETTES_SOLUTIONS', "Solutions");     
 define ('_LG_PLUGIN_ALLUMETTES_TEST_SOLUTIONS', "Tester les solutions");     
 define ('_AP_QUIZMAKER_ALLOW_ROTATION', "Autoriser la rotation");     
 define ('_AP_QUIZMAKER_ALLOW_ROTATION_DESC', "Autorise ou non le joueur à appliquer une rotation sur les allumettes.<br>Cette option est utile si les alumettes sont dans la même position dans le défi et les solutions.");     
 
 define ('_LG_PLUGIN_ALLUMETTES_MAX_ALTO_MOVE', "Maximum d'allumette a déplacer");     
 define ('_LG_PLUGIN_ALLUMETTES_MAX_ALTO_MOVE_DESC', "0 = les déplacement sont illimité (pour le dev)<br> > 0 : nombre limité de déplacement. Si il est déplacé, revient à la position d'origine.");     
 define ('_LG_PLUGIN_ALLUMETTES_RECALER_SUR_LA_GRILLE', "Recaler les allumettes sur la grille");     

define('_LG_PLUGIN_ALLUMETTES_COUNT', "Nombre d'allumettes");   
define('_LG_PLUGIN_ALLUMETTES_DELETE', "Supprimer");   
define('_LG_PLUGIN_ALLUMETTES_HIDDE_FIXE', "Cacher les allumettes fixes");   

define('_LG_PLUGIN_ALLUMETTES_ALLUMETTES_TO_ADD', "Allumettes à ajouter");   
define('_LG_PLUGIN_ALLUMETTES_ALLUMETTES_TO_ADD_DESC', "Nombre d'allumettes manquantes que Le joueur peut ajouter sur le plateau");   
define('_LG_PLUGIN_ALLUMETTES_ADD_ALLUMETTES', "Allumettes à ajouter");   

define('_LG_PLUGIN_ALLUMETTES_GOOD', "Bingo la solution {idSolution} correspond");   
define('_LG_PLUGIN_ALLUMETTES_BAD', "PAs de chance aucune solution ne correspond");   
define('_LG_PLUGIN_ALLUMETTES_RECALAGE_OK', "{nbAllumettes} allumettes vérifiées, aucune correction.");   
define('_LG_PLUGIN_ALLUMETTES_RECALAGE_DONE', "{nbAllumettes} allumettes vérifiées, {nbCorrections} correction(s)."); 
define('_LG_PLUGIN_ALLUMETTES_CONFIRM_DELETE', "Êtes vous sur de vouloir supprimer cet élément ?"); 


define('_LG_PLUGIN_ALLUMETTES_TITLE_PLATEAU_WIDTH', "Largeur du plateau"); 
define('_LG_PLUGIN_ALLUMETTES_TITLE_PLATEAU_HEIGHT', "Hauteur du plateau"); 
define('_LG_PLUGIN_ALLUMETTES_TITLE_GRID_SIZE', "Taille des mailles la grille"); 
define('_LG_PLUGIN_ALLUMETTES_TITLE_ALLUMETTES_WIDTH', "Largeur des allumettes"); 
define('_LG_PLUGIN_ALLUMETTES_TITLE_ALLUMETTES_HEIGHT', "Hauteur des allumettes"); 
define('_LG_PLUGIN_ALLUMETTES_TITLE_ALLUMETTES_ROT', "Angle de rotation des allumettes"); 
 
define('_LG_PLUGIN_ALLUMETTES_MAXPROPOSITIONS', "Nombre maximum de solutions");
define('_LG_PLUGIN_ALLUMETTES_MAXPROPOSITIONS_DESC', "Si le nombre de solutions possibles n'est pas sufisant il est possible de l'augmeter.<br>Il faudra dans ce cas soummettre et recharger le slide pour prendre en compte cette nouvelle valeur.");
   

//-----------------------------------------------------
define('_LG_PLUGIN_ALLUMETTES_MSG_REPLAY', "Nombre de mouvement atteind");   
define('_LG_PLUGIN_ALLUMETTES_MSG_REPLAY_DESC', "Le nombre de mouvemants autorisés est atteind, mais le joueur peut recommencer.");   
define('_LG_PLUGIN_ALLUMETTES_MSG_REPLAY_0', "Vous avez dépasser le nombre  autorisés d'allumettes à déplacer. Vous devez recommencer !");   

define('_LG_PLUGIN_ALLUMETTES_MSG_REMAINING', "Nombre d'essais restants");   
define('_LG_PLUGIN_ALLUMETTES_MSG_REMAINING_DESC', "Indique à l'utilisateur le nombre d'essais restants.");   
define('_LG_PLUGIN_ALLUMETTES_MSG_REMAINING_0', "Il vous reste {nbAttempts} essai(s).");   

define('_LG_PLUGIN_ALLUMETTES_MSG_ADDALLUMETTES', "Ajouter des allumettes");   
define('_LG_PLUGIN_ALLUMETTES_MSG_ADDALLUMETTES_DESC', "Libellé du bouton qui permet d'ajouter des allumettes.");   
define('_LG_PLUGIN_ALLUMETTES_MSG_ADDALLUMETTES_0', "Ajouter des allumettes");   

define('_LG_PLUGIN_ALLUMETTES_MSG_DELALLUMETTES', "Supprimer des allumettes");   
define('_LG_PLUGIN_ALLUMETTES_MSG_DELALLUMETTES_DESC', "Libelle du bouton de suppression de la dernière allumette.");   
define('_LG_PLUGIN_ALLUMETTES_MSG_DELALLUMETTES_0', "Supprimez des allumettes !");   

  
?>



  
