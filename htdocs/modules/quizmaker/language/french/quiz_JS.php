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
 * @Module : Xoops FAQ
 * @subpackage : Menu Language
 * @since 2.5.7
 * @author John Neill
 * @version $Id: modinfo.php 0000 10/04/2009 09:08:46 John Neill $
 * Traduction: LionHell 
 * upgrade to xoops 2.5.10 by Jean-Jacques DELALANDRE
 */
 
defined( 'XOOPS_ROOT_PATH' ) or die( 'Accès restreint' );

define('_JS_QUIZMAKER_MESSAGE01', "Attention à bien lire la consigne, et le sens de tri car seul l'ordre exacte donne des points.");
define('_JS_QUIZMAKER_RESULTS', "Résultats");
define('_JS_QUIZMAKER_COMBOBOXSORTLIST', "Attention à l'ordre.");
define('_JS_QUIZMAKER_RADIO', "Attention, plusieurs répponses sont possibles mais les points diffèrent selon la réponse. Les moins évidentes ont le nombre de points le plus élevé");
define('_JS_QUIZMAKER_CHECKBOX', "Chaque réponse est associée à un nombre points selon la difficulté. Les réponses se cumulent");
define('_JS_QUIZMAKER_TEXTBOX', "Attention, plusieurs répponses sont possibles mais les points diffèrent selon la réponse. Les moins évidentes ont le nombre de points le plus élevé");
define('_JS_QUIZMAKER_ALLTYPE', "Important : Dans certains questionnaires il peut y avoir des réponses avc un nombre de points négatifs.");
define('_JS_QUIZMAKER_FORPOINTS', "Pour {pointsMin} à {pointsMax} points");
define('_JS_QUIZMAKER_FORCHRONO', " en {timer} secondes");
define('_JS_QUIZMAKER_FORPOINTSTIMER', "Pour {points} points en {timer} secondes");
define('_JS_QUIZMAKER_RESULTONSLIDE', "résultat : pour {reponses} réponses sur {questions} questions,<br>Votre score est de {points} points sur {totalPoints} points");
define('_JS_QUIZMAKER_BTNNEXT', "Suivant");
define('_JS_QUIZMAKER_BTNPREVIOUS', "Précédent");
define('_JS_QUIZMAKER_BTNSUBMIT', "Solutions");
define('_JS_QUIZMAKER_BTNCONTINUE', "Continuez");
define('_JS_QUIZMAKER_BTNRELOAD', "Recharger");
define('_JS_QUIZMAKER_BTNANTISECHE', "?");
define('_JS_QUIZMAKER_SHOWREPONSES', "Affichage pour le dev des réponses possibles (réponse ===> points)");
define('_JS_QUIZMAKER_BTNRELOADLIST', "Recharger la liste");
define('_JS_QUIZMAKER_BTNRELOADTEXT', "Recharger le texte");
define('_JS_QUIZMAKER_RESULTBRAVO0', "Votre résultat pour cette question :");
define('_JS_QUIZMAKER_RESULTBRAVO1', "Vous avez obtenu le score maximumn");
define('_JS_QUIZMAKER_RESULTBRAVO2', "Vous n'avez pas obtenu le score maximum");
define('_JS_QUIZMAKER_RESULTBRAVO3', "Vous avez obtenu le score minimum");
define('_JS_QUIZMAKER_RESULTSCORE', "Votre score est de {points} / {totalPoints} points");
define('_JS_QUIZMAKER_POINTS', "point(s)");
define('_JS_QUIZMAKER_BONNEREPONSE', "La bonne réponse pour {points} point(s) est :");
define('_JS_QUIZMAKER_TPLWORD', "<span style='color: red;'>{word}</span> ");
define('_JS_QUIZMAKER_TPLWORD2', "<span style='color: white;'>{word}</span> ");
define('_JS_QUIZMAKER_TPLREPONSETABLE', "<table>{content}</table>");
define('_JS_QUIZMAKER_TPLREPONSEDBLTABLE', "<table><tr>{content}</tr></table>");
define('_JS_QUIZMAKER_TPLREPONSEDBLTD', "<td>{content}</td>");
define('_JS_QUIZMAKER_TPLREPONSETD', "<tr><td>{word}</td><td>{sep}</td><td>{points}</td></tr>");
define('_JS_QUIZMAKER_TEXTBOXMULTIPLE', "Textes multiples");

?>