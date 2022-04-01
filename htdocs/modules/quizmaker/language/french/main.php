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

define('_MA_QUIZMAKER_INDEX', "Accueil");
define('_MA_QUIZMAKER_TITLE', "Quiz");
define('_MA_QUIZMAKER_DESC', "Ce module permet de créer des quiz et des QCM");
define('_MA_QUIZMAKER_INDEX_DESC', "Bienvenue sur la page d'accueil de votre nouveau module QuizMaker !<br>

Comme vous pouvez le voir, vous avez créé une page avec une liste de liens en haut pour naviguer entre les pages de votre module. Cette description n'est visible que sur la page d'accueil de ce module, les autres pages vous verrez le contenu que vous avez créé lorsque vous avez construit ce module avec le module ModuleBuilder, et après avoir créé un nouveau contenu dans l'admin de ce module. Afin d'étendre ce module avec d'autres ressources, ajoutez simplement le code dont vous avez besoin pour étendre les fonctionnalités de celui-ci. Les fichiers sont regroupés par type, de l'en-tête au pied de page pour voir comment se divise le code source.<br><br>Si vous voyez ce message, c'est que vous n'avez pas créé de contenu pour ce module. Une fois que vous avez créé n'importe quel type de contenu, vous ne verrez plus ce message.<br><br>Si vous avez aimé le module ModuleBuilder et grâce au long processus pour donner la possibilité au nouveau module d'être créé en un instant, pensez faire un don pour conserver le module ModuleBuilder et faire un don en utilisant ce bouton <a href='http://www.txmodxoops.org/modules/xdonations/index.php' title='Donation To Txmod Xoops'><img src ='https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif' alt='Dons de boutons' /></a><br>Merci !<br><br>Utilisez le lien ci-dessous pour allez à l'administrateur et créez du contenu.");
define('_MA_QUIZMAKER_NO_PDF_LIBRARY', "Les bibliothèques TCPDF ne sont pas encore là, téléchargez-les dans root/Frameworks");
define('_MA_QUIZMAKER_NO', "Non");
define('_MA_QUIZMAKER_DETAILS', "Afficher les détails");
define('_MA_QUIZMAKER_BROKEN', "Notifier cassé");
define('_MA_QUIZMAKER_QUIZ', "Quiz");
define('_MA_QUIZMAKER_QUIZ_TITLE', "Titre du quiz");
define('_MA_QUIZMAKER_QUIZ_DESC', "Description du quiz");
define('_MA_QUIZMAKER_QUIZ_LIST', "Liste des quiz");
define('_MA_QUIZMAKER_QUIZ_ID', "Identifiant");
define('_MA_QUIZMAKER_QUIZ_CAT_ID', "Cat_id");
define('_MA_QUIZMAKER_QUIZ_NAME', "Nom");
define('_MA_QUIZMAKER_QUIZ_DESCRIPTION', "La description");
define('_MA_QUIZMAKER_QUIZ_CREATION', "Création");
define('_MA_QUIZMAKER_QUIZ_UPDATE', "Mettre à jour");
define('_MA_QUIZMAKER_QUIZ_COMMENTS', "commentaires");
define('_MA_QUIZMAKER_QUIZ_DATEBEGIN', "DateDébut");
define('_MA_QUIZMAKER_QUIZ_DATEEND', "DateFin");
define('_MA_QUIZMAKER_QUIZ_ONCLICK', "Sur clic");
define('_MA_QUIZMAKER_QUIZ_THEME', "Thème");
define('_MA_QUIZMAKER_QUIZ_ANSWERBEFORENEXT', "RépondreAvantSuivant");
define('_MA_QUIZMAKER_QUIZ_ALLOWEDPREVIOUS', "AutoriséPrécédent");
define('_MA_QUIZMAKER_QUIZ_ALLOWEDSUBMIT', "AutoriséEnvoyer");
define('_MA_QUIZMAKER_QUIZ_TIMER', "Minuteur");
define('_MA_QUIZMAKER_QUIZ_SHOWRESULTALLWAYS', "AfficherRésultatTous");
define('_MA_QUIZMAKER_QUIZ_SHOWREPONSES', "Afficher les réponses");
define('_MA_QUIZMAKER_QUIZ_SHOWLOG', "Afficher le journal");
define('_MA_QUIZMAKER_QUIZ_LEGEND', "Légende");
define('_MA_QUIZMAKER_QUIZ_DATEBEGINOK', "Date Début Ok");
define('_MA_QUIZMAKER_QUIZ_DATEENDOK', "Date Fin Ok");
define('_MA_QUIZMAKER_QUESTIONS', "Questions");
define('_MA_QUIZMAKER_QUESTIONS_TITLE', "Titre des questions");
define('_MA_QUIZMAKER_QUESTIONS_DESC', "Description des questions");
define('_MA_QUIZMAKER_QUESTIONS_LIST', "Liste de questions");
define('_MA_QUIZMAKER_QUESTIONS_ID', "Identifiant");
define('_MA_QUIZMAKER_QUESTIONS_QUIZ_ID', "Quiz_id");
define('_MA_QUIZMAKER_QUESTIONS_QUESTION', "Question");
define('_MA_QUIZMAKER_QUESTIONS_COMMENT1', "Commentaire1");
define('_MA_QUIZMAKER_QUESTIONS_TYPE_QUESTION', "Type_question");
define('_MA_QUIZMAKER_QUESTIONS_MINREPONSE', "MinRéponse");
define('_MA_QUIZMAKER_QUESTIONS_CREATION', "Création");
define('_MA_QUIZMAKER_QUESTIONS_UPDATE', "Mettre à jour");
define('_MA_QUIZMAKER_QUESTIONS_COMMENT2', "Commentaire2");
define('_MA_QUIZMAKER_CATEGORIES', "Catégories");
define('_MA_QUIZMAKER_CATEGORIES_TITLE', "Titre des catégories");
define('_MA_QUIZMAKER_CATEGORIES_DESC', "Description des catégories");
define('_MA_QUIZMAKER_CATEGORIES_LIST', "Liste des catégories");
define('_MA_QUIZMAKER_CATEGORIES_ID', "Identifiant");
define('_MA_QUIZMAKER_CATEGORIES_NAME', "Nom");
define('_MA_QUIZMAKER_CATEGORIES_DESCRIPTION', "La description");
define('_MA_QUIZMAKER_CATEGORIES_CREATION', "Création");
define('_MA_QUIZMAKER_CATEGORIES_UPDATE', "Mettre à jour");
define('_MA_QUIZMAKER_TYPEDEQUESTION', "Typedequestion");
define('_MA_QUIZMAKER_TYPE_QUESTION', "Type_question");
define('_MA_QUIZMAKER_TYPE_QUESTION_TITLE', "Type_titre de la question");
define('_MA_QUIZMAKER_TYPE_QUESTION_DESC', "Type_question description");
define('_MA_QUIZMAKER_TYPE_QUESTION_LIST', "Liste des Type_question");
define('_MA_QUIZMAKER_TYPEDEQUESTION_ID', "Identifiant");
define('_MA_QUIZMAKER_TYPEDEQUESTION_NAME', "Nom");
define('_MA_QUIZMAKER_TYPEDEQUESTION_DESCRIPTION', "La description");
define('_MA_QUIZMAKER_TYPEDEQUESTION_SHORTDESC', "Description courte");
define('_MA_QUIZMAKER_ANSWERS', "Réponses");
define('_MA_QUIZMAKER_ANSWERS_TITLE', "Titre des réponses");
define('_MA_QUIZMAKER_ANSWERS_DESC', "Description des réponses");
define('_MA_QUIZMAKER_ANSWERS_LIST', "Liste de réponses");
define('_MA_QUIZMAKER_ANSWERS_ID', "Identifiant");
define('_MA_QUIZMAKER_ANSWERS_QUESTION_ID', "ID_question");
define('_MA_QUIZMAKER_ANSWERS_LETTER', "Lettre");
define('_MA_QUIZMAKER_ANSWERS_PROPOSITION', "Proposition");
define('_MA_QUIZMAKER_ANSWERS_POINTS', "Points");
define('_MA_QUIZMAKER_RESULTS', "Résultats");
define('_MA_QUIZMAKER_RESULTS_TITLE', "Titre des résultats");
define('_MA_QUIZMAKER_RESULTS_DESC', "Description des résultats");
define('_MA_QUIZMAKER_RESULTS_LIST', "Liste des résultats");
define('_MA_QUIZMAKER_RESULTS_ID', "Id");
define('_MA_QUIZMAKER_RESULTS_QUIZ_ID', "Quiz_id");
define('_MA_QUIZMAKER_RESULTS_UID', "Uid");
define('_MA_QUIZMAKER_RESULTS_SCORE', "Score");
define('_MA_QUIZMAKER_RESULTS_SOREMAX', "Soremax");
define('_MA_QUIZMAKER_RESULTS_NBANSWERS', "Nb réponses");
define('_MA_QUIZMAKER_RESULTS_DURATION', "Durée");
define('_MA_QUIZMAKER_RESULTS_NOTE', "Note");
define('_MA_QUIZMAKER_RESULTS_CREATION', "Création");
define('_MA_QUIZMAKER_MESSAGES', "messages");
define('_MA_QUIZMAKER_MESSAGES_TITLE', "Titre des messages");
define('_MA_QUIZMAKER_MESSAGES_DESC', "Description des messages");
define('_MA_QUIZMAKER_MESSAGES_LIST', "Liste des messages");
define('_MA_QUIZMAKER_MESSAGES_ID', "Identifiant");
define('_MA_QUIZMAKER_MESSAGES_CODE', "Code");
define('_MA_QUIZMAKER_MESSAGES_MESSAGE', "Un message");
define('_MA_QUIZMAKER_INDEX_THEREARE', "Il y a %s messages");
define('_MA_QUIZMAKER_INDEX_LATEST_LIST', "Dernier QuizMaker");
define('_MA_QUIZMAKER_SUBMIT', "Soumettre");
define('_MA_QUIZMAKER_SUBMIT_MESSAGES', "Soumettre des messages");
define('_MA_QUIZMAKER_SUBMIT_ALLPENDING', "Tous les messages/informations de script sont publiés en attente de vérification.");
define('_MA_QUIZMAKER_SUBMIT_DONTABUSE', "Le nom d'utilisateur et l'IP sont enregistrés, veuillez donc ne pas abuser du système.");
define('_MA_QUIZMAKER_SUBMIT_ISAPPROVED', "Vos messages ont été approuvés");
define('_MA_QUIZMAKER_SUBMIT_PROPOSER', "Soumettre un message");
define('_MA_QUIZMAKER_SUBMIT_RECEIVED', "Nous avons reçu les informations de vos messages. Merci !");
define('_MA_QUIZMAKER_SUBMIT_SUBMITONCE', "Soumettez vos messages/script une seule fois.");
define('_MA_QUIZMAKER_SUBMIT_TAKEDAYS', "Cela prendra plusieurs jours pour voir vos messages/script ajoutés avec succès dans notre base de données.");
define('_MA_QUIZMAKER_FORM_OK', "Enregistré avec succès");
define('_MA_QUIZMAKER_FORM_DELETE_OK', "Supprimé avec succès");
define('_MA_QUIZMAKER_FORM_SURE_DELETE', "Êtes-vous sûr de supprimer : <b><span style='color : Red;'>%s </span></b>");
define('_MA_QUIZMAKER_FORM_SURE_RENEW', "Êtes-vous sûr de mettre à jour : <b><span style='color : Red;'>%s </span></b>");
define('_MA_QUIZMAKER_INVALID_PARAM', "Paramètre invalide");
define('_MA_QUIZMAKER_ADMIN', "Admin");
define('_MA_QUIZMAKER_RUN_QUIZ', "Lancer le quiz");
define('_MA_QUIZMAKER_UID', "UID");
define('_MA_QUIZMAKER_SCORE_ACHIVED', "Score");
define('_MA_QUIZMAKER_SCORE_MAX', "Score max");
define('_MA_QUIZMAKER_DURATION', "Durée");
define('_MA_QUIZMAKER_DATE', "Date");
define('_MA_QUIZMAKER_SCORE', "Score");
define('_MA_QUIZMAKER_UNAME', "Pseudo");
define('_MA_QUIZMAKER_STILL_ANSWER', "Vous avez déjà participé à ce quiz.<br>Veuillez réessayer plus tard.");
define('_MA_QUIZMAKER_NOTE', "Note");
define('_MA_QUIZMAKER_SELECTION', "Sélection");
define('_MA_QUIZMAKER_NO_RESULTS', "Il n'y a pas encore de résultats pour ce quiz");
define('_MA_QUIZMAKER_RESULT_5', "Expert");
define('_MA_QUIZMAKER_RESULT_4', "Connaisseur");
define('_MA_QUIZMAKER_RESULT_3', "Quelques progrès à faire");
define('_MA_QUIZMAKER_RESULT_2', "Moyen");
define('_MA_QUIZMAKER_RESULT_1', "peut mieux faire");
define('_MA_QUIZMAKER_RESULT_0', "Nullos");
define('_MA_QUIZMAKER_', "");
define('_MA_QUIZMAKER_CATEGORIES_THEME', "Thème");

define('_MA_QUIZMAKER_NAME', "Nom");
define('_MA_QUIZMAKER_NB_QUESTIONS', "Questions");
define('_MA_QUIZMAKER_EXECUTION', "Exécution");
define('_MA_QUIZMAKER_NO_SCORE', "--- Néant ---");
define('_MA_QUIZMAKER_AVERAGE', "Moyenne");
define('_MA_QUIZMAKER_CLOSED', "Fermé");
define('_MA_QUIZMAKER_PARTICIPATION', "Participation");
define('_MA_QUIZMAKER_SOLUTIONS', "Solutions");
define('_MA_QUIZMAKER_HOW_TO_RUN_QUIZ', "Pour lancer un quiz, cliquez sur la flèche.");
define('_MA_QUIZMAKER_HOW_TO_SHOW_SOLUTIONS', "Pour voir les solutions cliquez sur les ampoules oranges.");
define('_MA_QUIZMAKER_HOW_TO_SHOW_RESULTS', "Pour voir les résultats cliquez sur le symbole &#425;.");

define('_MA_QUIZMAKER_RESULTS_FOR', "Résultats pour : ");
define('_MA_QUIZMAKER_RESULTS_NOTE', "Note");
define('_MA_QUIZMAKER_HOUR', "heure(s)");
define('_MA_QUIZMAKER_MINUTES', "minute(s)");
define('_MA_QUIZMAKER_SECONDS', "secondes");
define('_MA_QUIZMAKER_ANSWERS_AT', "Répondu à");
define('_MA_QUIZMAKER_OF_TOTAL', "Au total");
define('_MA_QUIZMAKER_QUIZ_ANSWERS_AND_EXPLANATION', "Réponses et explications du Quiz");
define('_MA_QUIZMAKER_THANKS_FOR_PARTICIPATION', "Merci pour votre participation");

?>