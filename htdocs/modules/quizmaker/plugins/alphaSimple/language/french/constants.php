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
define('_LG_PLUGIN_ALPHASIMPLE', "Question de lettres");
define('_LG_PLUGIN_ALPHASIMPLE_DESC', "Ce slide est composé d'une question et de plusieurs caractères ou expressions alpha-nummériques à sélectionner.");

define('_LG_PLUGIN_ALPHASIMPLE_CONSIGNE', "Sélectionnez un ou plusieurs caractères ou expressions alpha-numérique qui correspond à la question.");
define('_LG_PLUGIN_ALPHASIMPLE_INTRUS', "Expressions erronées");

define('_LG_PLUGIN_ALPHASIMPLE_LETTERS_DESC', <<<__exp__
Liste de caractères ou d'expressions qui vient complèter les propositions pour faciliter la saisie.
<br>Cette liste doit être séparée par un des caractères suivants :\",-|\"</b>
<br>Les boutons \"X#@\" permettent de remplir automatiquement avec des listes prédéfinies.
<br>Les éléments de cette liste valent 0 point.
<br>Quand cette liste est utilisée, Il n'est pas utile de les saisir dans la liste des propositions.
__exp__);

define('_LG_PLUGIN_ALPHASIMPLE_DIRECTIVE', "Directive");
define('_LG_PLUGIN_ALPHASIMPLE_DIRECTIVE_DESC', "Action à faire affichée au dessus des lettres.");
define('_LG_PLUGIN_ALPHASIMPLE_DIRECTIVE_LIB', "Cliquez sur une ou plusieurs lettres ou chiffres.");
define('_LG_PLUGIN_ALPHASIMPLE_IGNORE_ACCENTS', "Ignorer les accents.");

define('_LG_PLUGIN_ALPHASIMPLE_NEXT_SLIDE', "Message");        
define('_LG_PLUGIN_ALPHASIMPLE_NEXT_SLIDE_DESC', "Message affiché lors du passage au slide suivant en mose automatique");        
define('_LG_PLUGIN_ALPHASIMPLE_NEXT_SLIDE0', "Bravo !");        

?>
