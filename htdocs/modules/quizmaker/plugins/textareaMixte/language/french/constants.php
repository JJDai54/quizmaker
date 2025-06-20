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
define('_LG_PLUGIN_TEXTAREAMIXTE', "Corriger le texte");
define('_LG_PLUGIN_TEXTAREAMIXTE_DESC', "Ce slide est composé d'une zone de texte qu'il faut corriger directement.");
define('_LG_PLUGIN_TEXTAREAMIXTE_CONSIGNE', "Consigne à complèter");

define('_LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION', "Présentation");
define('_LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION_TEXTAREA', "Zone de Texte simple à modifier directement");
define('_LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION_TEXTBOX', "Zone de texte avec autant de zones de saisies que de mots entre accolades");
define('_LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION_LISTBOX', "Zone de texte avec autant de listes déroulantes que de mots entre accolades avec en plus une liste de mots erronés.");

define('_LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON', "Comparaison");
define('_LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON_0', "Comparaison strict (Mot exact avec accents)");
define('_LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON_1', "Comparaison avec Accents optionnels (ex: \"sérénade\" équivalent à \"serênade\" équivalent à \"serenade\")");
define('_LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON_2', "Comparaison souple (Mot contenu avec ou sans accents (ex: \"sérénade\", \"serênade\" sont équivalents à \"serenade\")");
define('_LG_PLUGIN_TEXTAREAMIXTE_SCORE_BY_WORD', "Nombre de points par mot trouvé");
define('_LG_PLUGIN_TEXTAREAMIXTE_ACCOLADES_ERR', "Le nombre des accollades ouvrantes et fermantes est différent.");
define('_LG_PLUGIN_TEXTAREAMIXTE_REMOVE_ALERT', "Confirmez la suppression de toutes les accolades !");
define('_LG_PLUGIN_TEXTAREAMIXTE_ADD_ACCOLADES', "Ajouter des acolades autour de la sélection.");
define('_LG_PLUGIN_TEXTAREAMIXTE_REMOVE_ACCOLADES', "Retirer les accolades autour de la sélection");
define('_LG_PLUGIN_TEXTAREAMIXTE_CLEAR_ALL_ACCOLADES', "Supprimer toutes les accolades.");

define('_LG_PLUGIN_TEXTAREAMIXTE_ADD_BAD_EXP', "Ajouter des mots ou expressions parasites.<br>Ces expressions ont pour but de pertuber l'utilisateur.<br><b>Important</b> : cette liste n'est utilisée qu'avec les listes déroulantes (voir plus haut l'option \"Présentation\").");


?>
