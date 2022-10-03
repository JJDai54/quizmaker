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

define('_CO_QUIZMAKER_TYPE_CHECKBOX_DESC', "Ce slide est composé d''une question et de plusieurs cases à cocher.");
define('_CO_QUIZMAKER_TYPE_PAGEINFO_DESC', "Ce silde a plusieurs fonctionalité: Page d'introduction, encart et résultats.<br>Le type de page est défini dans les options du slide.\"
. \"<br><b>Introduction</b> : à placer impérativement en premier, il permet de présenter le quiz.\"
. \"<br><b>Encart</b> : placé n'importe ou il permet de regroupper les questions par thème. Il faut définir dans les questions l'encart de regrouprement.<br>Le changement de poids (ordre) entraine toues les questions enfants.<br>Il peut également afficher des résultats intermédiaires\"
. \"<br><b>Résultat</b> : Obligatoirement placé à la fin, il permet d'afficher le résultat du quiz et de l'enregistrer.");
define('_CO_QUIZMAKER_TYPE_PAGEINFO', "Page d'infomation");

define('_CO_QUIZMAKER_TYPE_PNN', "<br>Chaque option est associée un nombre de points positif null ou négatif.");
define('_CO_QUIZMAKER_TYPE_PAC', "<br>Ni la ponctuation, ni l'accentuation ni la casse ne sont prises en compte pour comparer le résultat");
define('_CO_QUIZMAKER_TYPE_CHECKBOX', "Question à réponses multiples");

define('_CO_QUIZMAKER_TYPE_CHECKBOXLOGICAL', "Question de logique à réponses multiples");
define('_CO_QUIZMAKER_TYPE_CHECKBOXLOGICAL_DESC', "Ce slide est composé de plusieurs options qui ont des points communs.<br>Il faut cocher toutes les options qui ont également ces points communs.");
define('_CO_QUIZMAKER_TYPE_LISTBOXINTRUDERS1', "Chasser les intrus");
define('_CO_QUIZMAKER_TYPE_LISTBOXINTRUDERS1_DESC', "Ce slide est composé d'une liste dans la quelle il faut supprimer les intrus.<br>Pas de retour arrière.");
define('_CO_QUIZMAKER_TYPE_LISTBOXINTRUDERS2', "Déplacer les intrus (double liste)");
define('_CO_QUIZMAKER_TYPE_LISTBOXINTRUDERS2_DESC', "Ce slide est composé de deux listes.<br>Il faut écarter les intrus dans la deuxième liste.<br>Il est possible de corriger et de réintégrer les termes écartés.");
define('_CO_QUIZMAKER_TYPE_MATCHITEMS', "Associer les éléments deux à deux");
define('_CO_QUIZMAKER_TYPE_MATCHITEMS_DESC', "Ce slide es composé d'une liste de termes à la quelle il faut associer les termes d'une autre liste<br>Chaque teme de la deuxième liste est une liste mélangée des termes de la première liste.");
define('_CO_QUIZMAKER_TYPE_MULTITEXTBOX', "Questions multiples à réponses multiples.");
define('_CO_QUIZMAKER_TYPE_MULTITEXTBOX_DESC', "Ce slide est composé d'une ou plusieurs questions.<br>Chaque question peut avoir plusieurs réponses saisissables.");
define('_CO_QUIZMAKER_TYPE_RADIOLOGICAL', "Question de logique à réponse unique");
define('_CO_QUIZMAKER_TYPE_RADIOLOGICAL_DESC', "Ce slide est composé de deux listes :<br>- une liste de termes ayant des points communs<br>- une deuxième liste dont un seul terme possède les mêmes points communs");
define('_CO_QUIZMAKER_TYPE_RADIOMULTIPLE2', "Retrouver les termes qui vont ensemble");
define('_CO_QUIZMAKER_TYPE_RADIOMULTIPLE2_DESC', "Ce slide est composé de pliseurs liste avec un nombre identique de termes.<br>Il faut retouver les termes qui vont ensemble.<br>Plusierus solutions peuvent être proposées avec un nombre de points différent.");
define('_CO_QUIZMAKER_TYPE_RADIOSIMPLE', "Question à réponse unique");
define('_CO_QUIZMAKER_TYPE_RADIOSIMPLE_DESC', "Ce slide est composé de plusieurs réponses dont une seule peut être choisie.");
define('_CO_QUIZMAKER_TYPE_SORTCOMBOBOX', "Trier une liste multiple");
define('_CO_QUIZMAKER_TYPE_SORTCOMBOBOX_DESC', "Ce slide est composé de plusieurs liste constitué des même termes.<br>Il faut indiquer l'ordre précicé dans la question<br>L'ordre peut être inverse si l'option a été définie.");
define('_CO_QUIZMAKER_TYPE_TEXTAREA', "Corriger le texte");
define('_CO_QUIZMAKER_TYPE_TEXTAREA_DESC', "Ce slide est composé d'une zone de texte qu'il faut corriger directement.");
define('_CO_QUIZMAKER_TYPE_TEXTAREAINPUT', "Saisir les mots manquants");
define('_CO_QUIZMAKER_TYPE_TEXTAREAINPUT_DESC', "Ce slide est composé d'une zone de texte non saisissable et de plusieurs zones saisissables.<br>Il faut saisir les mots à remplacer dans le texte.<br>Les termes dans le texte ont été remplacé par des numéros entre accolades : {1} {2} {3} ... ");
define('_CO_QUIZMAKER_TYPE_TEXTAREALISTBOX', "Retrouver les mots manquants");
define('_CO_QUIZMAKER_TYPE_TEXTAREALISTBOX_DESC', "Ce slide est composé d'un texte et de pluseurs listes de mots.<br>Les mots manquants on été remplacéer par des numéros entre accolades : {1} {2} {3} ... <br>Chaque liste est constituées des mots a retrouver.<br>Il est possible d'ajouter des mots étrangés au texte");
define('_CO_QUIZMAKER_TYPE_LISTBOXSORTITEMS', "Trier une liste de termes");
define('_CO_QUIZMAKER_TYPE_LISTBOXSORTITEMS_DESC', "Ce slide est composé d'une liste et de boutons de déplacement.<br>Le tri peut être inverse.");

?>