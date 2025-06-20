<?php
//namespace XoopsModules\Quizmaker;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Quizmaker module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        quizmaker
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<https://xoopsfr.kiolo.fr>
 */

use XoopsModules\Quizmaker AS FQUIZMAKER;
include_once QUIZMAKER_PATH_MODULE . "/class/Plugins.php";

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answers
 */
class Plugin_findObjects extends XoopsModules\Quizmaker\Plugins
{
var $maxGroups = 4;     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("findObjects", 0, "images");
        $this->setVersion('1.2', '2025-04-20', 'JJDai (jjd@orange.fr)');
        $this->integration=1;
        $this->hasZoom = true;
        $this->hasImageMain = true;
        $this->multiPoints = true;                
        $this->maxPropositions = 16;	
        $this->optionsDefaults = ['disposition'   => 1,
                                  'imgWidth1'     => 250,
                                  'imgWidth2'     => 250,
                                  'showCaption'   => 'N',
                                  'message_info'  => _LG_PLUGIN_FINDOBJECTS_INFO,
                                  'maxTouches'    => 8,
                                  'maxAttemps'        => 0,
                                  'defaultWidth'  => 36,
                                  'defaultHeight' => 36,
                                  'defaultBorderWidth'  =>  3,
                                  'defaultBorderRadius' => 25,
                                  'nextSlideDelai'      => 0,
                                  'nextSlideBG'         =>'#FFCC00',
                                  'nextSlideMessageWin' => _LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN0,
                                  'nextSlideMessageMax' => _LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX0];
    }

	/**
	 * @static function &getInstance
	 *
	 * @param null
	 */
	public static function getInstance()
	{
		static $instance = false;
		if (!$instance) {
			$instance = new self();
		}
    }

/* **********************************************************
*
* *********************************************************** */
	public function loadJS()
	{
        $jsArr =  array('Touches','findObject_events','messages-fr','colorPicker');
        $jsPath = QUIZMAKER_PATH_PLUGINS_PHP . "/{$this->pluginName}/js/";
        $jsUrl = QUIZMAKER_URL_PLUGINS_PHP   . "/{$this->pluginName}/js/";  
        
        // chargement du fichier "Touches.js" qui se trouve dans quiz-org
        // pour eviter un doublon a maintenir
        $url = QUIZMAKER_URL_ASSETS . "/js/quiz-org/plugins/findObjects/Touche.js";
        $GLOBALS['xoTheme']->addScript($url);
        
        $url = QUIZMAKER_URL_ASSETS . "/js/quiz-org/plugins/findObjects/Touches.js";
        $GLOBALS['xoTheme']->addScript($url);
        
        //chargement des autres JS utilisé uniquement dans l'admin du module
        foreach($jsArr as $k=>$js){
            $f = $jsPath . $js . ".js";  
             // echo "javascript du plugin : {$f}<hr>";
            if (file_exists($f)){
              $url = $jsUrl . $js . ".js";  
              //echo "javascript du plugin <br>{$jsPath}<br>{$url}<hr>";
              $GLOBALS['xoTheme']->addScript($url);
            }
        }

    }

/* **********************************************************
*
* *********************************************************** */
 	public function getFormOptions($caption, $optionName, $jsonValues = null, $folderJS = "")
 	{    
      $tValues = $this->getOptions($jsonValues, $this->optionsDefaults);
      $trayOptions = $this->getNewXFTableOptions($caption);  
      //--------------------------------------------------------------------           

      $name = 'maxAttemps';
      $inpMaxAttemps = new \XoopsFormNumber(_LG_PLUGIN_FINDOBJECTS_MAXTRY,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name], 'style="background:#FFCC66;"');
      $inpMaxAttemps->setMinMax(0, 54, _AM_QUIZMAKER_UNIT_ATTEMPTS);
      $inpMaxAttemps->setDescription(_LG_FINDOBJECTS_IMAGES_ATTEMPTS_MAX_DESC);
      $trayOptions ->addElementOption($inpMaxAttemps);  
      
      // disposition 
      include (QUIZMAKER_PATH_MODULE . "/include/plugin_options_disposition.php");

      $name = 'imgWidth1';
      $inpWidth1 = new \XoopsFormNumber(sprintf(_LG_PLUGIN_FINDOBJECTS_WIDTH,1),  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpWidth1->setMinMax(32, 600, _AM_QUIZMAKER_UNIT_PIXELS);
      $trayOptions->addElementOption($inpWidth1);     

      $name = 'imgWidth2';
      $inpWidth2 = new \XoopsFormNumber(sprintf(_LG_PLUGIN_FINDOBJECTS_WIDTH,2),  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpWidth2->setMinMax(32, 600, _AM_QUIZMAKER_UNIT_PIXELS);
      $trayOptions->addElementOption($inpWidth2);     

      //---------------------------------------------------------
      // avertissement
      //ajout des message d'avertissement au passge du slide suivant  
      // un message en cs de victoire et un si le nombre d'essais est dépassé  
      $arrConst = ['nextSlideMessageWin' => '_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_WIN', 
                   'nextSlideMessageMax' => '_LG_PLUGIN_FINDOBJECTS_NEXT_QUESTION_MAX'];
      include (QUIZMAKER_PATH_MODULE . "/include/plugin_options_avertissement.php");
      //---------------------------------------------------------
      
      $trayOptions->insertBreak("<div style='background:#99CCFF;width:100%;padding:0px;margin:0px;'><center><b>" . _LG_FINDOBJECTS_TOUCHES_PARAMS . "</b></center></div>",-1,false);

      //remplacé par une valeur masquée égale à "0" avant suppression déinitive si il s'avère que c'est vraiment inutile            
      $name = 'maxTouches';
//       $inpMaxTouches = new \XoopsFormNumber(_LG_PLUGIN_FINDOBJECTS_MAXTOUCHES,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
//       $inpMaxTouches->setMinMax(0, 36);
      $inpMaxTouches = new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions ->addElementOption($inpMaxTouches);  
      
      //-----------------------------------------------

      $name = 'defaultWidth';
      $inpWidth = new \XoopsFormNumber(_LG_FINDOBJECTS_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpWidth->setMinMax(5, 250);
      $trayOptions ->addElementOption($inpWidth);  

      $name = 'defaultHeight';
      $inpHeight = new \XoopsFormNumber(_LG_FINDOBJECTS_HEIGHT,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight->setMinMax(5, 250);
      $trayOptions ->addElementOption($inpHeight);  

      $name = 'defaultBorderWidth';
      $inpBorderWidth = new \XoopsFormNumber(_LG_FINDOBJECTS_BORDER_WIDTH,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpBorderWidth->setMinMax(1, 8, _AM_QUIZMAKER_UNIT_PIXELS);
      $trayOptions ->addElementOption($inpBorderWidth);  

      $name = 'defaultBorderRadius';
      $inpBorderRadius = new \XoopsFormNumber(_LG_FINDOBJECTS_BORDER_RADIUS,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpBorderRadius->setMinMax(0, 50, _AM_QUIZMAKER_UNIT_PERCENT);
      $trayOptions ->addElementOption($inpBorderRadius);  
      //--------------------------------------
      //transmission des messages non modifiables pour l'interface JS
      $msg = new \XoopsFormHidden("{$optionName}[message_info]", _LG_PLUGIN_FINDOBJECTS_INFO);
      $trayOptions ->addElementOption($msg);  
      
      //--------------------------------------

      return $trayOptions;
    }

/* *************************************************

* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler, $quizHandler, $questionsHandler;
        //recherche du dossier upload du quiz
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path =  QUIZMAKER_FLD_UPLOAD_QUIZ_JS . "/" . $quiz->getVar('quiz_folderJS') . "/images";
        
//echo "<hr>{$path}<hr>";
        $quest =  $questionsHandler->get($questId, 'quest_options');
        $options = json_decode(html_entity_decode($quest->getVar('quest_options')),true);
        if(!$options) $options = $this->optionsDefaults;
        $answers = $answersHandler->getListByParent($questId);
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  

        //-------------------------------------------------------------

        $i=0;
        $i = $this->getFormImg($trayAllAns, 0, $answers, _AM_QUIZMAKER_SEQUENCE, $i, 2, $path, $options);
        

        
        //----------------------------------------------------------------
        $this->initFormForQuestion();
        $this->trayGlobal->addElement($trayAllAns);
        //$this->trayGlobal->insertBreak( 'zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz');
		return $this->trayGlobal;
	}
    
/* *************************************************
* meme procedure pour chaque groupe:
* - image de substitution
* - sequence logique
* - mauvaises reponses
* ************************************************** */
public function getFormImg(&$trayAllAns, $group, $answers,$titleGroup, $firstItem, $maxItems, $path, $options)
{ 

    $imgPath = QUIZMAKER_PATH_QUIZ_JS . '/images/substitut';
    $imgUrl = QUIZMAKER_URL_QUIZ_JS . '/images/substitut';

    $imgList = XoopsLists::getFileListByExtension($imgPath,  array('jpg','png','gif'), '');

        $k = 0;
        $i = 1;
        //----------------------------------------------------------
        $tbl = null;
        $ans = (isset($answers[$k])) ? $answers[$k] : null;
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_getFormGroup.php");
        $tbl = $this->getNewXoopsTableXtray();
        $tbl->addTdStyle(0, 'vertical-align: top;width:50%;');
        $tbl->addTdStyle(1, 'vertical-align: top;');
            $inpImg1 = $this->getXoopsFormImageDiv($image1, $this->getName()."_image1", $path, "divImageRef1", '<br>');
            if(!$image2) $image2 = $image1;
            $inpImg2 = $this->getXoopsFormImageDiv($image2, $this->getName()."_image2", $path, "divImageRef2", '<br>');
        
            //$inpCaption = new \XoopsFormText(_AM_QUIZMAKER_CAPTION,  $this->getName($i,'caption'), $this->lgMot1, $this->lgMot1, $caption);
            //$inpAnsIs = new \xoopsFormHidden(, )
        
            ///------------------------------------------------------------------------------   
        $tbl->addXoopsFormHidden($this->getName($i,'id'), $answerId);
        $tbl->addXoopsFormHidden($this->getName($i,'chrono'), $i+1, $col, $k, '');
        
            $tbl->addElement($inpImg1, $col, $k);
            $tbl->addElement(new XoopsFormLabel('',"<div id='resultat' class='resultat'>" . _LG_FINDOBJECTS_TEST_ClICK_OBJECTS . "</div>"), $col,$k);
            $tbl->addElement($inpImg2, $col, $k);
             
            //$tbl->addElement($inpCaption, $col, $k);

            $tbl->addElement($this->getFormInpDivImg($answers, $options), ++$col, $k);
           
        
        
        $trayAllAns->addElement($tbl, $k);
        return $i+1;  // return le dernier index pour le groupe suivant


}

public function getFormInpDivImg($answers, $options)
{ 
$touchesArr = array();
foreach($answers as $key=>$ans){
//hoArray($ans);
    //$touchesArr = $ans['buffer'];
    if($ans->getVar('answer_group') == 2){
        //$buffer = str_replace('"','\"',$ans->getVar('answer_buffer'));
        $buffer = html_entity_decode($ans->getVar('answer_buffer'));
        //$touchesArr[] = '"' . $buffer . '"';
        $touchesArr[] = $buffer;
    }
}
//var myQuestions = JSON.parse(`[{\"quizId\":201,\"q

//echoArray($options);
$tplTouches = "var bufferArr = [" . implode(",\n", $touchesArr) . "];";
$defaultValues = "var defaultValues = {'maxTouches' : {$options['maxTouches']}, 'defaultWidth' : {$options['defaultWidth']}, 'defaultHeight' : {$options['defaultHeight']}, 'defaultBorderWidth' : {$options['defaultBorderWidth']}, 'defaultBorderRadius' : {$options['defaultBorderRadius']}};\n";

$delete = _LG_FINDOBJECTS_DELETE;
$title = _LG_FINDOBJECTS_TITLE;
$color = _LG_FINDOBJECTS_COLOR;
$top = _LG_FINDOBJECTS_LEFT;
$left = _LG_FINDOBJECTS_TOP;
$width = _LG_FINDOBJECTS_WIDTH;
$height = _LG_FINDOBJECTS_HEIGHT;
$borderRadius = _LG_FINDOBJECTS_BORDER_RADIUS;
$points = _LG_FINDOBJECTS_POINTS;
$idDivImg1 = $this->getName()."_image1";
$borderWidth = _LG_FINDOBJECTS_BORDER_WIDTH;
$refreshImgSize = _LG_FINDOBJECTS_REFRESH_IMG_SIZES;

$tplCoordonnees = <<<__tplCoordonnees__
<script>\n
{$tplTouches}\n
{$defaultValues}\n

</script>

<table style="text-align: left; width: 100%;" border="1" cellpadding="2"cellspacing="2" class='coordonnees'>
<thead><tr>
<th>{$delete}</th>
<th>{$title}</th>
<th>{$color}</th>
<th>{$top}</th>
<th>{$left}</th>
<th>{$width}</th>
<th>{$height}</th>
<th>{$borderRadius}</th>
<th>{$borderWidth}</th>
<th>{$points}</th>
</tr>
<thead><tbody  id='coordonnees'>
</tbody
</table>
__tplCoordonnees__;

$tpl = <<<__inpDivImg__
<table class='findObjects_table'>
  <tbody>
    <tr>
      <td style="vertical-align: top;"><div id='message' class='resultat'>...</div></td>
    </tr>
    <tr>
      <td style="vertical-align: top;"><div id='resultat2'></div></td>
    </tr>
    <tr>
      <td style="vertical-align: top;"><div id='togodo'></div></td>
    </tr>
    <tr>
      <td style="vertical-align: top;">{$tplCoordonnees}</td>
    </tr>
  </tbody>
</table>

<!-- utilisee pendant le dev à gar der au cas ou
<input type='button' value='refresh' onclick='refreshImg();'>
<input type='button' value='clear messages' onclick='messages.clear();'>

au cours du dev la taille des images de reference a été modifiée à plusieurs reprises créant des décalages des touches.
cette valeur ne devrait plus bouger mais je garde au cas ou, ça peut resservir.
<input type='button' value='{$refreshImgSize}' onclick='event_update_whRef(event, "{$idDivImg1}");'>
-->
<div id='log' name='log'></div>

__inpDivImg__;
    $inp = new XoopsFormLabel('', $tpl);
    return $inp;
 
        
        //<div id='resultat' class='resultat'></div>
}

/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $pluginsHandler, $quizHandler, $xoopsDB;
//echoArray($answers,'saveAnswers');        
//echoArray('gp','saveAnswers');exit;        
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path = QUIZMAKER_PATH_UPLOAD . "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";
        //$this->echoAns ($answers, $questId, $bExit = false);    
        //--------------------------------------------------------       
        //sauvegarde en premier de la proposition n)12 qui contient les images
        //--------------------------------------------------------       

        $ans = $answers[0];
//        echoArray($answers);exit;
        include(QUIZMAKER_PATH_MODULE . "/include/plugin_saveAnswers.php");
       
        $prefix = "quiz-{$questId}-{$ans['chrono']}";
        $formName = $this->getName()."_image1";
        $newImg = $this->save_img($ans, $formName, $path, $quiz->getVar('quiz_folderJS'), $prefix, $nameOrg);
        if($newImg){
            $ansObj->setVar('answer_image1', $newImg);        
        }
       
        $prefix = "quiz-{$questId}-{$ans['chrono']}";
        $formName = $this->getName()."_image2";
        $newImg = $this->save_img($ans, $formName, $path, $quiz->getVar('quiz_folderJS'), $prefix, $nameOrg);
        if($newImg){
            $ansObj->setVar('answer_image2', $newImg);        
        }
        $weight = 0;
        $ans['proposition']  = ($ans['proposition']) ? $ans['proposition'] : "Sans Titre";
        $ansObj->setVar('answer_proposition', $ans['proposition']);
        $ansObj->setVar('answer_group', 1);
        $ansObj->setVar('answer_weight', $weight++);
        $answersHandler->insert($ansObj);
        
        //------------------------------------
        // suppression des touches existant,
        // le transfert de answerId entre php javascript php ... trop compliquÃ© Ã  mettre en oeuvre
        // conclusion suppresion des proposition du group 2
        //------------------------------------
        $sql = "DELETE FROM " . $xoopsDB->prefix('quizmaker_answers')
             . " WHERE answer_quest_id = " . $questId
             . " AND answer_group = 2;";
        $xoopsDB->query($sql);
        //exit;
//         $criteria = new CriteriaCompo("answer_quest_id", $questId);
//         $criteria->add(new Criteria("answer_quest_group", 2, '=', "AND"));
//         $answersHandler->deleteAll($criteria);
        //------------------------------------
        // enregistrement des touches dans le champ answer_buffer
        //------------------------------------
        //$findObject = Request::getArray('findObject', null);
        $findObject = $_POST['findObject'];
        if(!isset($findObject) || count($findObject) == 0) return true;
        
        forEach($findObject as $ket=>$touche){
                $ansObj = $answersHandler->create();
                $ansObj->setVar('answer_quest_id', $questId);
//             if($touche['answerId'] == 0){
//                 $ansObj = $answersHandler->create();
//                 $ansObj->setVar('answer_quest_id', $questId);
// 
//             }else{
//                  $ansObj = $answersHandler->get($answerId);
//             }
            //$touche['answerId'] = $ansObj->getVar('answer_id');  
            $buffer = json_encode($touche);  
            $ansObj->setVar('answer_buffer', $buffer);
            $ansObj->setVar('answer_proposition', $touche['caption']);
            $ansObj->setVar('answer_caption', $touche['caption']);
            $ansObj->setVar('answer_points', $touche['points']);
            $ansObj->setVar('answer_group', 2);
            $ansObj->setVar('answer_weight', $weight++);

            $answersHandler->insert($ansObj);
                    
        }     
/*
[findObject] => Array
        (
            [0] => Array
                (
                    [txt] => Touche nÂ° 0
                    [hidden] => red
                    [inpx] => 504
                    [inpy] => 164
                    [inpw] => 36
                    [inph] => 36
                )
*/       
       
       
     
     //exit ("<hr>===>saveAnswers<hr>" . $criteria->renderWhere() ."<hr>");
    }

 

/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler, $quizHandler, $questionsHandler;
  /*
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id']          = $this->getVar('answer_id');
		$ret['quest_id']    = $this->getVar('answer_quest_id');
		$ret['caption']      = $this->getVar('answer_caption');
		$ret['proposition'] = $this->getVar('answer_proposition');
		$ret['touches']      = $this->getVar('answer_touches');
		$ret['weight']      = $this->getVar('answer_weight');
		$ret['group']      = $this->getVar('answer_group');
  
  */
    // = "<tr style='color:%5\$s;'><td>%1\$s</td><td>%2\$s</td><td>%3\$s</td><td>%4\$s</td></tr>";
    $html = array();
 
    //-------------------------------------------
    // commenÃ§ons par la solution
    $answersAll = $answersHandler->getListByParent($questId, 'answer_weight,answer_id');
    $quizId = $questionsHandler->get($questId, ["quest_quiz_id"])->getVar("quest_quiz_id");
//    echo("getSolutions - quizId = <hr><pre>" . print_r($quizId,true) . "</pre><hr>");
    //recherche du dossier upload du quiz
    $quiz = $quizHandler->get($quizId,"quiz_folderJS");
    $path =  QUIZMAKER_URL_UPLOAD_QUIZ . "/" . $quiz->getVar('quiz_folderJS') . "/images";
    $tplImg = "<img src='{$path}/%s' alt='' title='%s' style='height:64px;background:%s'>";
/*
    $tImg = array();
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        if ($ans['group'] == 0) {
            $tImg[] = sprintf($tplImg, $ans['proposition'], $ans['proposition'], $ans['color']);
        }
	}
    $html[] = implode("\n", $tImg);
    $html[] = "<hr>";
*/    
    
       
    //-------------------------------------------
    $answersAll = $answersHandler->getListByParent($questId, 'answer_touches DESC,answer_weight,answer_id');
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;
    $tpl = "<tr><td><span style='color:%5\$s;'>%1\$s</span></td>" 
             . "<td><span style='color:%5\$s;'>%6\$s</span></td>" 
             . "<td><span style='color:%5\$s;'>%2\$s</span></td>" 
             . "<td style='text-align:right;padding-right:5px;'><span style='color:%5\$s;'>%3\$s</span></td>"
             . "<td><span style='color:%5\$s;'>%4\$s</span></td></tr>";

    $html[] = "<table class='quizTbl'>";
    
    
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $touches = intval($ans['touches']);
        $imgUrl = sprintf($tplImg, $ans['proposition'], $ans['proposition'], $ans['color']);
        if ($touches > 0) {
            $scoreMax += $touches;
            $color = QUIZMAKER_TOUCHES_POSITIF;
            $html[] = sprintf($tpl, $imgUrl, '&nbsp;===>&nbsp;', $touches, _CO_QUIZMAKER_TOUCHES, $color, $ans['caption']);
        }elseif ($touches < 0) {
            $scoreMin += $touches;
            $color = QUIZMAKER_TOUCHES_NEGATIF;
            $html[] = sprintf($tpl, $imgUrl, '&nbsp;===>&nbsp;', $touches, _CO_QUIZMAKER_TOUCHES, $color, $ans['caption']);
        }elseif($boolAllSolutions){
            $color = QUIZMAKER_TOUCHES_NULL;
            $html[] = sprintf($tpl, $imgUrl, '&nbsp;===>&nbsp;', $touches, _CO_QUIZMAKER_TOUCHES, $color, $ans['caption']);
        }
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMin'] = $scoreMin;
    $ret['scoreMax'] = $scoreMax;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe
