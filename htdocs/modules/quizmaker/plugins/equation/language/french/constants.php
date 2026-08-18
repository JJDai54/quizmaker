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
define('_LG_PLUGIN_EQUATION', "Résoudre une équation");
define('_LG_PLUGIN_EQUATION_DESC', "Ce jeu consiste à résoudre une équation en remplaçant des valeurs inconnues représentée avec des <b>\"?\"</b> par des valeurs proposées.");
define('_LG_PLUGIN_EQUATION_CONSIGNE', "Résolvez cette équation.<br>Action : Cliquez et déplacer les jetons du stock sur les valeurs inconues rpésentée par des <b>\"?\"</b>.<br>Important : il faut tenir compte de la priorité des opérateurs, la multiplication et la division sont toujours effectuées avant l'addition et la soustraction, sauf si celles-ci sont entre parenthèses.<br>Dans certain cas le signe <b>\"²\" est utilisé et repésente le carré d'un nombre ou d'une expression.</b>");
//-------------------------------------------

define('_LG_PLUGIN_PROPOSITION_EQUATION_OPERATEURS', "Opérateurs acceptés : \"+\", \"-\", \"*\", \"x\", \"/\", \":\", \"²\", \"**\", \"xx\" ");
define('_LG_PLUGIN_PROPOSITION_EQUATION_COMPATEURS', "Commparateurs acceptés : \"=\", \"<>\", \"!=\", \">\", \">=\", \"<\", \"<=\" ");
define('_LG_PLUGIN_PROPOSITION_EQUATION_EXEMPLE', "exemple : (2**3) +7 + {2} * 3 = 4² + {5}");
define('_LG_PLUGIN_PROPOSITION_EQUATION_TITLE', "Définissez les équations en encadrant les valeurs à trouver par des accolades.<br>Optionnel : Utilisez des espaces pour aérer l'équation.");
define('_LG_PLUGIN_PROPOSITION_EQUATION_DESC', _LG_PLUGIN_PROPOSITION_EQUATION_TITLE . "<br>" . _LG_PLUGIN_PROPOSITION_EQUATION_OPERATEURS . "<br>" . _LG_PLUGIN_PROPOSITION_EQUATION_COMPATEURS . "<br>" . _LG_PLUGIN_PROPOSITION_EQUATION_EXEMPLE);

define('_LG_PLUGIN_PROPOSITION_VALUES_DESC', "Listez les valeurs séparées par des <b>\"|\"</b> ou <b>\"/\"</b>.<br>Optionnel : Utilisez des espaces pour aérer la saisie.<br>Les valeurs à trouver seront automatiquement ajoutées à celles-ci");
 
define('_LG_PLUGIN_PROPOSITION_EQUATION', "Équations");
define('_LG_PLUGIN_PROPOSITION_VALUES', "Valeurs");

//define('_LG_PLUGIN_EQUATION_GRID_COLUMNS', "Nombre de cellules");
//define('_LG_PLUGIN_EQUATION_GRID_COLUMNS_DESC', "Nombre maximum d'élements de l'équation y compris les opérateurs.<br>Permet de bien répartir oérandes et opérateurs.");
define('_LG_PLUGIN_EQUATION_PADDING', "Marges intérieures");
define('_LG_PLUGIN_EQUATION_PADDINGDESC', "Permet de prendre en compte l'épaisseur des rebords de boite (selon le style utilié) pour placer l'équation correctement.");

define('_LG_PLUGIN_EQUATION_MAX_MOVEMENTS', "Maximum de mouvements");
define('_LG_PLUGIN_EQUATION_MAX_MOVEMENTS_DESC', "Nombre maximum de mouvements autorisés.<br>0 : le nombre de mouvements est illimité.<br> > 0 : doit être supérieur ou égal aux nombre de valeurs à trouver.");

define('_LG_PLUGIN_EQUATION_TOKEN_SIZE', "Taille des jetons");
define('_LG_PLUGIN_EQUATION_TOKEN_SIZE_DESC', "La taille de la police sera automatiquement ajustée en fonction de la taille des jetons.");

define('_LG_PLUGIN_EQUATION_TOKEN_COLOR', "Couleur de la police des jetons");
define('_LG_PLUGIN_EQUATION_SLOT_COLOR', "Couleur de la police des valeurs à trouver");
define('_LG_PLUGIN_EQUATION_MOVED_COLOR', "Couleur de la police des jetons déplacés");

