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
define('_LG_PLUGIN_ALPHASIMPLE', "Trouver les lettres ou expressions");
define('_LG_PLUGIN_ALPHASIMPLE_DESC', "Ce jeu consiste à trouver un ou plusieurs caractères ou expressions, qui réponde aux critères définis par la question..");
define('_LG_PLUGIN_ALPHASIMPLE_CONSIGNE', "Ce jeu consiste à trouver un ou plusieurs caractères pour modifier un mot ou une expression.<br>Action : Cliquez sur une lettre ou une expression proposée.<br>Dans le cas ou plusieurs lettres ou expressions à trouver, cliquez autant de fois que néssaire sur les lettres ou espressions proposées pour compplèter et trouver la solution.");
//---------------------------------------------------------------

define('_LG_PLUGIN_ALPHASIMPLE_INTRUS', "Expressions erronées");

define('_LG_PLUGIN_ALPHASIMPLE_LETTERS_DESC', <<<__exp__
Liste de caractères ou d'expressions qui vient complèter les propositions pour faciliter la saisie.
<br>Cette liste doit être séparée par un des caractères suivants :\",-|\"</b>
<br>Les boutons \"X#@\" permettent de remplir automatiquement avec des listes prédéfinies.
<br>Les éléments de cette liste valent 0 point.
<br>Quand cette liste est utilisée, Il n'est pas utile de les saisir dans la liste des propositions.
__exp__);

define('_LG_PLUGIN_DIRECTIVE_LIB', "Cliquez sur une ou plusieurs lettres ou chiffres.");
define('_LG_PLUGIN_ALPHASIMPLE_IGNORE_ACCENTS', "Ignorer les accents.");

?>
