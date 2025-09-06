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
define('_LG_PLUGIN_FINDOBJECTS', "Retrouver les objects cachés");
define('_LG_PLUGIN_FINDOBJECTS_DESC', "Ce slide est composé d'une ou deux images. Il faut retrouver les objets cachés ou différents.");
define('_LG_PLUGIN_FINDOBJECTS_CONSIGNE', "Consigne à complèter");

define('_LG_PLUGIN_FINDOBJECTS_WIDTH', "Largeur de l'image %s");
define('_LG_PLUGIN_FINDOBJECTS_MAXTRY', "Nombre d'essais maximum");
define('_LG_PLUGIN_FINDOBJECTS_MAXTOUCHES', "Nombre de touches maximum");

define('_LG_FINDOBJECTS_NB_OBJETS', "Nombre d'objets");
define('_LG_FINDOBJECTS_DELETE', "X");
define('_LG_FINDOBJECTS_TITLE', "Titre");
define('_LG_FINDOBJECTS_COLOR', "Couleur");
define('_LG_FINDOBJECTS_LEFT', "Gauche");
define('_LG_FINDOBJECTS_TOP', "Haut");
define('_LG_FINDOBJECTS_WIDTH', "Largeur");
define('_LG_FINDOBJECTS_HEIGHT', "Hauteur");
define('_LG_FINDOBJECTS_BORDER_WIDTH', "Èpaisseur");
define('_LG_FINDOBJECTS_BORDER_RADIUS', "Arrondi");

define('_LG_FINDOBJECTS_TOUCHES_PARAMS', "Paramètres des touches (Validez et rechargez le formulaire pour prendre ces paramètres en compte)");
define('_LG_FINDOBJECTS_NEXT_SLIDE_PARAMS', "Paramètres des messages automatiques");
define('_LG_FINDOBJECTS_POINTS', "Points");
define('_LG_FINDOBJECTS_TEST_ClICK_OBJECTS', "Test des zones sensibles");
define('_LG_FINDOBJECTS_DISPOSITION_DESC', "La première image est obligatoire pour les objets cachés par exemple.<br>La deuxième image optionnelle sera utile pour le jeux des différences par exemple.<br>Si la deuxième  image'est pas définie, la première sera automatiquement chargée comme duxième image mais ne sera pas utilisée dans le quiz.<br>Elle sera utile pour tester les zones sensibles.");
define('_LG_FINDOBJECTS_IMAGES_ATTEMPTS_MAX_DESC', "Le nombre d'essais est limité pour éviter la triche en cliquant des dizaines de fois à la veuglette.<br>Il est important de définir cette valeur qui devrait être supérieure ou égale aux nombres de touches.<br>Bien que possible, Il n'est pas conseillé de définir une valeur inférieure au nombre de touches.<br>0 = Nombre d'essais limité au nombre de touches définies.<br>-1 = Nombre d'essais illimité.");
define('_LG_FINDOBJECTS_REFRESH_IMG_SIZES', "Appliquez la taille de l'image de référence");



define('_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_DELAI', "Delai d'affichage du slide suivant");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_DELAI_DESC', "<b>délai = 0</b> : l''utilisateur doit lui même appuyer sur le bouton suivant.<br><b>délai > 0</b> : le passage au slide suivant sera automatique dans deux cas : l'utilisatuer à tout bon, l'utilisatuer a épuisé le nombre de test possible.");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_MESSAGE', "Message");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_MESSAGE_DESC', "Message affiché lors du passge au slide suivant. Utilisé si le délai est égal à zéro");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_BG', "Couleur de fond du message");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDET_MESSAGE_DEFAULT', "Question suivante !");

define('_LG_PLUGIN_FINDSOBJECTS_NEXTSLIDE_AUTO', "Slide suivant automatique");
define('_LG_PLUGIN_FINDSOBJECTS_NEXTSLIDE_AUTO_DESC', "<b>Non</b> : L'utilisateur doit cliquer sur le bouton suivant.<br><b>Oui</b> : Le passage au slide suivant sera automatique dans deux cas :<br>1) Le nombre d'essais a été atteint,<br>2) Tous les objets ont été trouvés.");
define('_LG_PLUGIN_FINDOBJECTS_INFO', "Vous avez fait {total} essais sur {max}//dont {winning} objets trouvés sur {length} !");

define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN', "Message 1");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN_DESC', "Message affiché si l'utilisateur a trouvé tous les objets.");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN0', "Vous avez trouvé {winning} / {length} objets//Question suivante !");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN1', "Vous avez trouvé {winning} / {length} objets//On passe à la suite");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN2', "Vous avez fait {total}/{max} essais dont {winning} objets trouvés sur {length}//Question suivante");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN3', "Score cumulé : {score} / {scoreMaxi}");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN4', "Votre score cumulé est de {score}/{scoreMaxi}//Question suivante");

define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX', "Message 2");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX_DESC', "Message affiché si l'utilisateur a atteint le nombre d'essais maximums.");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX0', "Vous avez atteint le nombre d'essais maximum {max}//Question suivante !");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX1', "Vous avez atteint le nombre d'essais maximum {max} dont {winning} / {length} de bons//Question suivante !");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX2', "Vous avez fait {total}/{max} essais dont {winning} objets trouvés sur {length}//Question suivante !");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX3', "Score cumulé : {score} / {scoreMaxi}");
define('_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX4', "Votre score cumulé est de {score}/{scoreMaxi}//Question suivante");

define('_LG_FINDOBJECTS_MSG_JS_0',"s");
define('_LG_FINDOBJECTS_MSG_JS_1',"");
define('_LG_FINDOBJECTS_MSG_JS_2',"");
?>
