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

define('_CO_QUIZMAKER_ADD_SAMPLEDATA', "Import sample data (will delete ALL current data)");
define('_CO_QUIZMAKER_ADD_SAMPLEDATA_OK', "Are you sure to import sample data? (This will delete ALL current data)");
define('_CO_QUIZMAKER_CATEGORIES', "Categories");
define('_CO_QUIZMAKER_CONFIRM', "Confirm");
define('_CO_QUIZMAKER_DC_AVAILABLE', "<span style='color: green;'>Available</span>");
define('_CO_QUIZMAKER_DC_CREATETHEDIR', "Create it");
define('_CO_QUIZMAKER_DC_DIRCREATED', "The directory has been created");
define('_CO_QUIZMAKER_DC_DIRNOTCREATED', "The directory cannot be created");
define('_CO_QUIZMAKER_DC_NOTAVAILABLE', "<span style='color: red;'>Not available</span>");
define('_CO_QUIZMAKER_DC_NOTWRITABLE', "<span style='color: red;'> should have permission ( %d ), but it does ( %d )</span>");
define('_CO_QUIZMAKER_DC_PERMNOTSET', "Permission cannot be set");
define('_CO_QUIZMAKER_DC_PERMSET', "Permission has been set");
define('_CO_QUIZMAKER_DC_SETMPERM', "Set permission");
define('_CO_QUIZMAKER_ERROR_BAD_DEL_PATH', "Unable to delete directory %s");
define('_CO_QUIZMAKER_ERROR_BAD_PHP', "This module requires PHP version %s+ (%s installed)");
define('_CO_QUIZMAKER_ERROR_BAD_XOOPS', "This module requires XOOPS %s+ (%s installed)");
define('_CO_QUIZMAKER_EXPORT_SCHEMA', "Export database schema to YAML");
define('_CO_QUIZMAKER_EXPORT_SCHEMA_ERROR', "ERROR: Failed to export database schema to YAML");
define('_CO_QUIZMAKER_EXPORT_SCHEMA_SUCCESS', "Exporting database schema to YAML was successful");
define('_CO_QUIZMAKER_FORM_GROUP', "Group");
define('_CO_QUIZMAKER_FORM_INTRO', "Introduction");
define('_CO_QUIZMAKER_FORM_QUESTION', "Question");
define('_CO_QUIZMAKER_FORM_RESULT', "Results");
define('_CO_QUIZMAKER_GDLIBSTATUS', "GD library support:");
define('_CO_QUIZMAKER_GDLIBVERSION', "GD library version:");
define('_CO_QUIZMAKER_GDOFF', "<span style='font-weight: bold;'>Disabled</span> (No thumbnail available)");
define('_CO_QUIZMAKER_GDON', "<span style='font-weight: bold;'>Enabled</span> (Thumbnails available)");
define('_CO_QUIZMAKER_GROUP', "Group");
define('_CO_QUIZMAKER_HOUR', "hour(s)");
define('_CO_QUIZMAKER_IMAGEINFO', "Server status");
define('_CO_QUIZMAKER_LEGEND', "Legend");
define('_CO_QUIZMAKER_LIB_BEGIN', "First slide button");
define('_CO_QUIZMAKER_LIB_BEGIN_DEFAULT', "Start the quiz");
define('_CO_QUIZMAKER_LIB_BEGIN_DESC', "Label of the first button to start the quiz");
define('_CO_QUIZMAKER_LIB_END', "Last slide button");
define('_CO_QUIZMAKER_LIB_END_DEFAULT', "Validate your score to see the answers");
define('_CO_QUIZMAKER_LIB_END_DESC', "Label of the button of the last slide of the quiz");
define('_CO_QUIZMAKER_MAXPOSTSIZE', "Maximum post size allowed (post_max_size directive in php.ini):");
define('_CO_QUIZMAKER_MAXUPLOADSIZE', "Maximum upload size allowed (upload_max_filesize directive in php.ini) :");
define('_CO_QUIZMAKER_MEMORYLIMIT', "Memory limit (memory_limit directive in php.ini) :");
define('_CO_QUIZMAKER_METAVERSION', "<span style='font-weight: bold;'>Download meta version:</span>");
define('_CO_QUIZMAKER_MINUTES', "minute(s)");
define('_CO_QUIZMAKER_NEW_VERSION', "New version:");
define('_CO_QUIZMAKER_NO_PERM', "You do not have permissions to access this feature!");
define('_CO_QUIZMAKER_NUM_NONE', "Without");
define('_CO_QUIZMAKER_OFF', "<span style='font-weight: bold;'>OFF</span>");
define('_CO_QUIZMAKER_ON', "<span style='font-weight: bold;'>ON</span>");
define('_CO_QUIZMAKER_PLUGIN', "Question type");
define('_CO_QUIZMAKER_PLUGIN_DESC', "You can change the type of question but be careful not all types are compatible<br>For example the radioSimple type is not compatible with the textboxSimple type.<br>The risk when changing the type is to lose the answers and having to re-enter them.");
define('_CO_QUIZMAKER_POINTS', "Points");
define('_CO_QUIZMAKER_POINTS_FOR_ANSWER2', 'This question counted for a minimum score of %1\$s points and a maximum score of %2\$s points.');
define('_CO_QUIZMAKER_POINTS_UNDER_DEV', "<span style='color:red;'><b>Function under development.</b></span>");
define('_CO_QUIZMAKER_PUBLISH_INLINE', "Running in site");
define('_CO_QUIZMAKER_PUBLISH_NONE', "No execution");
define('_CO_QUIZMAKER_PUBLISH_OUTLINE', "execution off site");
define('_CO_QUIZMAKER_PUBLISH_QUIZ', "Publishing the quiz");
define('_CO_QUIZMAKER_SAMPLEDATA_SUCCESS', "Sample date downloaded successfully");
define('_CO_QUIZMAKER_SAVE_SAMPLEDATA', "Export tables to YAML");
define('_CO_QUIZMAKER_SECONDS', "seconds");
define('_CO_QUIZMAKER_SEE_ANSWER', "See answers");
define('_CO_QUIZMAKER_SERVERPATH', "Server path to XOOPS root:");
define('_CO_QUIZMAKER_SERVERUPLOADSTATUS', "Server download status:");
define('_CO_QUIZMAKER_SOMMAIRE', "Summary of questions");
define('_CO_QUIZMAKER_SPHPINI', "<span style='font-weight: bold;'>Information extracted from the PHP ini file:</span>");
define('_CO_QUIZMAKER_TIME', "Date/Time");
define('_CO_QUIZMAKER_UPLOADPATHDSC', "Note. The upload path *MUST* contain the full server path of your upload folder.");

define('_CO_QUIZMAKER_PLUGIN_CAT_BASIC', "Basic questions");
define('_CO_QUIZMAKER_PLUGIN_CAT_DRAGANDDROP', "Click and drop");
define('_CO_QUIZMAKER_PLUGIN_CAT_IMAGES', "Easy images");
define('_CO_QUIZMAKER_PLUGIN_CAT_OTHER', "Other question types");
define('_CO_QUIZMAKER_PLUGIN_CAT_PAGE', "Start, end and grouping pages");
define('_CO_QUIZMAKER_PLUGIN_CAT_TEXT', "Texts");
define('_CO_QUIZMAKER_NEW', 'New');

?>
