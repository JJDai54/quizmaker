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

define('_CO_QUIZMAKER_GDLIBSTATUS', "GD library support: ");
define('_CO_QUIZMAKER_GDLIBVERSION', "GD Library version: ");
define('_CO_QUIZMAKER_GDOFF', "<span style='font-weight: bold;'>Disabled</span> (No thumbnails available)");
define('_CO_QUIZMAKER_GDON', "<span style='font-weight: bold;'>Enabled</span> (Thumbsnails available)");
define('_CO_QUIZMAKER_IMAGEINFO', "Server status");
define('_CO_QUIZMAKER_MAXPOSTSIZE', "Max post size permitted (post_max_size directive in php.ini): ");
define('_CO_QUIZMAKER_MAXUPLOADSIZE', "Max upload size permitted (upload_max_filesize directive in php.ini): ");
define('_CO_QUIZMAKER_MEMORYLIMIT', "Memory limit (memory_limit directive in php.ini): ");
define('_CO_QUIZMAKER_METAVERSION', "<span style='font-weight: bold;'>Downloads meta version:</span> ");
define('_CO_QUIZMAKER_OFF', "<span style='font-weight: bold;'>OFF</span>");
define('_CO_QUIZMAKER_ON', "<span style='font-weight: bold;'>ON</span>");
define('_CO_QUIZMAKER_SERVERPATH', "Server path to XOOPS root: ");
define('_CO_QUIZMAKER_SERVERUPLOADSTATUS', "Server uploads status: ");
define('_CO_QUIZMAKER_SPHPINI', "<span style='font-weight: bold;'>Information taken from PHP ini file:</span>");
define('_CO_QUIZMAKER_UPLOADPATHDSC', "Note. Upload path *MUST* contain the full server path of your upload folder.");
define('_CO_QUIZMAKER_PRINT', "<span style='font-weight: bold;'>Print</span>");
define('_CO_QUIZMAKER_PDF', "<span style='font-weight: bold;'>Create PDF</span>");
define('_CO_QUIZMAKER_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
define('_CO_QUIZMAKER_UPGRADEFAILED1', "Update failed - couldn't add new fields");
define('_CO_QUIZMAKER_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");
define('_CO_QUIZMAKER_ERROR_COLUMN', "Could not create column in database : %s");
define('_CO_QUIZMAKER_ERROR_BAD_XOOPS', "This module requires XOOPS %s+ (%s installed)");
define('_CO_QUIZMAKER_ERROR_BAD_PHP', "This module requires PHP version %s+ (%s installed)");
define('_CO_QUIZMAKER_ERROR_TAG_REMOVAL', "Could not remove tags from Tag Module");
define('_CO_QUIZMAKER_FOLDERS_DELETED_OK', "Upload Folders have been deleted");
define('_CO_QUIZMAKER_ERROR_BAD_DEL_PATH', "Could not delete %s directory");
define('_CO_QUIZMAKER_ERROR_BAD_REMOVE', "Could not delete %s");
define('_CO_QUIZMAKER_ERROR_NO_PLUGIN', "Could not load plugin");
define('_CO_QUIZMAKER_BACK_2_ADMIN', "Back to Administration of ");
define('_CO_QUIZMAKER_OVERVIEW', "Overview");
define('_CO_QUIZMAKER_DISCLAIMER', "Disclaimer");
define('_CO_QUIZMAKER_LICENSE', "License");
define('_CO_QUIZMAKER_SUPPORT', "Support");
define('_CO_QUIZMAKER_ADD_SAMPLEDATA', "Import Sample Data (will delete ALL current data)");
define('_CO_QUIZMAKER_SAMPLEDATA_SUCCESS', "Sample Date uploaded successfully");
define('_CO_QUIZMAKER_SAVE_SAMPLEDATA', "Export Tables to YAML");
define('_CO_QUIZMAKER_SHOW_SAMPLE_BUTTON', "Show Sample Button?");
define('_CO_QUIZMAKER_SHOW_SAMPLE_BUTTON_DESC', "If yes, the \"Add Sample Data\" button will be visible to the Admin. It is Yes as a default for first installation.");
define('_CO_QUIZMAKER_EXPORT_SCHEMA', "Export DB Schema to YAML");
define('_CO_QUIZMAKER_EXPORT_SCHEMA_SUCCESS', "Export DB Schema to YAML was a success");
define('_CO_QUIZMAKER_EXPORT_SCHEMA_ERROR', "ERROR: Export of DB Schema to YAML failed");
define('_CO_QUIZMAKER_ADD_SAMPLEDATA_OK', "Are you sure to Import Sample Data? (It will delete ALL current data)");
define('_CO_QUIZMAKER_HIDE_SAMPLEDATA_BUTTONS', "Hide the Import buttons");
define('_CO_QUIZMAKER_SHOW_SAMPLEDATA_BUTTONS', "Show the Import buttons");
define('_CO_QUIZMAKER_CONFIRM', "Confirm");
define('_CO_QUIZMAKER_BROWSETOTOPIC', "<span style='font-weight: bold;'>Browse items alphabetically</span>");
define('_CO_QUIZMAKER_OTHER', "Other");
define('_CO_QUIZMAKER_ALL', "All");
define('_CO_QUIZMAKER_ACCESSRIGHTS', "Access Rights");
define('_CO_QUIZMAKER_ACTION', "Action");
define('_CO_QUIZMAKER_ACTIVERIGHTS', "Active Rights");
define('_CO_QUIZMAKER_BADMIN', "Block Administration");
define('_CO_QUIZMAKER_BLKDESC', "Description");
define('_CO_QUIZMAKER_CBCENTER', "Center Middle");
define('_CO_QUIZMAKER_CBLEFT', "Center Left");
define('_CO_QUIZMAKER_CBRIGHT', "Center Right");
define('_CO_QUIZMAKER_SBLEFT', "Left");
define('_CO_QUIZMAKER_SBRIGHT', "Right");
define('_CO_QUIZMAKER_SIDE', "Alignment");
define('_CO_QUIZMAKER_TITLE', "Title");
define('_CO_QUIZMAKER_VISIBLE', "Visible");
define('_CO_QUIZMAKER_VISIBLEIN', "Visible In");
define('_CO_QUIZMAKER_WEIGHT', "Weight");
define('_CO_QUIZMAKER_PERMISSIONS', "Permissions");
define('_CO_QUIZMAKER_BLOCKS', "Blocks Admin");
define('_CO_QUIZMAKER_BLOCKS_DESC', "Blocks/Group Admin");
define('_CO_QUIZMAKER_BLOCKS_MANAGMENT', "Manage");
define('_CO_QUIZMAKER_BLOCKS_ADDBLOCK', "Add a new block");
define('_CO_QUIZMAKER_BLOCKS_EDITBLOCK', "Edit a block");
define('_CO_QUIZMAKER_BLOCKS_CLONEBLOCK', "Clone a block");
define('_CO_QUIZMAKER_AGDS', "Admin Groups");
define('_CO_QUIZMAKER_BCACHETIME', "Cache Time");
define('_CO_QUIZMAKER_BLOCKS_ADMIN', "Blocks Admin");
define('_CO_QUIZMAKER_TPLSETS', "Template Management");
define('_CO_QUIZMAKER_GENERATE', "Generate");
define('_CO_QUIZMAKER_FILENAME', "File Name");
define('_CO_QUIZMAKER_ADMENU_MIGRATE', "Migrate");
define('_CO_QUIZMAKER_FOLDER_YES', "Folder \"%s\" exist");
define('_CO_QUIZMAKER_FOLDER_NO', "Folder \"%s\" does not exist. Create the specified folder with CHMOD 777.");
define('_CO_QUIZMAKER_SHOW_DEV_TOOLS', "Show Development Tools Button?");
define('_CO_QUIZMAKER_SHOW_DEV_TOOLS_DESC', "If yes, the \"Migrate\" Tab and other Development tools will be visible to the Admin.");
define('_CO_QUIZMAKER_ADMENU_FEEDBACK', "Feedback");
define('_CO_QUIZMAKER_NEW_VERSION', "New Version: ");
define('_CO_QUIZMAKER_AVAILABLE', "<span style='color: green;'>Available</span>");
define('_CO_QUIZMAKER_NOTAVAILABLE', "<span style='color: red;'>Not available</span>");
define('_CO_QUIZMAKER_NOTWRITABLE', "<span style='color: red;'>Should have permission ( %d ), but it has ( %d )</span>");
define('_CO_QUIZMAKER_CREATETHEDIR', "Create it");
define('_CO_QUIZMAKER_SETMPERM', "Set the permission");
define('_CO_QUIZMAKER_DIRCREATED', "The directory has been created");
define('_CO_QUIZMAKER_DIRNOTCREATED', "The directory cannot be created");
define('_CO_QUIZMAKER_PERMSET', "The permission has been set");
define('_CO_QUIZMAKER_PERMNOTSET', "The permission cannot be set");
define('_CO_QUIZMAKER_FILECOPIED', "The file has been copied");
define('_CO_QUIZMAKER_FILENOTCOPIED', "The file cannot be copied");
define('_CO_QUIZMAKER_IMAGE_WIDTH', "Image Display Width");
define('_CO_QUIZMAKER_IMAGE_WIDTH_DSC', "Display width for image");
define('_CO_QUIZMAKER_IMAGE_HEIGHT', "Image Display Height");
define('_CO_QUIZMAKER_IMAGE_HEIGHT_DSC', "Display height for image");
define('_CO_QUIZMAKER_IMAGE_CONFIG', "<span style=\"color: #FF0000; font-size: Small;  font-weight: bold;\">--- EXTERNAL Image configuration ---</span> ");
define('_CO_QUIZMAKER_IMAGE_CONFIG_DSC', "");
define('_CO_QUIZMAKER_IMAGE_UPLOAD_PATH', "Image Upload path");
define('_CO_QUIZMAKER_IMAGE_UPLOAD_PATH_DSC', "Path for uploading images");
define('_CO_QUIZMAKER_TRUNCATE_LENGTH', "Number of Characters to truncate to the long text field");
define('_CO_QUIZMAKER_TRUNCATE_LENGTH_DESC', "Set the maximum number of characters to truncate the long text fields");
define('_CO_QUIZMAKER_STATS_SUMMARY', "Module Statistics");
define('_CO_QUIZMAKER_TOTAL_CATEGORIES', "Categories:");
define('_CO_QUIZMAKER_TOTAL_ITEMS', "Items");
define('_CO_QUIZMAKER_TOTAL_OFFLINE', "Offline");
define('_CO_QUIZMAKER_TOTAL_PUBLISHED', "Published");
define('_CO_QUIZMAKER_TOTAL_REJECTED', "Rejected");
define('_CO_QUIZMAKER_TOTAL_SUBMITTED', "Submitted");
define('_CO_QUIZMAKER_DC_AVAILABLE', "<span style='color: green;'>Available</span>");
define('_CO_QUIZMAKER_DC_NOTAVAILABLE', "<span style='color: red;'>Not available</span>");
define('_CO_QUIZMAKER_DC_NOTWRITABLE', "<span style='color: red;'> should have permission ( %d ), but it has ( %d )</span>");
define('_CO_QUIZMAKER_DC_CREATETHEDIR', "Create it");
define('_CO_QUIZMAKER_DC_SETMPERM', "Set the permission");
define('_CO_QUIZMAKER_DC_DIRCREATED', "The directory has been created");
define('_CO_QUIZMAKER_DC_DIRNOTCREATED', "The directory cannot be created");
define('_CO_QUIZMAKER_DC_PERMSET', "The permission has been set");
define('_CO_QUIZMAKER_DC_PERMNOTSET', "The permission cannot be set");


?>