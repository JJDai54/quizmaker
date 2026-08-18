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
define('_LG_PLUGIN_MONKEY', "Memorisez une suite de valeurs aléatoires dans une grille.");
define('_LG_PLUGIN_MONKEY_DESC', "Il faut cliquer sur la grile dans l'ordre des valeurs.");
define('_LG_PLUGIN_MONKEY_CONSIGNE', "Vous devez cliquer sur les pièces pour reproduire la suite d'images.");

define('_LG_PLUGIN_MONKEY_GRID', "Taille de la grille.");

//-----------------------------------------------------
define('_LG_PLUGIN_MONKEY_MSG_ATYOU', "Avous de jouer");   
define('_LG_PLUGIN_MONKEY_MSG_ATYOU_DESC', "Message affichée dès que la grille est prete.");   
define('_LG_PLUGIN_MONKEY_MSG_ATYOU_0', "A vous de jouer !");   

define('_LG_PLUGIN_MONKEY_MSG_REPLAY', "Nombre de mouvement atteind");   
define('_LG_PLUGIN_MONKEY_MSG_REPLAY_DESC', "Le nombre d'essais autorisés est atteind, mais le joueur peut recommencer.");   
define('_LG_PLUGIN_MONKEY_MSG_REPLAY_0', "Raté. Il vous reste {nbAttempts} essais / {maxAttempts} !");   
                                                                              
define('_LG_PLUGIN_MONKEY_MSG_ATTEMPTS', "Nombre d'essais");   
define('_LG_PLUGIN_MONKEY_MSG_ATTEMPTS_DESC', "Compte le nombre d'essais effectués si masAttemptds == 0.");   
define('_LG_PLUGIN_MONKEY_MSG_ATTEMPTS_0', "Raté. Vous avez fait {nbAttempts} essais !");   

define('_LG_PLUGIN_MONKEY_KEEP_SAME_GRID', "Garder la même grille");   
define('_LG_PLUGIN_MONKEY_KEEP_SAME_GRID_DESC', "En cas d'échec du défi : <b>Oui</b> rejoue la même grille - <b>Non</b> génère une nouvelle grille.");  

?>
