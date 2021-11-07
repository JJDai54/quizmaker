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
//echo "<hr>language : " . __FILE__ . "<hr>";

define('_CO_QUIZMAKER_GDLIBSTATUS', "Prise en charge de la bibliothèque GD :");
define('_CO_QUIZMAKER_GDLIBVERSION', "Version de la bibliothèque GD :");
define('_CO_QUIZMAKER_GDOFF', "<span style='font-weight: bold;'>Désactivé</span> (Aucune miniature disponible)");
define('_CO_QUIZMAKER_GDON', "<span style='font-weight: bold;'>Activé</span> (Miniatures disponibles)");
define('_CO_QUIZMAKER_IMAGEINFO', "État du serveur");
define('_CO_QUIZMAKER_MAXPOSTSIZE', "Taille maximale de publication autorisée (directive post_max_size dans php.ini) :");
define('_CO_QUIZMAKER_MAXUPLOADSIZE', "Taille de téléchargement maximale autorisée (directive upload_max_filesize dans php.ini) :");
define('_CO_QUIZMAKER_MEMORYLIMIT', "Limite de mémoire (directive memory_limit dans php.ini) :");
define('_CO_QUIZMAKER_METAVERSION', "<span style='font-weight: bold;'>Télécharge la version méta :</span>");
define('_CO_QUIZMAKER_OFF', "<span style='font-weight: bold;'>OFF</span>");
define('_CO_QUIZMAKER_ON', "<span style='font-weight: bold;'>ON</span>");
define('_CO_QUIZMAKER_SERVERPATH', "Chemin du serveur vers la racine XOOPS :");
define('_CO_QUIZMAKER_SERVERUPLOADSTATUS', "État des téléchargements du serveur :");
define('_CO_QUIZMAKER_SPHPINI', "<span style='font-weight: bold;'>Informations extraites du fichier ini PHP :</span>");
define('_CO_QUIZMAKER_UPLOADPATHDSC', "Noter. Le chemin de téléchargement *DOIT* contenir le chemin complet du serveur de votre dossier de téléchargement.");
define('_CO_QUIZMAKER_PRINT', "<span style='font-weight: bold;'>Imprimer</span>");
define('_CO_QUIZMAKER_PDF', "<span style='font-weight: bold;'>Créer un PDF</span>");
define('_CO_QUIZMAKER_UPGRADEFAILED0', "Échec de la mise à jour - impossible de renommer le champ '%s'");
define('_CO_QUIZMAKER_UPGRADEFAILED1', "Échec de la mise à jour - impossible d'ajouter de nouveaux champs");
define('_CO_QUIZMAKER_UPGRADEFAILED2', "Échec de la mise à jour - impossible de renommer la table '%s'");
define('_CO_QUIZMAKER_ERROR_COLUMN', "Impossible de créer la colonne dans la base de données : %s");
define('_CO_QUIZMAKER_ERROR_BAD_XOOPS', "Ce module nécessite XOOPS %s+ (%s installé)");
define('_CO_QUIZMAKER_ERROR_BAD_PHP', "Ce module nécessite la version PHP %s+ (%s installé)");
define('_CO_QUIZMAKER_ERROR_TAG_REMOVAL', "Impossible de supprimer les balises du module de balises");
define('_CO_QUIZMAKER_FOLDERS_DELETED_OK', "Les dossiers de téléchargement ont été supprimés");
define('_CO_QUIZMAKER_ERROR_BAD_DEL_PATH', "Impossible de supprimer le répertoire %s");
define('_CO_QUIZMAKER_ERROR_BAD_REMOVE', "Impossible de supprimer %s");
define('_CO_QUIZMAKER_ERROR_NO_PLUGIN', "Impossible de charger le plug-in");
define('_CO_QUIZMAKER_BACK_2_ADMIN', "Retour à Administration de");
define('_CO_QUIZMAKER_OVERVIEW', "Aperçu");
define('_CO_QUIZMAKER_DISCLAIMER', "Clause de non-responsabilité");
define('_CO_QUIZMAKER_LICENSE', "Licence");
define('_CO_QUIZMAKER_SUPPORT', "Soutien");
define('_CO_QUIZMAKER_ADD_SAMPLEDATA', "Importer des exemples de données (supprimera TOUTES les données actuelles)");
define('_CO_QUIZMAKER_SAMPLEDATA_SUCCESS', "Exemple de date téléchargé avec succès");
define('_CO_QUIZMAKER_SAVE_SAMPLEDATA', "Exporter des tableaux vers YAML");
define('_CO_QUIZMAKER_SHOW_SAMPLE_BUTTON', "Afficher le bouton d'exemple ?");
define('_CO_QUIZMAKER_SHOW_SAMPLE_BUTTON_DESC', "Si oui, le bouton \"Ajouter des données d'échantillon\" sera visible par l'administrateur. C'est Oui par défaut pour la première installation.");
define('_CO_QUIZMAKER_EXPORT_SCHEMA', "Exporter le schéma de base de données vers YAML");
define('_CO_QUIZMAKER_EXPORT_SCHEMA_SUCCESS', "Exporter le schéma de base de données vers YAML a été un succès");
define('_CO_QUIZMAKER_EXPORT_SCHEMA_ERROR', "ERREUR : Échec de l'exportation du schéma de base de données vers YAML");
define('_CO_QUIZMAKER_ADD_SAMPLEDATA_OK', "Êtes-vous sûr d'importer des exemples de données ? (Cela supprimera TOUTES les données actuelles)");
define('_CO_QUIZMAKER_HIDE_SAMPLEDATA_BUTTONS', "Masquer les boutons Importer");
define('_CO_QUIZMAKER_SHOW_SAMPLEDATA_BUTTONS', "Afficher les boutons Importer");
define('_CO_QUIZMAKER_CONFIRM', "Confirmer");
define('_CO_QUIZMAKER_BROWSETOTOPIC', "<span style='font-weight: bold;'>Parcourir les éléments par ordre alphabétique</span>");
define('_CO_QUIZMAKER_OTHER', "Autre");
define('_CO_QUIZMAKER_ALL', "Tous");
define('_CO_QUIZMAKER_ACCESSRIGHTS', "Des droits d'accès");
define('_CO_QUIZMAKER_ACTION', "action");
define('_CO_QUIZMAKER_ACTIVERIGHTS', "Droits actifs");
define('_CO_QUIZMAKER_BADMIN', "Bloquer l'administration");
define('_CO_QUIZMAKER_BLKDESC', "La description");
define('_CO_QUIZMAKER_CBCENTER', "Centre Milieu");
define('_CO_QUIZMAKER_CBLEFT', "Centre gauche");
define('_CO_QUIZMAKER_CBRIGHT', "Centre droit");
define('_CO_QUIZMAKER_SBLEFT', "La gauche");
define('_CO_QUIZMAKER_SBRIGHT', "Droit");
define('_CO_QUIZMAKER_SIDE', "Alignement");
define('_CO_QUIZMAKER_TITLE', "Titre");
define('_CO_QUIZMAKER_VISIBLE', "Visible");
define('_CO_QUIZMAKER_VISIBLEIN', "Visible dans");
define('_CO_QUIZMAKER_WEIGHT', "Poids");
define('_CO_QUIZMAKER_PERMISSIONS', "Autorisations");
define('_CO_QUIZMAKER_BLOCKS', "Administrateur des blocs");
define('_CO_QUIZMAKER_BLOCKS_DESC', "Blocs/Administrateur de groupe");
define('_CO_QUIZMAKER_BLOCKS_MANAGMENT', "Faire en sorte");
define('_CO_QUIZMAKER_BLOCKS_ADDBLOCK', "Ajouter un nouveau bloc");
define('_CO_QUIZMAKER_BLOCKS_EDITBLOCK', "Modifier un bloc");
define('_CO_QUIZMAKER_BLOCKS_CLONEBLOCK', "Cloner un bloc");
define('_CO_QUIZMAKER_AGDS', "Groupes d'administrateurs");
define('_CO_QUIZMAKER_BCACHETIME', "Temps de cache");
define('_CO_QUIZMAKER_BLOCKS_ADMIN', "Administrateur des blocs");
define('_CO_QUIZMAKER_TPLSETS', "Gestion des modèles");
define('_CO_QUIZMAKER_GENERATE', "produire");
define('_CO_QUIZMAKER_FILENAME', "Nom de fichier");
define('_CO_QUIZMAKER_ADMENU_MIGRATE', "Émigrer");
define('_CO_QUIZMAKER_FOLDER_YES', "Le dossier \"%s\" existe");
define('_CO_QUIZMAKER_FOLDER_NO', "Le dossier \"%s\" n'existe pas. Créez le dossier spécifié avec CHMOD 777.");
define('_CO_QUIZMAKER_SHOW_DEV_TOOLS', "Afficher le bouton Outils de développement ?");
define('_CO_QUIZMAKER_SHOW_DEV_TOOLS_DESC', "Si oui, l'onglet \"Migrer\" et les autres outils de développement seront visibles par l'administrateur.");
define('_CO_QUIZMAKER_ADMENU_FEEDBACK', "Retour d'information");
define('_CO_QUIZMAKER_NEW_VERSION', "Nouvelle version:");
define('_CO_QUIZMAKER_AVAILABLE', "<span style='color: green;'>Disponible</span>");
define('_CO_QUIZMAKER_NOTAVAILABLE', "<span style='color: red;'>Non disponible</span>");
define('_CO_QUIZMAKER_NOTWRITABLE', "<span style='color: red;'>Devrait avoir l'autorisation ( %d ), mais elle l'a ( %d )</span>");
define('_CO_QUIZMAKER_CREATETHEDIR', "Créez-le");
define('_CO_QUIZMAKER_SETMPERM', "Définir l'autorisation");
define('_CO_QUIZMAKER_DIRCREATED', "Le répertoire a été créé");
define('_CO_QUIZMAKER_DIRNOTCREATED', "Le répertoire ne peut pas être créé");
define('_CO_QUIZMAKER_PERMSET', "L'autorisation a été définie");
define('_CO_QUIZMAKER_PERMNOTSET', "L'autorisation ne peut pas être définie");
define('_CO_QUIZMAKER_FILECOPIED', "Le fichier a été copié");
define('_CO_QUIZMAKER_FILENOTCOPIED', "Le fichier ne peut pas être copié");
define('_CO_QUIZMAKER_IMAGE_WIDTH', "Largeur d'affichage de l'image");
define('_CO_QUIZMAKER_IMAGE_WIDTH_DSC', "Largeur d'affichage pour l'image");
define('_CO_QUIZMAKER_IMAGE_HEIGHT', "Hauteur d'affichage de l'image");
define('_CO_QUIZMAKER_IMAGE_HEIGHT_DSC', "Hauteur d'affichage pour l'image");
define('_CO_QUIZMAKER_IMAGE_CONFIG', "<span style=\"color : #FF0000 ; font-size : Small ; font-weight : bold ;\">--- EXTERNE Configuration de l'image ---</span>");
define('_CO_QUIZMAKER_IMAGE_CONFIG_DSC', "");
define('_CO_QUIZMAKER_IMAGE_UPLOAD_PATH', "Chemin de téléchargement d'image");
define('_CO_QUIZMAKER_IMAGE_UPLOAD_PATH_DSC', "Chemin de téléchargement des images");
define('_CO_QUIZMAKER_TRUNCATE_LENGTH', "Nombre de caractères à tronquer dans le champ de texte long");
define('_CO_QUIZMAKER_TRUNCATE_LENGTH_DESC', "Définir le nombre maximum de caractères pour tronquer les champs de texte longs");
define('_CO_QUIZMAKER_STATS_SUMMARY', "Statistiques du module");
define('_CO_QUIZMAKER_TOTAL_CATEGORIES', "Catégories :");
define('_CO_QUIZMAKER_TOTAL_ITEMS', "Articles");
define('_CO_QUIZMAKER_TOTAL_OFFLINE', "Hors ligne");
define('_CO_QUIZMAKER_TOTAL_PUBLISHED', "Publié");
define('_CO_QUIZMAKER_TOTAL_REJECTED', "Rejeté");
define('_CO_QUIZMAKER_TOTAL_SUBMITTED', "Soumis");
define('_CO_QUIZMAKER_DC_AVAILABLE', "<span style='color: green;'>Disponible</span>");
define('_CO_QUIZMAKER_DC_NOTAVAILABLE', "<span style='color: red;'>Non disponible</span>");
define('_CO_QUIZMAKER_DC_NOTWRITABLE', "<span style='color: red;'> devrait avoir l'autorisation ( %d ), mais il l'a ( %d )</span>");
define('_CO_QUIZMAKER_DC_CREATETHEDIR', "Créez-le");
define('_CO_QUIZMAKER_DC_SETMPERM', "Définir l'autorisation");
define('_CO_QUIZMAKER_DC_DIRCREATED', "Le répertoire a été créé");
define('_CO_QUIZMAKER_DC_DIRNOTCREATED', "Le répertoire ne peut pas être créé");
define('_CO_QUIZMAKER_DC_PERMSET', "L'autorisation a été définie");
define('_CO_QUIZMAKER_DC_PERMNOTSET', "L'autorisation ne peut pas être définie");
define('_CO_QUIZMAKER_FORM_RESULT', "Resultats");
define('_CO_QUIZMAKER_FORM_ENCART', "Encart");
define('_CO_QUIZMAKER_FORM_INTRO', "Introduction");
define('_CO_QUIZMAKER_FORM_QUESTION', "Question");
define('_CO_QUIZMAKER_TYPE_CHECKBOX_DESC', "Ce slide est composé d''une question et de plusieurs cases à cocher.");
define('_CO_QUIZMAKER_TYPE_PAGEINFO_DESC', "Ce silde a plusieurs fonctionalité: Page d'introduction, encart et résultats.<br>Le type de page est défini dans les options du slide.\"
. \"<br><b>Introduction</b> : à placer impérativement en premier, il permet de présenter le quiz.\"
. \"<br><b>Encart</b> : placé n'importe ou il permet de regroupper les questions par thème. Il faut définir dans les questions l'encart de regrouprement.<br>Le changement de poids (ordre) entraine toues les questions enfants.<br>Il peut également afficher des résultats intermédiaires\"
. \"<br><b>Résultat</b> : Obligatoirement placé à la fin, il permet d'afficher le résultat du quiz et de l'enregistrer.");
define('_CO_QUIZMAKER_TYPE_PAGEINFO', "Page d'infomation");

define('_CO_QUIZMAKER_TYPE_PNN', "<br>Chaque option est associée un nombre de points positif null ou négatif.");
define('_CO_QUIZMAKER_TYPE_PAC', "<br>Ni la ponctuation, ni l'accentuation ni la casse ne sont prises en compte pour comparer le résultat");
define('_CO_QUIZMAKER_TYPE_CHECKBOX', "Question à réponses multiples");

define('_CO_QUIZMAKER_TYPE_CHECKBOXLOGICAL', "Question de logique à répopnses multiples");
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
define('_CO_QUIZMAKER_QUIZ_DESCRIPTION', "Description");
define('_CO_QUIZMAKER_QUIZ_LEGEND', "Légende");

define('_CO_QUIZMAKER_POINTS_FOR_ANSWER1', "Cette question comptait pour un score minimum de %1\$s points et un score maximum de %2\$s points.");
define('_CO_QUIZMAKER_POINTS_FOR_ANSWER2', "Cette question comptait pour un score minimum de %1\$s points et un score maximum de %2\$s points.");
define('_CO_QUIZMAKER_POINTS_UNDER_DEV', "<span style='color:red;'><b>Fonction en cours de développement.</b></span>");
define('_CO_QUIZMAKER_POINTS', "Points");
define('_CO_QUIZMAKER_SOMMAIRE', "Sommaire des questions");

define('_CO_QUIZMAKER_EXECUTION_NONE', "Pas d'exécution");
define('_CO_QUIZMAKER_EXECUTION_INLINE', "Exécution dans le site");
define('_CO_QUIZMAKER_EXECUTION_OUTLINE', "exécution hors du site");

?>