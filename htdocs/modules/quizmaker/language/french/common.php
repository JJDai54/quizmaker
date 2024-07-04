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

define('_CO_QUIZMAKER_DC_AVAILABLE', "<span style='color: green;'>Disponible</span>");
define('_CO_QUIZMAKER_DC_NOTWRITABLE', "<span style='color: red;'> devrait avoir l'autorisation ( %d ), mais il l'a ( %d )</span>");
define('_CO_QUIZMAKER_DC_NOTAVAILABLE', "<span style='color: red;'>Non disponible</span>");
define('_CO_QUIZMAKER_POINTS_UNDER_DEV', "<span style='color:red;'><b>Fonction en cours de développement.</b></span>");
define('_CO_QUIZMAKER_GDOFF', "<span style='font-weight: bold;'>Désactivé</span> (Aucune miniature disponible)");
define('_CO_QUIZMAKER_METAVERSION', "<span style='font-weight: bold;'>Télécharge la version méta :</span>");
define('_CO_QUIZMAKER_GDON', "<span style='font-weight: bold;'>Activé</span> (Miniatures disponibles)");
define('_CO_QUIZMAKER_SPHPINI', "<span style='font-weight: bold;'>Informations extraites du fichier ini PHP :</span>");
define('_CO_QUIZMAKER_OFF', "<span style='font-weight: bold;'>OFF</span>");
define('_CO_QUIZMAKER_ON', "<span style='font-weight: bold;'>ON</span>");
define('_CO_QUIZMAKER_ADD_SAMPLEDATA_OK', "Êtes-vous sûr d'importer des exemples de données ? (Cela supprimera TOUTES les données actuelles)");
define('_CO_QUIZMAKER_CONFIRM', "Confirmer");
define('_CO_QUIZMAKER_ERROR_BAD_DEL_PATH', "Impossible de supprimer le répertoire %s");
define('_CO_QUIZMAKER_DC_CREATETHEDIR', "Créez-le");
define('_CO_QUIZMAKER_EXPORT_SCHEMA_ERROR', "ERREUR : Échec de l'exportation du schéma de base de données vers YAML");
define('_CO_QUIZMAKER_PUBLISH_INLINE', "Exécution dans le site");
define('_CO_QUIZMAKER_EXPORT_SCHEMA', "Exporter le schéma de base de données vers YAML");
define('_CO_QUIZMAKER_EXPORT_SCHEMA_SUCCESS', "Exporter le schéma de base de données vers YAML a été un succès");
define('_CO_QUIZMAKER_SAVE_SAMPLEDATA', "Exporter des tableaux vers YAML");
define('_CO_QUIZMAKER_GDLIBSTATUS', "Prise en charge de la bibliothèque GD :");
define('_CO_QUIZMAKER_GDLIBVERSION', "Version de la bibliothèque GD :");
define('_CO_QUIZMAKER_ADD_SAMPLEDATA', "Importer des exemples de données (supprimera TOUTES les données actuelles)");
define('_CO_QUIZMAKER_LEGEND', "Légende");
define('_CO_QUIZMAKER_MAXPOSTSIZE', "Taille maximale de publication autorisée (directive post_max_size dans php.ini) :");
define('_CO_QUIZMAKER_MAXUPLOADSIZE', "Taille de téléchargement maximale autorisée (directive upload_max_filesize dans php.ini) :");
define('_CO_QUIZMAKER_MEMORYLIMIT', "Limite de mémoire (directive memory_limit dans php.ini) :");
define('_CO_QUIZMAKER_NEW_VERSION', "Nouvelle version:");
define('_CO_QUIZMAKER_PUBLISH_NONE', "Pas d'exécution");
define('_CO_QUIZMAKER_UPLOADPATHDSC', "Noter. Le chemin de téléchargement *DOIT* contenir le chemin complet du serveur de votre dossier de téléchargement.");
define('_CO_QUIZMAKER_PUBLISH_OUTLINE', "exécution hors du site");
define('_CO_QUIZMAKER_GROUP', "Groupe");
define('_CO_QUIZMAKER_POINTS', "Points");
define('_CO_QUIZMAKER_FORM_RESULT', "Resultats");
define('_CO_QUIZMAKER_SAMPLEDATA_SUCCESS', "Exemple de date téléchargé avec succès");
define('_CO_QUIZMAKER_SERVERPATH', "Chemin du serveur vers la racine XOOPS :");
define('_CO_QUIZMAKER_IMAGEINFO', "État du serveur");
define('_CO_QUIZMAKER_SERVERUPLOADSTATUS', "État des téléchargements du serveur :");
define('_CO_QUIZMAKER_DC_SETMPERM', "Définir l'autorisation");
//define('_CO_QUIZMAKER_SHOW_SAMPLE_BUTTON', "Afficher le bouton d'exemple ?");
//define('_CO_QUIZMAKER_SHOW_SAMPLE_BUTTON_DESC', "Si oui, le bouton \"Ajouter des données d'échantillon\" sera visible par l'administrateur. C'est Oui par défaut pour la première installation.");
define('_CO_QUIZMAKER_SOMMAIRE', "Sommaire des questions");
define('_CO_QUIZMAKER_DC_DIRNOTCREATED', "Le répertoire ne peut pas être créé");
define('_CO_QUIZMAKER_DC_DIRCREATED', "Le répertoire a été créé");
define('_CO_QUIZMAKER_DC_PERMNOTSET', "L'autorisation ne peut pas être définie");
define('_CO_QUIZMAKER_DC_PERMSET', "L'autorisation a été définie");
define('_CO_QUIZMAKER_ERROR_BAD_PHP', "Ce module nécessite la version PHP %s+ (%s installé)");
define('_CO_QUIZMAKER_ERROR_BAD_XOOPS', "Ce module nécessite XOOPS %s+ (%s installé)");
define('_CO_QUIZMAKER_POINTS_FOR_ANSWER2', "Cette question comptait pour un score minimum de %1\$s points et un score maximum de %2\$s points.");
define('_CO_QUIZMAKER_FORM_GROUP', "Groupe");
define('_CO_QUIZMAKER_PUBLISH_QUIZ', "Publication du quiz");
define('_CO_QUIZMAKER_FORM_INTRO', "Introduction");
define('_CO_QUIZMAKER_FORM_QUESTION', "Question");
define('_CO_QUIZMAKER_TYPE_QUESTION', "Type de question");
define('_CO_QUIZMAKER_TYPE_QUESTION_DESC', "Vos pouvez changer le type de question mais attention tous les types ne sont pas compatibles<br>Par exemple le type radioSimple n'est pas compatible avec le type textboxSimple.<br>Le risque quand on change le type est de perdre les réponses et de devoir les ressaisir.");
define('_CO_QUIZMAKER_TYPE_QUESTION_2_ADD', "Type de question à ajouter");
define('_CO_QUIZMAKER_CATEGORIES', "Catégories");
define('_CO_QUIZMAKER_SEE_ANSWER', "Voir les réponses");
define('_CO_QUIZMAKER_TIME', "Date/Heure");
define('_CO_QUIZMAKER_HOUR', "heure(s)");
define('_CO_QUIZMAKER_MINUTES', "minute(s)");
define('_CO_QUIZMAKER_SECONDS', "secondes");
define('_CO_QUIZMAKER_NUM_NONE', "Sans");

define('_CO_QUIZMAKER_LIB_BEGIN', "Bouton du premier slide");
define('_CO_QUIZMAKER_LIB_BEGIN_DEFAULT', "Lancez le quiz");
define('_CO_QUIZMAKER_LIB_BEGIN_DESC', "Libellé du premier bouton pour lancer le quiz");

define('_CO_QUIZMAKER_LIB_END', "Bouton du dernier slide");
define('_CO_QUIZMAKER_LIB_END_DEFAULT', "Validez votre score pour voir les réponses");
define('_CO_QUIZMAKER_LIB_END_DESC', "Libellé du bouton du dernier slide du quiz");

define('_CO_QUIZMAKER_NO_PERM', "Vous n'avez pas les permissions pour accéder à cette fonctionalité !");
//define('_CO_QUIZMAKER_NO_PERM2', "Vous n'avez pas les permissions pour accéder à cette fonctionalité !<br>Permission=\"%s\" - %s=\"%s\" ");
define('_CO_QUIZMAKER_NO_PERM2', "Vous n'avez pas les permissions pour accéder à cette fonctionalité !<br>Permission=%s ===> %s=%s");
?>
