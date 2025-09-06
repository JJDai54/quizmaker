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
define('_LG_PLUGIN_TEXTAREAMIXTE', "Correct the text");
define('_LG_PLUGIN_TEXTAREAMIXTE_DESC', "This slide contains a text box that must be corrected directly.");
define('_LG_PLUGIN_TEXTAREAMIXTE_CONSIGNE', "Instructions to complete");

define('_LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION', "Presentation");
define('_LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION_TEXTAREA', "Simple text box for direct editing");
define('_LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION_TEXTBOX', "Text box with as many input fields as there are words in curly brackets");
define('_LG_PLUGIN_TEXTAREAMIXTE_PRESENTATION_LISTBOX', "Text box with as many drop-down lists as there are words in curly braces, plus a list of incorrect words.");

define('_LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON', "Comparison");
define('_LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON_0', "Strict comparison (Exact word with accents)");
define('_LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON_1', "Comparison with optional accents (e.g., \"sérénade\" is equivalent to \"serênade\" is equivalent to \"serenade\".)");
define('_LG_PLUGIN_TEXTAREAMIXTE_COMPARAISON_2', "Soft comparison (Contained word with or without accents (e.g., \"sérénade\", \"serênade\" are equivalent to \"serenade\")");
define('_LG_PLUGIN_TEXTAREAMIXTE_SCORE_BY_WORD', "Number of points per word found");
define('_LG_PLUGIN_TEXTAREAMIXTE_ACCOLADES_ERR', "The number of opening and closing braces is different.");
define('_LG_PLUGIN_TEXTAREAMIXTE_REMOVE_ALERT', "Confirm the removal of all braces!");
define('_LG_PLUGIN_TEXTAREAMIXTE_ADD_ACCOLADES', "Add braces around the selection.");
define('_LG_PLUGIN_TEXTAREAMIXTE_REMOVE_ACCOLADES', "Remove braces around the selection");
define('_LG_PLUGIN_TEXTAREAMIXTE_CLEAR_ALL_ACCOLADES', "Remove all braces.");

define('_LG_PLUGIN_TEXTAREAMIXTE_ADD_BAD_EXP', "Add unwanted words or phrases.<br>These phrases are intended to confuse the user.<br><b>Important</b>: This list is only used with drop-down lists (see the \"Presentation\" option above).");
      
define('_LG_PLUGIN_TEXTAREAMIXTE_TOKEN_COLOR',"Token color");
define('_LG_PLUGIN_TEXTAREAMIXTE_WORD_COLOR',"Token color");
define('_LG_PLUGIN_TEXTAREAMIXTE_LINE_HEIGHT',"Line spacing height (em)");
define('_LG_PLUGIN_TEXTAREAMIXTE_FONT_SIZE',"Font size (em)");

?>
