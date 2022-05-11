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

define('_AM_QUIZMAKER_STATISTICS', "Statistiques");
define('_AM_QUIZMAKER_THEREARE_QUIZ', "Il y a <span class='bold'>%s</span> quiz dans la base de données");
define('_AM_QUIZMAKER_THEREARE_QUESTIONS', "Il y a <span class='bold'>%s</span> questions dans la base de données");
define('_AM_QUIZMAKER_THEREARE_CATEGORIES', "Il y a <span class='bold'>%s</span> catégories dans la base de données");
define('_AM_QUIZMAKER_THEREARE_TYPE_QUESTION', "Il y a <span class='bold'>%s</span> type_question dans la base de données");
define('_AM_QUIZMAKER_THEREARE_ANSWERS', "Il y a <span class='bold'>%s</span> réponses dans la base de données");
define('_AM_QUIZMAKER_THEREARE_RESULTS', "Il y a <span class='bold'>%s</span> résultats dans la base de données");
define('_AM_QUIZMAKER_THEREARE_MESSAGES', "Il y a <span class='bold'>%s</span> messages dans la base de données");
define('_AM_QUIZMAKER_THEREARENT_QUIZ', "Il n'y a pas de quiz");
define('_AM_QUIZMAKER_THEREARENT_QUESTIONS', "Il n'y a pas de questions");
define('_AM_QUIZMAKER_THEREARENT_CATEGORIES', "Il n'y a pas de catégories");
define('_AM_QUIZMAKER_THEREARENT_TYPE_QUESTION', "Il n'y a pas de type_question");
define('_AM_QUIZMAKER_THEREARENT_ANSWERS', "Il n'y a pas de réponses");
define('_AM_QUIZMAKER_THEREARENT_RESULTS', "Il n'y a pas de résultats");
define('_AM_QUIZMAKER_THEREARENT_MESSAGES', "Il n'y a pas de messages");
define('_AM_QUIZMAKER_FORM_OK', "Enregistré avec succès");
define('_AM_QUIZMAKER_FORM_DELETE_OK', "Supprimé avec succès");
define('_AM_QUIZMAKER_FORM_SURE_DELETE', "Êtes-vous sûr de supprimer : <b><span style='color : Red;'>%s </span></b>");
define('_AM_QUIZMAKER_FORM_SURE_RENEW', "Êtes-vous sûr de mettre à jour : <b><span style='color : Red;'>%s </span></b>");
define('_AM_QUIZMAKER_ADD_QUIZ', "Ajouter un nouveau quiz");
define('_AM_QUIZMAKER_ADD_QUESTIONS', "Ajouter de nouvelles questions");
define('_AM_QUIZMAKER_ADD_CATEGORIES', "Ajouter de nouvelles catégories");
define('_AM_QUIZMAKER_ADD_TYPEDEQUESTION', "Ajouter un nouveau typeDeQuestion");
define('_AM_QUIZMAKER_ADD_ANSWERS', "Ajouter de nouvelles réponses");
define('_AM_QUIZMAKER_ADD_RESULTS', "Ajouter de nouveaux résultats");
define('_AM_QUIZMAKER_ADD_MESSAGES', "Ajouter de nouveaux messages");
define('_AM_QUIZMAKER_QUIZ_LIST', "Liste des quiz");
define('_AM_QUIZMAKER_QUESTIONS_LIST', "Liste de questions");
define('_AM_QUIZMAKER_CATEGORIES_LIST', "Liste des catégories");
define('_CO_QUIZMAKER_TYPE_QUESTION_LIST', "Liste des Type_question");
define('_AM_QUIZMAKER_ANSWERS_LIST', "Liste de réponses");
define('_AM_QUIZMAKER_RESULTS_LIST', "Liste des résultats");
define('_AM_QUIZMAKER_MESSAGES_LIST', "Liste des messages");
define('_AM_QUIZMAKER_QUIZ_ADD', "Ajout d'un nouveau quiz");
define('_AM_QUIZMAKER_QUIZ_EDIT', "Édition du Quiz");
define('_AM_QUIZMAKER_QUIZ_ID', "Identifiant");
define('_AM_QUIZMAKER_QUIZ_CAT_ID', "Identifiant du chat");
define('_AM_QUIZMAKER_QUIZ_NAME', "Nom");
define('_AM_QUIZMAKER_DESCRIPTION', "Description");
define('_AM_QUIZMAKER_QUIZ_CREATION', "DateHeure");
define('_AM_QUIZMAKER_QUIZ_UPDATE', "DateHeure");
define('_AM_QUIZMAKER_QUIZ_COMMENTS', "commentaires");
define('_AM_QUIZMAKER_DATEBEGIN', "Date début");
define('_AM_QUIZMAKER_DATEEND', "Date fin");
define('_AM_QUIZMAKER_QUIZ_THEME', "Thème");
define('_AM_QUIZMAKER_QUIZ_ANSWERBEFORENEXT', "Réponse obligatoire");
define('_AM_QUIZMAKER_QUIZ_ANSWERBEFORENEXT_DESC', "L'utilisateur doit faire une réponse avant de passer à la question suivante");
define('_AM_QUIZMAKER_QUIZ_ALLOWEDPREVIOUS', "Retour arrière");
define('_AM_QUIZMAKER_QUIZ_ALLOWEDPREVIOUS_DESC', "Autoriser à revenir sur les questions prcédentes");
define('_AM_QUIZMAKER_QUIZ_ALLOWEDSUBMIT', "Bouton de soummission");
define('_AM_QUIZMAKER_QUIZ_ALLOWEDSUBMIT_DESC', "Affiche le bouton de soumission pour valider les réponses");
define('_AM_QUIZMAKER_QUIZ_USE_TIMER', "Utiliser un chronomètre");
define('_AM_QUIZMAKER_QUIZ_USE_TIMER_DESC', "Si oui utilise le délai paramètré dans les questions pour enchainer les questions, réponse faite ou  non");
define('_AM_QUIZMAKER_QUIZ_SHOWRESULTALLWAYS', "ShowResultAllways");
define('_AM_QUIZMAKER_QUIZ_SHOWREPONSES_BOTTOM', "Afficher les réponses");
define('_AM_QUIZMAKER_QUIZ_SHOWREPONSES_BOTTOM_DESC', "Permet d'afficher les réponses en bas de page.<br>A utiliser en mode développement ou préparation d'un quiz");
define('_AM_QUIZMAKER_QUIZ_SHOWLOG', "Montrer les logs");
define('_AM_QUIZMAKER_LEGEND', "Légende");
define('_AM_QUIZMAKER_DATEBEGINOK', "Date de déébut Ok");
define('_AM_QUIZMAKER_DATEENDOK', "Date de fin Ok");
define('_AM_QUIZMAKER_QUESTIONS_ADD', "Ajouter des questions");
define('_AM_QUIZMAKER_QUESTIONS_EDIT', "Modifier les questions");
define('_AM_QUIZMAKER_QUESTIONS_ID', "Id");
define('_AM_QUIZMAKER_QUESTIONS_QUIZ_ID', "Quiz id");
define('_AM_QUIZMAKER_QUESTIONS_QUESTION', "Question");
define('_AM_QUIZMAKER_QUESTIONS_COMMENT1', "Commentaires");
define('_AM_QUIZMAKER_QUESTIONS_COMMENT1_DESC', "Les balises <b>{scoreMaxi}</b> et <b>{timer}</b> seront remplacées par leurs valeurs respectives.");
define('_AM_QUIZMAKER_QUESTIONS_TYPE_QUESTION', "Type question");
define('_AM_QUIZMAKER_QUESTIONS_MINREPONSE', "Minimum de réponses");
define('_AM_QUIZMAKER_QUESTIONS_MINREPONSE2', "Min rép.");
define('_AM_QUIZMAKER_QUESTIONS_CREATION', "Date de création");
define('_AM_QUIZMAKER_QUESTIONS_UPDATE', "Date de mise à jour");
define('_AM_QUIZMAKER_QUESTIONS_COMMENT2', "Commentaire");
define('_AM_QUIZMAKER_QUESTIONS_TEXT_TO_CORRECT', "Texte à corriger");
define('_AM_QUIZMAKER_CATEGORIES_ADD', "Ajouter des catégories");
define('_AM_QUIZMAKER_CATEGORIES_EDIT', "Modifier les catégories");
define('_AM_QUIZMAKER_CATEGORIES_ID', "Identifiant");
define('_AM_QUIZMAKER_CATEGORIES_NAME', "Nom");
define('_AM_QUIZMAKER_CATEGORIES_DESCRIPTION', "Description");
define('_AM_QUIZMAKER_CATEGORIES_THEME', "Thème");
define('_AM_QUIZMAKER_CREATION', "Création");
define('_AM_QUIZMAKER_UPDATE', "Mise à jour");
define('_AM_QUIZMAKER_TYPEDEQUESTION_ADD', "Ajouter TypeDeQuestion");
define('_AM_QUIZMAKER_TYPEDEQUESTION_EDIT', "Modifier le typeDeQuestion");
define('_AM_QUIZMAKER_TYPEDEQUESTION_ID', "Identifiant");
define('_AM_QUIZMAKER_TYPEDEQUESTION_NAME', "Nom");
define('_AM_QUIZMAKER_TYPEDEQUESTION_DESCRIPTION', "Description");
define('_AM_QUIZMAKER_TYPEDEQUESTION_SHORTDESC', "Description courte");
define('_AM_QUIZMAKER_ANSWERS_ADD', "Ajouter des réponses");
define('_AM_QUIZMAKER_ANSWERS_EDIT', "Modifier les réponses");
define('_AM_QUIZMAKER_ANSWERS_ID', "Id");
define('_AM_QUIZMAKER_ANSWERS_QUESTION_ID', "Question id");
define('_AM_QUIZMAKER_ANSWERS_LETTER', "Lettre");
define('_AM_QUIZMAKER_ANSWERS_PROPOSITION', "Proposition");
define('_AM_QUIZMAKER_ANSWERS_POINTS', "Points");
define('_AM_QUIZMAKER_RESULTS_ID', "Id");
define('_AM_QUIZMAKER_RESULTS_QUIZ_ID', "Quiz id");
define('_AM_QUIZMAKER_RESULTS_UID', "Uid");
define('_AM_QUIZMAKER_RESULTS_SCORE', "Score");
define('_AM_QUIZMAKER_RESULTS_SOREMAX', "Sore maximum");
define('_AM_QUIZMAKER_RESULTS_NBANSWERS', "Nonbre de réponses");
define('_AM_QUIZMAKER_RESULTS_DURATION', "Durée");
define('_AM_QUIZMAKER_RESULTS_NOTE', "Note");
define('_AM_QUIZMAKER_RESULTS_CREATION', "Creation");
define('_AM_QUIZMAKER_MESSAGES_ADD', "Ajouter des messages");
define('_AM_QUIZMAKER_MESSAGES_EDIT', "Modifier les messages");
define('_AM_QUIZMAKER_MESSAGES_ID', "Id");
define('_AM_QUIZMAKER_MESSAGES_CODE', "Code");
define('_AM_QUIZMAKER_MESSAGES_MESSAGE', "Message");
define('_AM_QUIZMAKER_FORM_UPLOAD', "Téléverser un fichier");
define('_AM_QUIZMAKER_FORM_UPLOAD_NEW', "Télécharger un nouveau fichier :");
define('_AM_QUIZMAKER_FORM_UPLOAD_SIZE', "Taille maximale du fichier :");
define('_AM_QUIZMAKER_FORM_UPLOAD_SIZE_MB', "Mo");
define('_AM_QUIZMAKER_FORM_UPLOAD_IMG_WIDTH', "Largeur maximale de l'image :");
define('_AM_QUIZMAKER_FORM_UPLOAD_IMG_HEIGHT', "Hauteur maximale de l'image :");
define('_AM_QUIZMAKER_FORM_IMAGE_PATH', "Fichiers dans %s :");
define('_AM_QUIZMAKER_FORM_ACTION', "Action");
define('_AM_QUIZMAKER_FORM_EDIT', "Modification");
define('_AM_QUIZMAKER_FORM_DELETE', "Dégager");
define('_AM_QUIZMAKER_PERMISSIONS_GLOBAL', "Autorisations globales");
define('_AM_QUIZMAKER_PERMISSIONS_GLOBAL_DESC', "Autorisations globales pour vérifier le type de.");
define('_AM_QUIZMAKER_PERMISSIONS_GLOBAL_4', "Autorisations globales à approuver");
define('_AM_QUIZMAKER_PERMISSIONS_GLOBAL_8', "Autorisations globales pour soumettre");
define('_AM_QUIZMAKER_PERMISSIONS_GLOBAL_16', "Autorisations globales pour afficher");
define('_AM_QUIZMAKER_PERMISSIONS_APPROVE', "Autorisations d'approbation");
define('_AM_QUIZMAKER_PERMISSIONS_APPROVE_DESC', "Autorisations d'approbation");
define('_AM_QUIZMAKER_PERMISSIONS_SUBMIT', "Autorisations de soumission");
define('_AM_QUIZMAKER_PERMISSIONS_SUBMIT_DESC', "Autorisations de soumission");
define('_AM_QUIZMAKER_PERMISSIONS_VIEW', "Autorisations de voir");
define('_AM_QUIZMAKER_PERMISSIONS_VIEW_DESC', "Autorisations de voir");
define('_AM_QUIZMAKER_NO_PERMISSIONS_SET', "Aucune autorisation définie");
define('_AM_QUIZMAKER_MAINTAINEDBY', "  est maintenu par");
define('_AM_QUIZMAKER_CATEGORIES', "Catégories");
define('_AM_QUIZMAKER_QUIZ', "Quiz");
define('_AM_QUIZMAKER_ADD_NEW_QUESTION', "<== Ajouer une nouvelle question");
define('_AM_QUIZMAKER_SELECT_TYPE_BEFORE_ADD', "Sélectionnez un type de question avant d'ajouter une nouvelle question");
define('_AM_QUIZMAKER_CATEGORY', "Catégorie");
define('_AM_QUIZMAKER_WEIGHT', "Poids");
define('_AM_QUIZMAKER_QUESTION', "Question");
define('_AM_QUIZMAKER_QUESTIONS', "Questions");
define('_AM_QUIZMAKER_ADD_NEW_ANSWER', "Ajouter une proposition");
define('_AM_QUIZMAKER_COMPUTE_WEIGHT', "Initialisation du poids");
define('_AM_QUIZMAKER_CAPTION', "Titre");
define('_AM_QUIZMAKER_TIMER', "Chronomètre");
define('_AM_QUIZMAKER_ISQUESTION', "Est une question");
define('_AM_QUIZMAKER_ISQUESTION_DESC', "Non pour les slides d'intro encarts et résultats.<br>laissez oui pour les autres");
define('_AM_QUIZMAKER_BUILD_QUIZ', "Générer le quiz");
define('_AM_QUIZMAKER_FILE_NAME', "Fichier");
define('_AM_QUIZMAKER_FILE_NAME_JS', "Fichier Java script");
define('_AM_QUIZMAKER_FILE_NAME_JS_DESC', "La nom du fichier ne doit contenir ni espace, ni caractère accentués.");
define('_AM_QUIZMAKER_INPUTS', "Nombre de zones de saisie");
define('_AM_QUIZMAKER_DESCCRITION', "Description");
define('_AM_QUIZMAKER_IMAGE', "Image");
define('_AM_QUIZMAKER_NAME', "Nom");
define('_AM_QUIZMAKER_SHORTDESC', "Description courte");
define('_AM_QUIZMAKER_TYPE', "Type");
define('_AM_QUIZMAKER_ID', "#");
define('_AM_QUIZMAKER_PROPOSITIONS_ANSWERS', "Propositions de réponses");
define('_AM_QUIZMAKER_PROPOSITIONS', "Propositions");
define('_AM_QUIZMAKER_OPTIONS', "Options");
define('_AM_QUIZMAKER_PROPOSITIONS_MEME_FAMILLE_DESC', "Lister ici les éléments dénissant un objet");
define('_AM_QUIZMAKER_PROPOSITIONS_MEME_FAMILLE_PROPOS', "Propositions");
define('_AM_QUIZMAKER_NUMBERING', "Numérotation");
define('_AM_QUIZMAKER_NUMERIQUE', "Numérique");
define('_AM_QUIZMAKER_UPPERCASE', "Majuscule");
define('_AM_QUIZMAKER_LOWERCASE', "Minuscule");
define('_AM_QUIZMAKER_QUIZ_ONCLICK', "Action sur clique souris");
define('_AM_QUIZMAKER_QUIZ_ONCLICK_DESC', "Dans les listes déroulantes l'action peut être effectuée par un simple clique ou un double clique");
define('_AM_QUIZMAKER_CLICK_SIMPLE', "Simple click");
define('_AM_QUIZMAKER_CLICK_DOUBLE', "double click");
define('_AM_QUIZMAKER_QUIZ_SHUFFLE_QUESTION', "Mélanger les questions");
define('_AM_QUIZMAKER_QUIZ_SHUFFLE_QUESTION_DESC', "Ne pas utiliser si l'ordre des questions est important ou si des \"Encarts\" ont été utilisés");
define('_AM_QUIZMAKER_QUIZ_SHOW_GOOD_ANSWERS', "Afficher le bouton \"Bonnes réponses\"");
define('_AM_QUIZMAKER_QUIZ_SHOW_GOOD_ANSWERS_DESC', "Utiliser pour le développement, laisser non en production.");
define('_AM_QUIZMAKER_QUIZ_SHOW_BAD_ANSWERS', "Afficher le bouton \"Mauvaises réponses\"");
define('_AM_QUIZMAKER_QUIZ_SHOW_BAD_ANSWERS_DESC', "Utiliser pour le développement, laisser non en production.");
define('_AM_QUIZMAKER_QUIZ_MINUS_OSGA', "Diminue le score");
define('_AM_QUIZMAKER_QUIZ_MINUS_OSGA_DESC', "Diminue le score si le bouton \"Bonnes réponses\" est visible et cliquée");
define('_AM_QUIZMAKER_QUIZ_RESULT_POPUP', "Afficher le résultat dans un popup");
define('_AM_QUIZMAKER_QUIZ_RESULT_POPUP_DESC', "permet d'afficher le résultat  de la question courante dans un popup lors du passage à la suivante.<br>Permet d'avoirune idée du résultat global au fure et à mesure, surtout si le retour arrière est bloqué");
define('_AM_QUIZMAKER_THEME', "Thème");
define('_AM_QUIZMAKER_THEME_DESC', "Laisser vide pour utiliser le thème de la catégorie");
define('_AM_QUIZMAKER_LEGEND_DESC', "Texte affiché sur la page d'introduction du quiz");
define('_AM_QUIZMAKER_DESCRIPTION_DESC', "Texte affiché sur la page d'introduction du quiz");
define('_AM_QUIZMAKER_QUIZ_SHOWREPONSES', "Afficher les réponses");
define('_AM_QUIZMAKER_QUIZ_SHOWRESULTALLWAYS_DESC', "Affiche le résultat global dans le bandeau du bas");
define('_AM_QUIZMAKER_QUIZ_SHOWLOG_DESC', "Utilisé lors du développement, afffiche des informations d'action ou de valeur.<br>Laisser non en production.");
define('_AM_QUIZMAKER_QUIZ_BUILD', "Génération");
define('_AM_QUIZMAKER_ACTIF', "Actif");
define('_CO_QUIZMAKER_TYPE_QUESTION_2_ADD', "Type de question à ajouter");
define('_AM_QUIZMAKER_TEST_QUIZ', "Teste quiz version");
define('_AM_QUIZMAKER_VISIBLE', "Visible");
define('_AM_QUIZMAKER_VISIBLE_DESC', "La question ne sera pas générer dans le quiz.");
define('_AM_QUIZMAKER_QUIZ_SHOW_BTN_RELOAD_ANSWERS', "Afficher le bouton \"Réinitialiser\"");
define('_AM_QUIZMAKER_QUIZ_SHOW_BTN_RELOAD_ANSWERS_DESC', "Permet de réinitialiser la question courante.");
define('_AM_QUIZMAKER_QUIZ_SHOW_RELOAD_ANSWERS', "Afficher le bouton \"Recharger\"");
define('_AM_QUIZMAKER_PERIODE', "Période");
define('_AM_QUIZMAKER_ABOUT_WHY_DONATE', "Faire une donation c'est contribuer à maintenir le projet, et à aider l'auteur à le maintenir.<br>Merci à tous ceux qui feront un donation, si petite soit-elle.");
define('_AM_QUIZMAKER_ABOUT_BY', "par");
define('_AM_QUIZMAKER_ABOUT_CONTRIBUTION', "Contribution");
define('_AM_QUIZMAKER_QUIZ_SHOW_TYPE_QUESTION', "Afficher le type de question");
define('_AM_QUIZMAKER_QUIZ_SHOW_TYPE_QUESTION_DESC', "Affiche le type de question dans l'entête du slide sous la question.<br>Utilisé pour la mise au point du quiz, laissez \"Non\" en production<br>Affiche également l'id du quiz et de la question");
define('_AM_QUIZMAKER_CONFIGS_OPTIONS', "Configurations");
define('_AM_QUIZMAKER_CONFIG_PROD', "Configuration en production");
define('_AM_QUIZMAKER_CONFIG_DEV', "Configuration pour le developpement");
define('_AM_QUIZMAKER_SHUFFLE_ANS', "Mélanger les propositions");
define('_AM_QUIZMAKER_SHUFFLE_ANS_DESC', "Change l'ordre des réponses à chaque fois que le quiz est lancé.<br>Les propositions seront donc numérotées différemment à chaque foir.<br>Attention certains types de questions peuvent ne pas changer l'ordre.");
define('_AM_QUIZMAKER_TIMER_DESC', "Indiquer le temps d'affichage en secondes de la question avant de passer à la suivante.<br>Cette options n'est active que si le paramètre \"Utiliser un chronomètre\" du quiz est activé");
define('_AM_QUIZMAKER_NOT_QUESTION', "Ce n'est pas une question");
define('_AM_QUIZMAKER_DOWNLOAD_OK', "Le téléchargement va démarrer. Si il ne démarre pas cliquer sur le lien direct ici ===>");
define('_AM_QUIZMAKER_EXPORTER', "Exporter");
define('_AM_QUIZMAKER_SELECT_CATEGORY_DESC', "Sélectionnez une catégorie de destintion pour ce nouveau quiz.");
define('_AM_QUIZMAKER_PARENT_ID', "ID Parent");
define('_AM_QUIZMAKER_PARENT', "Parent");
define('_AM_QUIZMAKER_NONE', "Aucun");
define('_AM_QUIZMAKER_TEXTE', "Texte");
define('_AM_QUIZMAKER_FORM_TYPE', "Type de formulaire");
define('_AM_QUIZMAKER_FORM_TYPE_SHORT', "Formulaire");
define('_AM_QUIZMAKER_FORM_TYPE_DESC', "Utiliser pour les type \"Encart\" pour caractériser un slide d'introduction, un simple encart ou le slide de résuluat.");
define('_AM_QUIZMAKER_FORM_RESULT', "Resultats");
define('_AM_QUIZMAKER_IMPORT', "Importation");
define('_AM_QUIZMAKER_IMPORTER', "Importer");
define('_AM_QUIZMAKER_FILE_TO_LOAD', "Fichier YAML à télécharger");
define('_AM_QUIZMAKER_FILE_DESC', "Un nouveau quiz sera généré");
define('_AM_QUIZMAKER_FILE_UPLOADSIZE', "Taile maximum des fichiers %s mo");
define('_CO_QUIZMAKER_TYPE_QUESTION', "Type de question");
define('_AM_QUIZMAKER_EXPORT_YML', "Export YML");
define('_AM_QUIZMAKER_EXPORT_QUIZ_YML', "Export YML du quiz");
define('_AM_QUIZMAKER_IMPORT_YML', "Import YML");
define('_AM_QUIZMAKER_IMPORT_QUIZ_YML', "Import YML du quiz");
define('_AM_QUIZMAKER_RESTOR_YML', "Restauration YML");
define('_AM_QUIZMAKER_RESTOR_QUIZ_YML', "Restauration YML du quiz");
define('_AM_QUIZMAKER_OPTIONS_FOR_DEV', "Options pour le développement - Laiser  \"Non\" ces options en production");
define('_AM_QUIZMAKER_OPTIONS_FOR_QUIZ', "Options du quiz");
define('_AM_QUIZMAKER_PERMISSIONS', "Gestion des permissions");
define('_AM_QUIZMAKER_GOTO_QUESTION', "Aller à la question");
define('_AM_QUIZMAKER_STRANGER_EXP', "<b>Expressions étrangères au texte</b>");
define('_AM_QUIZMAKER_RESULTS_SCORE_MIN', "Score minimum");
define('_AM_QUIZMAKER_RESULTS_SCORE_MAX', "Score maximum");
define('_AM_QUIZMAKER_MESSAGES_JS_MESSAGE01', "Attention à bien lire la consigne, et le sens de tri car seul l'ordre exacte donne des points.");
define('_AM_QUIZMAKER_MESSAGES_JS_RESULTS', "Résultats");
define('_AM_QUIZMAKER_MESSAGES_JS_SORTCOMBOBOX', "Attention à l'ordre.");
define('_AM_QUIZMAKER_MESSAGES_JS_RADIO', "Attention, plusieurs répponses sont possibles mais les points diffèrent selon la réponse. Les moins évidentes ont le nombre de points le plus élevé");
define('_AM_QUIZMAKER_MESSAGES_JS_CHECKBOX', "Chaque réponse est associée à un nombre points selon la difficulté. Les réponses se cumulent");
define('_AM_QUIZMAKER_MESSAGES_JS_TEXTBOX', "Attention, plusieurs répponses sont possibles mais les points diffèrent selon la réponse. Les moins évidentes ont le nombre de points le plus élevé");
define('_AM_QUIZMAKER_MESSAGES_JS_ALLTYPE', "Important : Dans certains questionnaires il peut y avoir des réponses avc un nombre de points négatifs.");
define('_AM_QUIZMAKER_MESSAGES_JS_FORPOINTS', "Pour {pointsMin} à {pointsMax} points");
define('_AM_QUIZMAKER_MESSAGES_JS_FORCHRONO', " en {timer} secondes");
define('_AM_QUIZMAKER_MESSAGES_JS_FORPOINTSTIMER', "Pour {points} points en {timer} secondes");
define('_AM_QUIZMAKER_MESSAGES_JS_RESULTONSLIDE', "résultat : pour {reponses} réponses sur {questions} questions,<br>Votre score est de {points} points sur {totalPoints} points");
define('_AM_QUIZMAKER_MESSAGES_JS_BTNNEXT', "Suivant");
define('_AM_QUIZMAKER_MESSAGES_JS_BTNPREVIOUS', "Précédent");
define('_AM_QUIZMAKER_MESSAGES_JS_BTNSUBMIT', "Solutions");
define('_AM_QUIZMAKER_MESSAGES_JS_BTNCONTINUE', "Continuez");
define('_AM_QUIZMAKER_MESSAGES_JS_BTNRELOAD', "Recharger");
define('_AM_QUIZMAKER_MESSAGES_JS_BTNANTISECHE', "?");
define('_AM_QUIZMAKER_MESSAGES_JS_SHOWREPONSES', "Affichage pour le dev des réponses possibles (réponse ===> points)");
define('_AM_QUIZMAKER_MESSAGES_JS_BTNRELOADLIST', "Recharger la liste");
define('_AM_QUIZMAKER_MESSAGES_JS_BTNRELOADTEXT', "Recharger le texte");
define('_AM_QUIZMAKER_MESSAGES_JS_RESULTBRAVO0 ', "Votre résultat pour cette question :");
define('_AM_QUIZMAKER_MESSAGES_JS_RESULTBRAVO1 ', "Vous avez obtenu le score maximumn");
define('_AM_QUIZMAKER_MESSAGES_JS_RESULTBRAVO2 ', "Vous n'avez pas obtenu le score maximum");
define('_AM_QUIZMAKER_MESSAGES_JS_RESULTBRAVO3 ', "Vous avez obtenu le score minimum");
define('_AM_QUIZMAKER_MESSAGES_JS_RESULTSCORE ', "Votre score est de {points} / {totalPoints} points");
define('_AM_QUIZMAKER_MESSAGES_JS_POINTS ', "point(s)");
define('_AM_QUIZMAKER_MESSAGES_JS_BONNEREPONSE ', "La bonne réponse pour {points} point(s) est :");
define('_AM_QUIZMAKER_MESSAGES_JS_TPLWORD ', "<span style='color: red;'>{word}</span> ");
define('_AM_QUIZMAKER_MESSAGES_JS_TPLWORD2 ', "<span style='color: white;'>{word}</span> ");
define('_AM_QUIZMAKER_MESSAGES_JS_TPLREPONSETABLE ', "<table>{content}</table>");
define('_AM_QUIZMAKER_MESSAGES_JS_TPLREPONSEDBLTABLE ', "<table><tr>{content}</tr></table>");
define('_AM_QUIZMAKER_MESSAGES_JS_TPLREPONSEDBLTD ', "<td>{content}</td>");
define('_AM_QUIZMAKER_MESSAGES_JS_TPLREPONSETD ', "<tr><td>{word}</td><td>{sep}</td><td>{points}</td></tr>");
define('_AM_QUIZMAKER_SLIDE_POINTS', "Points");
define('_AM_QUIZMAKER_SLIDE_POINT', "Point");
define('_AM_QUIZMAKER_SLIDE_MOTS', "Mots");
define('_AM_QUIZMAKER_SLIDE_MOT', "Mot");
define('_AM_QUIZMAKER_SLIDE_TEXTE', "Texte");
define('_AM_QUIZMAKER_SLIDE_TEXTES', "Textes");
define('_AM_QUIZMAKER_SLIDE_PROPO', "Proposition");
define('_AM_QUIZMAKER_SLIDE_PROPOS', "Propositions");
define('_AM_QUIZMAKER_SLIDE_TITLE', "Titre");
define('_AM_QUIZMAKER_SLIDE_WEIGHT', "Poids");
define('_AM_QUIZMAKER_SLIDE_LABEL', "Libellé");
define('_AM_QUIZMAKER_SLIDE_INPUTS', "Entrées");
define('_AM_QUIZMAKER_SLIDE_001', "Points par mot trouvé");
define('_AM_QUIZMAKER_SLIDE_002', "Elements décrivant un objet");
define('_AM_QUIZMAKER_SLIDE_003', "Liste des options proposées");
define('_AM_QUIZMAKER_SLIDE_004', "");
define('_AM_QUIZMAKER_SLIDE_005', "");
define('_AM_QUIZMAKER_SLIDE_101', "Elements qui décrivent un même objet ou un ensemble d'objets");
define('_AM_QUIZMAKER_SLIDE_102', "Proposition de réponses - le points peuvent être négatifs");

define('_AM_QUIZMAKER_CAT_NOT_EMPTY', "La cétégorie n'est pas vide");
define('_AM_QUIZMAKER_NB_QUIZ', "Nb Quiz");

define('_AM_QUIZMAKER_LAST', "Enoyer à la fin");
define('_AM_QUIZMAKER_FIRST', "Enoyer au début");
define('_AM_QUIZMAKER_UP', "Remonter");
define('_AM_QUIZMAKER_DOWN', "Redescendre");
define('_AM_QUIZMAKER_EXPLANATION', "Explication");
define('_AM_QUIZMAKER_EXPLANATION_DESC', "Ce texte sera affiché avec les solutions pour les commenter.");
define('_AM_QUIZMAKER_ACTIF_DESC', "La question sera exclus du quiz sans la supprimer.");
define('_AM_QUIZMAKER_AUTO', "Automatique");
define('_AM_QUIZMAKER_PUBLISH', "Publication");
define('_AM_QUIZMAKER_PUBLISH_RESULTS', "Publier les résultats");

define('_AM_QUIZMAKER_PUBLISH_ANSWERS', "Publier les réponses");
define('_AM_QUIZMAKER_PUBLISH_AUTO_DESC', "Automatique : quand le quiz est cloturé");

define('_AM_QUIZMAKER_PUBLISH_QUIZ_DESC', "Défini le mode d'exécution, dans l'interface du site ou en indépenament du site<br>en mode autonome, les scores ne seront pas engistrés");
define('_AM_QUIZMAKER_CHRONO', "Chronomètre");

define('_AM_QUIZMAKER_REPARTITION_ALEATOIRE1', "Répartition aléatoire sur la liste de gauche uniquement");
define('_AM_QUIZMAKER_REPARTITION_ALEATOIRE2', "Réparttiion aléatoire sur les deux listes");
define('_AM_QUIZMAKER_DATE_BEGIN_END', "Dates début/fin");

define('_AM_QUIZMAKER_SLIDE_CAPTION1', "Titre de la liste 1");
define('_AM_QUIZMAKER_SLIDE_CAPTION2', "Titre de la liste 2");
define('_AM_QUIZMAKER_MESSAGES_CONSTANT', "Constante");

define('_AM_QUIZMAKER_QUIZ_PRESENTATION', "Présentation du quiz");
define('_AM_QUIZMAKER_QUIZ_RESULTATS', "Résultats");
define('_AM_QUIZMAKER_QUIZ_RESULTATS_DESC', "<ul style='text-align: left;'><li><span style='font-size: large; font-family: arial, helvetica, sans-serif;'>Nombre de r&eacute;ponses faites : {repondu} / {totalQuestions}</span><br />
<span style='font-size: large; font-family: arial, helvetica, sans-serif;'></span></li><li><span style='font-size: large; font-family: arial, helvetica, sans-serif;'><strong>Votre score est de {score} / {scoreMaxi}</strong><span style='color: #ff0000;'> (score minimum : {scoreMini})</span> </span><br />
<span style='font-size: large; font-family: arial, helvetica, sans-serif;'></span></li><li><span style='font-size: large; font-family: arial, helvetica, sans-serif;'>Votre temps de r&eacute;ponse est de {duree}</span></li></ul>");

define('_AM_QUIZMAKER_QUESTIONS_LEARN_MORE', "En savoir plus");
define('_AM_QUIZMAKER_QUESTIONS_LEARN_MORE_DESC', "Défini un lien sur une page externe");
define('_AM_QUIZMAKER_QUESTIONS_SEE_ALSO', "Voir aussi");
define('_AM_QUIZMAKER_QUESTIONS_SEE_ALSO_DESC', "Défini un lien sur une page externe");
define('_AM_QUIZMAKER_RAZ_RESULTS', "Effacer les résultats de ce quiz");
define('_AM_QUIZMAKER_CONFIRM_RAS_RESULTS', "Confirmer la suppression des résultats du quiz <b>%s (#%s) ?</b>");
define('_AM_QUIZMAKER_DELETE_RESULTS_OK', "Suppression des résultats ok");

?>