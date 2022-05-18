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

define('_CO_QUIZMAKER_DC_AVAILABLE', "<span style='color: green;'>Available</span>");
define('_CO_QUIZMAKER_DC_NOTWRITABLE', "<span style='color: red;'> should have permission ( %d ), but it has ( %d )</span>");
define('_CO_QUIZMAKER_DC_NOTAVAILABLE', "<span style='color: red;'>Not available</span>");
define('_CO_QUIZMAKER_POINTS_UNDER_DEV', "<span style='color:red;'><b>Feature under development.</b></span>");
define('_CO_QUIZMAKER_GDOFF', "<span style='font-weight: bold;'>Disabled</span> (No thumbnails available)");
define('_CO_QUIZMAKER_METAVERSION', "<span style='font-weight: bold;'>Downloads meta version:</span> ");
define('_CO_QUIZMAKER_GDON', "<span style='font-weight: bold;'>Enabled</span> (Thumbsnails available)");
define('_CO_QUIZMAKER_SPHPINI', "<span style='font-weight: bold;'>Information taken from PHP ini file:</span>");
define('_CO_QUIZMAKER_OFF', "<span style='font-weight: bold;'>OFF</span>");
define('_CO_QUIZMAKER_ON', "<span style='font-weight: bold;'>ON</span>");
define('_CO_QUIZMAKER_ADD_SAMPLEDATA_OK', "Are you sure to Import Sample Data? (It will delete ALL current data)");
define('_CO_QUIZMAKER_CONFIRM', "Confirm");
define('_CO_QUIZMAKER_ERROR_BAD_DEL_PATH', "Could not delete %s directory");
define('_CO_QUIZMAKER_DC_CREATETHEDIR', "Create it");
define('_CO_QUIZMAKER_EXPORT_SCHEMA_ERROR', "ERROR: Export of DB Schema to YAML failed");
define('_CO_QUIZMAKER_PUBLISH_INLINE', "Execution in site");
define('_CO_QUIZMAKER_EXPORT_SCHEMA', "Export DB Schema to YAML");
define('_CO_QUIZMAKER_EXPORT_SCHEMA_SUCCESS', "Export DB Schema to YAML was a success");
define('_CO_QUIZMAKER_SAVE_SAMPLEDATA', "Export Tables to YAML");
define('_CO_QUIZMAKER_GDLIBSTATUS', "GD library support: ");
define('_CO_QUIZMAKER_GDLIBVERSION', "GD Library version: ");
define('_CO_QUIZMAKER_SHOW_SAMPLE_BUTTON_DESC', "If yes, the \"Add Sample Data\" button will be visible to the Admin. It is Yes as a default for first installation.");
define('_CO_QUIZMAKER_ADD_SAMPLEDATA', "Import Sample Data (will delete ALL current data)");
define('_CO_QUIZMAKER_LEGEND', "Legend");
define('_CO_QUIZMAKER_MAXPOSTSIZE', "Max post size permitted (post_max_size directive in php.ini): ");
define('_CO_QUIZMAKER_MAXUPLOADSIZE', "Max upload size permitted (upload_max_filesize directive in php.ini): ");
define('_CO_QUIZMAKER_MEMORYLIMIT', "Memory limit (memory_limit directive in php.ini): ");
define('_CO_QUIZMAKER_NEW_VERSION', "New Version: ");
define('_CO_QUIZMAKER_PUBLISH_NONE', "No execution");
define('_CO_QUIZMAKER_UPLOADPATHDSC', "Note. Upload path *MUST* contain the full server path of your upload folder.");
define('_CO_QUIZMAKER_PUBLISH_OUTLINE', "off-site execution");
define('_CO_QUIZMAKER_POINTS', "Points");
define('_CO_QUIZMAKER_FORM_RESULT', "Results");
define('_CO_QUIZMAKER_SAMPLEDATA_SUCCESS', "Sample Date uploaded successfully");
define('_CO_QUIZMAKER_SERVERPATH', "Server path to XOOPS root: ");
define('_CO_QUIZMAKER_IMAGEINFO', "Server status");
define('_CO_QUIZMAKER_SERVERUPLOADSTATUS', "Server uploads status: ");
define('_CO_QUIZMAKER_DC_SETMPERM', "Set the permission");
define('_CO_QUIZMAKER_SHOW_SAMPLE_BUTTON', "Show Sample Button?");
define('_CO_QUIZMAKER_SOMMAIRE', "Summary of questions");
define('_CO_QUIZMAKER_DC_DIRNOTCREATED', "The directory cannot be created");
define('_CO_QUIZMAKER_DC_DIRCREATED', "The directory has been created");
define('_CO_QUIZMAKER_DC_PERMNOTSET', "The permission cannot be set");
define('_CO_QUIZMAKER_DC_PERMSET', "The permission has been set");
define('_CO_QUIZMAKER_ERROR_BAD_PHP', "This module requires PHP version %s+ (%s installed)");
define('_CO_QUIZMAKER_ERROR_BAD_XOOPS', "This module requires XOOPS %s+ (%s installed)");
define('_CO_QUIZMAKER_POINTS_FOR_ANSWER2', 'This question counted for a minimum score of %1\$s points and a maximum score of %2\$s points.');

?>