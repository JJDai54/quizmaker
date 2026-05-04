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
define('_LG_PLUGIN_PUZZLE', "Puzzle");
define('_LG_PLUGIN_PUZZLE_DESC', "Ce slide est composé d'une image découpée et mélangée qu'il faut reconstituer.");
define('_LG_PLUGIN_PUZZLE_CONSIGNE', "Vous devez déplacer les pièces pour reconstituer le puzzle.");

define('_LG_PLUGIN_PUZZLE_IMG_WIDTH', "Largeur du jeu");
define('_LG_PLUGIN_PUZZLE_ROWS', "Nombre de lignes");
define('_LG_PLUGIN_PUZZLE_ROWS_DESC', "Nombre de lignes de découpage de l'images");
define('_LG_PLUGIN_PUZZLE_COLS', "Nombre de colonnes");
define('_LG_PLUGIN_PUZZLE_COLS_DESC', "Nombre de pièces par ligne");
define('_LG_PLUGIN_PUZZLE_MODE', "Mode de déplacement des pièces");
define('_LG_PLUGIN_PUZZLE_MODE_DESC', "0 = Le pièces sont échangées sans décaler les pièces intermédiaires<br>1 = les pièces sont insérées et décalent toutes les pièces intermèdiaires");
define('_LG_PLUGIN_PUZZLE_MODE0', "Echange les pièces");
define('_LG_PLUGIN_PUZZLE_MODE1', "Insert les pièces");
define('_LG_PLUGIN_PUZZLE_BACKGROUND', "Couleur de fond");
define('_LG_PLUGIN_PUZZLE_MARGE', "Marge");
define('_LG_PLUGIN_PUZZLE_MARGE_DESC', "Défini les marges en pixels pour chaque pièces.<br><b> Important</b> : A l'affichage l'intervalle entre chaque pièce sera doublée");
define('_LG_PLUGIN_PUZZLE_BORDER_RADIUS', "Arrondi des pîèces");

define('_LG_PLUGIN_PUZZLE_VARIANT', "Jeux");
define('_LG_PLUGIN_PUZZLE_PUZZLE', "Puzzle");
define('_LG_PLUGIN_PUZZLE_TAQUIN', "Taquin");
define('_LG_PLUGIN_PUZZLE_MEMORY', "Mémoire");
define('_LG_PLUGIN_PUZZLE_LUCIOLES', "Lucioles");

define('_LG_PLUGIN_PUZZLE_PREVIEW', "Pré-visualisation");
define('_LG_PLUGIN_PUZZLE_PREVIEW_DESC', "Délai d'affichage en secondes de toutes les images en début de jeu.<br>0 = pas d'affichage de pré-visualisation des images.");
define('_LG_PLUGIN_PUZZLE_TEMPO', "Temporisation");
define('_LG_PLUGIN_PUZZLE_TEMPO_DESC', "Délai en secondes avant masquage des images quand elles ne sont pas identiques.<br>0 = pas de temporisation, les images restent affichées jusqu'au prochain click souris.");

define('_LG_PLUGIN_PUZZLE_DOUBLONS', "Doublons");      
define('_LG_PLUGIN_PUZZLE_DOUBLONS_DESC', "Nombre d'image à décourir pour trouver tous les doublons d'une même image.");      
define('_LG_PLUGIN_PUZZLE_IMG_GRID', "Grille de l'image");      
define('_LG_PLUGIN_PUZZLE_GAME_GRID', "Grille du jeu");      
define('_LG_PLUGIN_PUZZLE_GRID_COLS_DESC', "Le nombre de lignes de la grile sera déduite du nombre d'images du nombre de doublons et du nombre de colonnes de la grille.<br>Nombre ligne = (Nombre d'images * doublons) / Nombre de colonnes de la grille.<br>Pour une présentation optimum, chaque ligne doit contenir le même nombre de colonnes.");      
define('_LG_PLUGIN_PUZZLE_OPTIONS', "Options spécifiques");
define('_LG_PLUGIN_PUZZLE_MEMORY_DESC', "Le produit du nombre de lignes et de colonnes doit être un multiple du nombre de lignes et de colonnes de l'image supérieur ou égal à 2 et inférieur ou égal à 5."
      . "<br>Exemple :  grille de l'imgage : (2 x 3) * 3 => grille du jeu : 3 x 6");      
define('_LG_PLUGIN_PUZZLE_POINTS_BY_IMG', "Indiquez ici le nombre de points par image trouvée.<br>Si vous voulez affectez un nombre de points global au jeu, utilisez le champs \"Points\" plus haut dans le formulaire.");
      
define ('_LG_PLUGIN_ROTATION', "Rotation des pièces");
// define ('_LG_PLUGIN_ROTATION_NONE', "Pas de rotation");
// define ('_LG_PLUGIN_ROTATION_090', "Rotation à 0°, 90°, 180° et 270°");
// define ('_LG_PLUGIN_ROTATION_180', "Rotation à 0° et 180° uniquement");
define ('_LG_PLUGIN_ROTATION_DESC', "Cette option permet lors du mélange des pièces, de leur appliquer une rotaarion pour augmenter la difficulté du jeu.<br>Un click sur l'image permer d'effectuer la rotation des pièces");

?>
