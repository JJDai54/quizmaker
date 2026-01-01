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
define('_LG_PLUGIN_TEXTMIXTE', "Corriger le texte");
define('_LG_PLUGIN_TEXTMIXTE_DESC', "Ce slide est composé d'une zone de texte qu'il faut corriger directement.");
define('_LG_PLUGIN_TEXTMIXTE_CONSIGNE', "Consigne à complèter");

define('_LG_PLUGIN_TEXTMIXTE_PRESENTATION', "Présentation");
define('_LG_PLUGIN_TEXTMIXTE_PRESENTATION_TEXTAREA', "Zone de Texte simple à modifier directement. La liste des intrus n'est pas utilisée.");
define('_LG_PLUGIN_TEXTMIXTE_PRESENTATION_TEXTBOX', "Zone de texte avec autant de zones de saisies que de mots entre accolades. La liste des intrus n'est pas utilisée.");
define('_LG_PLUGIN_TEXTMIXTE_PRESENTATION_LISTBOX1', "Zone de texte avec autant de listes déroulantes qui comprennnent chacune tous les mots entre accolades plus la liste de tous les intrus (Propositions plus bas).");
define('_LG_PLUGIN_TEXTMIXTE_PRESENTATION_LISTBOX2', "Zone de texte avec autant de listes déroulantes qui comprennnent chacune uniquement le mot entre accolades et la liste des intrus correspopndant aux numero dans les proposition plus bas.");

define('_LG_PLUGIN_TEXTMIXTE_COMPARAISON', "Comparaison");
define('_LG_PLUGIN_TEXTMIXTE_COMPARAISON_0', "Comparaison strict (Mot exact avec accents)");
define('_LG_PLUGIN_TEXTMIXTE_COMPARAISON_1', "Comparaison avec Accents optionnels (ex: \"sérénade\" équivalent à \"serênade\" équivalent à \"serenade\")");
define('_LG_PLUGIN_TEXTMIXTE_COMPARAISON_2', "Comparaison souple (Mot contenu avec ou sans accents (ex: \"sérénade\", \"serênade\" sont équivalents à \"serenade\")");
define('_LG_PLUGIN_TEXTMIXTE_SCORE_BY_WORD', "Nombre de points par mot trouvé");
define('_LG_PLUGIN_TEXTMIXTE_ACCOLADES_ERR', "Le nombre des accollades ouvrantes et fermantes est différent.");
define('_LG_PLUGIN_TEXTMIXTE_REMOVE_ALERT', "Confirmez la suppression de toutes les accolades !");
define('_LG_PLUGIN_TEXTMIXTE_ADD_ACCOLADES', "Ajouter des acolades autour de la sélection.");
define('_LG_PLUGIN_TEXTMIXTE_REMOVE_ACCOLADES', "Retirer les accolades autour de la sélection");
define('_LG_PLUGIN_TEXTMIXTE_CLEAR_ALL_ACCOLADES', "Supprimer toutes les accolades.");

define('_LG_PLUGIN_TEXTMIXTE_ADD_BAD_EXP', "Ajouter des mots ou expressions parasites.<br>Ces expressions ont pour but de pertuber l'utilisateur.<br><b>Important</b> : cette liste n'est utilisée qu'avec les listes déroulantes (voir plus haut l'option \"Présentation\").");

define('_LG_PLUGIN_TEXTMIXTE_TOKEN_COLOR',"Couleur des balises");
define('_LG_PLUGIN_TEXTMIXTE_WORD_COLOR',"Couleur des mots choisis");
define('_LG_PLUGIN_TEXTMIXTE_LINE_HEIGHT',"Hauteur des interlignes (em)");
define('_LG_PLUGIN_TEXTMIXTE_TEXT_WIDTH',"Largeur de la zone de texte");
define('_LG_PLUGIN_TEXTMIXTE_INTRUS', "Liste de mots ou d'expressions qui seront ajoutées dans les listes déroulantes (voir \"Présentation\" voir plus haut)");         

define('_LG_PLUGIN_TEXTMIXTE_VARIANT', "Style");
define('_LG_PLUGIN_TEXTMIXTE_VARIANT_LISTBOX1', "Texte et Listes déroulantes commune");
define('_LG_PLUGIN_TEXTMIXTE_VARIANT_LISTBOX2', "Texte + listes déroulantes par expression");
define('_LG_PLUGIN_TEXTMIXTE_VARIANT_TEXTAREA',"Texte modifiable uniquement");
define('_LG_PLUGIN_TEXTMIXTE_VARIANT_TEXTEBOX', "Texte + zones de saisie");
define('_LG_PLUGIN_TEXTMIXTE_VARIANT_DESC0',  
  "<br><b>" . _LG_PLUGIN_TEXTMIXTE_VARIANT_LISTBOX1 . "</b> : Zone de texte avec autant de listes déroulantes que d'expressions entre accolades. Les listes sont communes"
. "<br><b>" . _LG_PLUGIN_TEXTMIXTE_VARIANT_LISTBOX2 . "</b> : Zone de texte avec autant de listes déroulantes que d'expressions entre accolades. Les listes sont propres à chaque expressions"
. "<br><b>" . _LG_PLUGIN_TEXTMIXTE_VARIANT_TEXTEBOX . "</b> :  Zone de texte avec autant de zonzs de saisie que d'expressions entre accolades"
. "<br><b>" . _LG_PLUGIN_TEXTMIXTE_VARIANT_TEXTAREA . "</b> : Zone de texte directement modifiable"
);

define('_LG_PLUGIN_TEXTMIXTE_VARIANT_SELECT', "Sélectionnez une variante,  validez et rechargez le formulaire");
define('_LG_PLUGIN_TEXTMIXTE_VARIANT_DESC1', "Le principe est le même quelque soit la structure, il s'agit de trier une liste qui se présente sous différentes formes:"
. _LG_PLUGIN_TEXTMIXTE_VARIANT_DESC0
. "<br><span style='color:red;'><b>Important : </b>Valider cette option avant de passer à la suite des paramètres afin d'actualiser l'affichage selon l'option choisie.</span>"
. "<br><span style='color:red;'>Pour faire apparaitre les paramètres selon l'option choisie cliquez sur <b>\"soumettre et recharger la question\"</b>.</span>"
. "<br><span style='color:red;'>Il est toujours possible de changer ensuite mais au risque de devoir reparamètrer les nouvelles options qui n'étaient pas disponibles et de perdre les autres.</span>");

define('_LG_PLUGIN_TEXTMIXTE_VARIANT_DESC2', "Le principe est le même quelque soit lr choix, il s'agit de corriger un texte qui se présente sous différentes formes:"
. _LG_PLUGIN_TEXTMIXTE_VARIANT_DESC0
. "<br><span style='color:red;'>Il est toujours possible de changer ensuite mais au risque de devoir reparamètrer les nouvelles options qui n'étaient pas disponibles et de perdre les autres.</span>");

define('_LG_PLUGIN_TEXTMIXTE_INIT_TEXT', "Initialise le texte");
define('_LG_PLUGIN_TEXTMIXTE_INIT_TEXT_DESC', "Initialise le texte en remplaçant les chiffres entre accolades par un des intrus");
define('_LG_PLUGIN_TEXTMIXTE_INIT_NONE', "Pas d'initialisation");
define('_LG_PLUGIN_TEXTMIXTE_INIT_FIRST', "Initialise avec le premier intrus de la liste");
define('_LG_PLUGIN_TEXTMIXTE_INIT_RND', "Initialisation aléatoire");

?>
