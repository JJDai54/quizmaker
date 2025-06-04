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
define('_LG_PLUGIN_SORTITEMS', "Trier une liste d'expressions ou d'images");
define('_LG_PLUGIN_SORTITEMS_DESC', "Ce slide est composé d'une liste et de boutons de déplacement.<br>Le tri peut être inverse.");
define('_LG_PLUGIN_SORTITEMS_CONSIGNE', "Vous devez trier la liste selon les critères énoncés dans la question.<br>Sélectionnez un item puis cliquez sur les boutons latéraux pour le déplacer vers le haut ou vers le bas.<br>Selon les options choisies, la liste doit être triée en ordre strict ou non, c'est à dire que l'ordre peut être inverse.");

define('_LG_PLUGIN_SORTITEMS_OPTIONS_LISTBOX', "Options spécifiques aux listes de sélection unique (listbox)");
define('_LG_PLUGIN_SORTITEMS_OPTIONS_LISTUL', "Options spécifiques aux listes à puces (Drag and Drop de libellés)");
define('_LG_PLUGIN_SORTITEMS_OPTIONS_DADIMAGE', "Options spécifiques aux images a déplacer (Drag and Drop d'images)");

define('_LG_PLUGIN_SORTITEMS_BTN_HEIGHT', "Hauteur des boutons de déplacement");

define('_LG_PLUGIN_SORTITEMS_CLASSE', "Classe");
define('_LG_PLUGIN_SORTITEMS_CLASSE_LISTBOX', "Listbox");
define('_LG_PLUGIN_SORTITEMS_CLASSE_COMBOBOX', "Combobox");
define('_LG_PLUGIN_SORTITEMS_CLASSE_LISTUL', "Liste à puce");
define('_LG_PLUGIN_SORTITEMS_CLASSE_IMAGEDAD', "Images");

define('_LG_PLUGIN_SORTITEMS_CLASSE_SELECT', "Sélectionnez une classe et validez le formulaire");
define('_LG_PLUGIN_SORTITEMS_CLASSE_DESC', "Le principe est le même quelque soit la structure, il s'agit de trier une liste qui se présente sous différentes formes:"
. "<br><b>" . _LG_PLUGIN_SORTITEMS_CLASSE_LISTBOX . "</b> : Liste d'expressions avec des boutons de déplacement"
. "<br><b>" . _LG_PLUGIN_SORTITEMS_CLASSE_COMBOBOX . "</b> : Autant de listes déroulantes que d'expresions à trier remplies avec toutes les expressions"
. "<br><b>" . _LG_PLUGIN_SORTITEMS_CLASSE_LISTUL . "</b> : Liste à puces à replacer en ordre en cliquant et déplaçant les expressions (Drag and Drop libellés)"
. "<br><b>" . _LG_PLUGIN_SORTITEMS_CLASSE_IMAGEDAD . "</b> : Images à replacer en ordre en cliquant et les déplaçant (Drag and Drop images)"
. "<br><span style='color:red;'><b>Important : </b>Valider cette option avant de passer à la suite des paramètres afin d'actualiser l'affichage selon l'option choisie.</span>"
. "<br><span style='color:red;'>Pour faire apparaitre les paramètre selon l'option chisie cliquez sur <b>\"soumettre et recharger la question\"</b>.</span>"
. "<br><span style='color:red;'>Il est toujours possible de changer ensuite mais au risque de devoir reparamètrer les nouvelles options qui n'étaient pas disponibles et de perdre les autres.</span>");

define('_LG_PLUGIN_SORTITEMS_DIRECTIVE', "Directive");
define('_LG_PLUGIN_SORTITEMS_DIRECTIVE_DESC', "Action à faire affichée sur le slide.");
define('_LG_PLUGIN_SORTITEMS_DIRECTIVE_LIB', "Cliquez sur les images et déplacez les pour les mettre dans l'ordre.");
define('_LG_PLUGIN_SORTITEMS_IMG1_HEIGHT', "Hauteur des images à déplacer");
define('_LG_PLUGIN_SORTITEMS_IMG2_HEIGHT', "Hauteur des images de référence");

define('_LG_PLUGIN_SORTITEMS_FLIP', "Echange les images");
define('_LG_PLUGIN_SORTITEMS_INSERT', "Insert et décale les images sans carret");
define('_LG_PLUGIN_SORTITEMS_CARRET', "Insert et décale les images avec carret");

define('_LG_PLUGIN_SORTITEMS_IMAGE_TO_SORT', "Images à ordonner");
define('_LG_PLUGIN_SORTITEMS_IMAGE_REFERANTE', "Images de référence");

?>
