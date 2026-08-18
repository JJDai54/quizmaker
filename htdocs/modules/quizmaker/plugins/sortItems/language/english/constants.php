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
define('_LG_PLUGIN_SORTITEMS', "Sort a list of expressions or images");
define('_LG_PLUGIN_SORTITEMS_DESC', "This slide consists of a list and movement buttons.<br>The sorting can be reversed.");
define('_LG_PLUGIN_SORTITEMS_CONSIGNE', "You must sort the list according to the criteria stated in the question.<br>Select an item then click the side buttons to move it up or down.<br>Depending on the options chosen, the list must be sorted in strict order or not, meaning the order can be reversed.");

define('_LG_PLUGIN_SORTITEMS_OPTIONS_LISTBOX', "Options specific to single selection lists (listbox)");
define('_LG_PLUGIN_SORTITEMS_OPTIONS_LISTUL', "Options specific to bulleted lists (Drag and Drop of labels)");

define('_LG_PLUGIN_SORTITEMS_OPTIONS_DADIMAGE', "Options specific to images to move (Drag and Drop of images)");

define('_LG_PLUGIN_SORTITEMS_BTN_HEIGHT', "Height of the movement buttons");

define('_LG_PLUGIN_SORTITEMS_VARIANT', "Class");

define('_LG_PLUGIN_SORTITEMS_VARIANT_LISTBOX', "Listbox");

define('_LG_PLUGIN_SORTITEMS_VARIANT_COMBOBOX', "Combobox");

define('_LG_PLUGIN_SORTITEMS_VARIANT_LISTUL', "Bulleted list");
define('_LG_PLUGIN_SORTITEMS_VARIANT_IMAGEDAD', "Images");
define('_LG_PLUGIN_SORTITEMS_VARIANT_IMAGEDAD_FH', "Images fixed height");
define('_LG_PLUGIN_SORTITEMS_VARIANT_IMAGEDAD_WH', "Images fixed width");


define('_AP_QUIZMAKER_VARIANT_SELECT', "Select a class, validate and reload the form");
define('_LG_PLUGIN_SORTITEMS_VARIANT_DESC', "The principle is the same regardless of the structure; it involves sorting a list that comes in different forms:"
. "<br><b>" . _LG_PLUGIN_SORTITEMS_VARIANT_LISTBOX . "</b> : List of expressions with movement buttons"
. "<br><b>" . _LG_PLUGIN_SORTITEMS_VARIANT_COMBOBOX . "</b> : As many drop-down lists as expressions to sort, filled with all the expressions"
. "<br><b>" . _LG_PLUGIN_SORTITEMS_VARIANT_LISTUL . "</b> : Bulleted list to reorder by clicking and moving the expressions (Drag and Drop labels)"
. "<br><b>" . _LG_PLUGIN_SORTITEMS_VARIANT_IMAGEDAD . "</b>: Images to rearrange by clicking and dragging them (Drag and Drop images)"
. "<br><span style='color:red;'><b>Important:</b> Confirm this option before proceeding with the rest of the settings to refresh the display according to the chosen option.</span>"
. "<br><span style='color:red;'>To display the settings according to the selected option, click on <b>\"Submit and reload the question\"</b>.</span>"
. "<br><span style='color:red;'>It is always possible to change them later, but at the risk of having to reconfigure the new options that were not available and losing the others.</span>");

define('_LG_PLUGIN_SORTITEMS_DIRECTIVE', "Directive");
define('_LG_PLUGIN_SORTITEMS_DIRECTIVE_DESC', "Action to perform displayed on the slide.");
define('_LG_PLUGIN_SORTITEMS_DIRECTIVE_LIB', "Click on the images and drag them to order them.");
define('_LG_PLUGIN_SORTITEMS_IMG1_HEIGHT', "Height of the images to move");
define('_LG_PLUGIN_SORTITEMS_IMG2_HEIGHT', "Height of the reference images");
define('_LG_PLUGIN_SORTITEMS_IMG1_WIDTH', "Width of images");

define('_LG_PLUGIN_SORTITEMS_FLIP', "Swaps the images");
define('_LG_PLUGIN_SORTITEMS_INSERT', "Inserts and shifts images without a square");
define('_LG_PLUGIN_SORTITEMS_CARET', "Inserts and shifts images with caret");

define('_LG_PLUGIN_SORTITEMS_IMAGE_TO_SORT', "Images to sort");
define('_LG_PLUGIN_SORTITEMS_IMAGE_REFERANTE', "Reference images");

?>
