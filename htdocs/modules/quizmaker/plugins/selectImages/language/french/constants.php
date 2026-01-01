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
define('_LG_PLUGIN_SELECTIMAGES', "Cliquer les bonnes images");
define('_LG_PLUGIN_SELECTIMAGES_DESC', "Ce slide est composé d'une séquence d'images dont une ou bien plusieurs peuvent est correctes, à l'instar des boutons radio ou des cases à cocher.");
define('_LG_PLUGIN_SELECTIMAGES_CONSIGNE', "Consigne à complèter");

define('_LG_PLUGIN_SELECTIMAGES_TYPE', "Type de sélection");
define('_LG_PLUGIN_SELECTIMAGES_TYPE_DESC', "");
define('_LG_PLUGIN_SELECTIMAGES_TYPE_CHECKBOX', "Choix multiple");
define('_LG_PLUGIN_SELECTIMAGES_TYPE_RADIO', "Choix unique");
define('_LG_PLUGIN_SELECTIMAGES_TYPE_2', "Choix unique et passage au slide suivant");

define('_LG_PLUGIN_SELECTIMAGES_VARIANT', "Style");
define('_LG_PLUGIN_SELECTIMAGES_VARIANT_IMAGE', "Image + coche");
define('_LG_PLUGIN_SELECTIMAGES_VARIANT_TEXTE', "Texte + coche");
//define('_LG_PLUGIN_SELECTIMAGES_VARIANT_DESC', "0 : affiche une image qu'il faut cliquer et le texte est placé sr l'image.<br>1 : affiche la coche qu'il faut cliquer et le text à côté");

define('_LG_PLUGIN_SELECTIMAGES_VARIANT_SELECT', "Sélectionnez une variante,  validez et rechargez le formulaire");
define('_LG_PLUGIN_SELECTIMAGES_VARIANT_DESC1', "Le principe est le même quelque soit la structure, il s'agit de trier une liste qui se présente sous différentes formes:"
. "<br><b>" . _LG_PLUGIN_SELECTIMAGES_VARIANT_IMAGE . "</b> : Image et coche"
. "<br><b>" . _LG_PLUGIN_SELECTIMAGES_VARIANT_TEXTE . "</b> : Texte et coche"
. "<br><span style='color:red;'><b>Important : </b>Valider cette option avant de passer à la suite des paramètres afin d'actualiser l'affichage selon l'option choisie.</span>"
. "<br><span style='color:red;'>Pour faire apparaitre les paramètres selon l'option choisie cliquez sur <b>\"soumettre et recharger la question\"</b>.</span>"
. "<br><span style='color:red;'>Il est toujours possible de changer ensuite mais au risque de devoir reparamètrer les nouvelles options qui n'étaient pas disponibles et de perdre les autres.</span>");

define('_LG_PLUGIN_SELECTIMAGES_VARIANT_DESC2', "Le principe est le même quelque soit la structure, il s'agit de trier une liste qui se présente sous différentes formes:"
. "<br><b>" . _LG_PLUGIN_SELECTIMAGES_VARIANT_IMAGE . "</b> : Image et coche"
. "<br><b>" . _LG_PLUGIN_SELECTIMAGES_VARIANT_TEXTE . "</b> : Texte et coche"
. "<br><span style='color:red;'>Il est toujours possible de changer ensuite mais au risque de devoir reparamètrer les nouvelles options qui n'étaient pas disponibles et de perdre les autres.</span>");
      
define('_LG_PLUGIN_SELECTIMAGES_IMG_HEIGHT', "Hauteur des images des propositions");
define('_LG_PLUGIN_SELECTIMAGES_COCHE', "Coche");
define('_LG_PLUGIN_SELECTIMAGES_COCHE_IMG_HEIGHT', "Hauteur des coches");
define('_LG_PLUGIN_SELECTIMAGES_IMG_TOP', "Position du libelle en hauteur");
define('_LG_PLUGIN_SELECTIMAGES_IMG_TOP_DESC', "Vers le haut < 50% > Vers le bas"); 

define('_LG_PLUGIN_SELECTIMAGES_DISPOSITION', "Disposition");
define('_LG_PLUGIN_SELECTIMAGES_DISPOSITION_DESC', "Chaque chiffre correspond au nombre de boutons sur chaque ligne.<br>exemple :123 = 1 bouton sur la première ligne, 2 boutons sur la deuxième ligne et 3 boutons sur la troisième ligne.<br>Sil il y a plus de boutons que la somme des chiffres, les derniers boutons seront tous sur la dernière ligne.");
define('_LG_PLUGIN_SELECTIMAGES_IMG1_OR_IMG2', "=> Ou =>");

define('_LG_PLUGIN_SELECTIMAGES_MSG_NEXT_SLIDE', "Message");
define('_LG_PLUGIN_SELECTIMAGES_MSG_NEXT_SLIDE_DESC', "Message affiché si c'est une question à choix unique avec passage au slide suivant.");
define('_LG_PLUGIN_SELECTIMAGES_MSGBG', "Couleur de fond du message.");

define('_LG_PLUGIN_SELECTIMAGES_NEXT_QUESTION1', "Question suivante");
define('_LG_PLUGIN_SELECTIMAGES_NEXT_QUESTION1_OPTIONS', _LG_PLUGIN_SELECTIMAGES_NEXT_QUESTION1 . ",On passe à la suite,Persévérez,Score cumulé : {score} / {scoreMaxi}");

define('_LG_PLUGIN_SELECTIMAGES_NEXT_SLIDE', "Message");        
define('_LG_PLUGIN_SELECTIMAGES_NEXT_SLIDE_DESC', "Message affiche lors du passage au slide suivant en mose automatique");        
define('_LG_PLUGIN_SELECTIMAGES_NEXT_SLIDE0', "Bravo !");        


define('_LG_PLUGIN_SELECTIMAGES_OPACITY', "Opacité des coches inactives");
define('_LG_PLUGIN_SELECTIMAGES_OPACITY_DESC', "0% = invisible - 50% = transparente - 100% = visible");
define('_AM_QUIZMAKER_PARAMS_COCHES', "Paramètre des coches");               
define('_AM_QUIZMAKER_PARAMS_OTHERS', "Autres paramètres");               
define('_LG_PLUGIN_SELECTIMAGES_INTERVAL_VERTICAL', "Interval vertical");               
define('_LG_PLUGIN_SELECTIMAGES_INTERVAL_VERTICAL_DESC', "Permet d'ajuster les intervals entre les items pour éviter l'affichage d'un barre de défilement.<br>La valeur peut-être négative, mais attention aux chevauchements.<br>0 = Automatique");
define('_LG_PLUGIN_SELECTIMAGES_TR_HEIGHT', "Hauteur des lignes");               
define('_LG_PLUGIN_SELECTIMAGES_TR_HEIGHT_DESC', "Permet d'ajuster la hauteur des items pour éviter l'affichage d'un barre de défilement.<br>0 = Automatique");

?>
