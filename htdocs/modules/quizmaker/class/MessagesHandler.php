<?php

namespace XoopsModules\Quizmaker;

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
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */

use XoopsModules\Quizmaker AS FQUIZMAKER;


/**
 * Class Object Handler Messages
 */
class MessagesHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct(\XoopsDatabase $db)
	{
		parent::__construct($db, 'quizmaker_messages', Messages::class, 'msg_id', 'msg_code');
	}

	/**
	 * @param bool $isNew
	 *
	 * @return object
	 */
	public function create($isNew = true)
	{
		return parent::create($isNew);
	}

	/**
	 * retrieve a field
	 *
	 * @param int $i field id
	 * @param null fields
	 * @return mixed reference to the {@link Get} object
	 */
	public function get($i = null, $fields = null)
	{
		return parent::get($i, $fields);
	}

	/**
	 * get inserted id
	 *
	 * @param null
	 * @return integer reference to the {@link Get} object
	 */
	public function getInsertId()
	{
		return $this->db->getInsertId();
	}

	/**
	 * Get Count Messages in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCountMessages($crAllMessages=null, $start = 0, $limit = 0, $sort = 'msg_id ASC, msg_code', $order = 'ASC')
	{
		$crCountMessages = new \CriteriaCompo();
		$crCountMessages = $this->getMessagesCriteria($crCountMessages, $start, $limit, $sort, $order);
		return parent::getCount($crCountMessages);
	}

	/**
	 * Get All Messages in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
	public function getAllMessages($crAllMessages=null, $start = 0, $limit = 0, $sort = 'msg_id ASC, msg_code', $order = 'ASC')
	{
		if (!$crAllMessages) $crAllMessages = new \CriteriaCompo();
		$crAllMessages = $this->getMessagesCriteria($crAllMessages, $start, $limit, $sort, $order);
		return parent::getAll($crAllMessages);
	}

	/**
	 * Get Criteria Messages
	 * @param        $crMessages
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	private function getMessagesCriteria($crMessages, $start, $limit, $sort, $order)
	{
		$crMessages->setStart( $start );
		$crMessages->setLimit( $limit );
		$crMessages->setSort( $sort );
		$crMessages->setOrder( $order );
		return $crMessages;
	}

/* ******************************
 * renvoie une liste "id=>name" pour les formSelect 
 * *********************** */
    public function getListKeyName(CriteriaElement $criteria = null, $addAll=false, $addNull=false)    {
        $ret     = array();
        if ($addAll) $ret[0] = "(*)";
//         if ($addNull) $inpList->addOption('_NULL_', _AM_CARTOUCHES_NULL);
        $obs = $this->getObjects($criteria, true);
        foreach (array_keys($obs) as $i) {
                $ret[$obs[$i]->getVar('msg_id')] = $obs[$i]->getVar('msg_code' );
        
        }

        return $ret;
    }
    
    
/* ******************************
 * renvoie la liste des langues du JS
 * *********************** */
    public function getLanguages(){
        $tLang = array();
        //dossier des fichiers
        $folder = QUIZMAKER_PATH_QUIZ_ORG . QUIZMAKER_FLD_LANGUAGE_JS;
        
        //liste des fichiers langue pour la partie javascript
        $jsFiles = \XoopsLists::getFileListByExtension($folder,  array('js'));
        foreach($jsFiles as $key=>$f){
          //recheche de la langue
          $h=strrpos($f,"-")+1;
          $j=strrpos($f,".");
          $language = substr($f, $h, $j-$h);
          $tLang[$language] = $language;

        }
        return $tLang;
    }

/* ******************************
 * charge les fichiers de langue javascript
 * *********************** */
    public function loadAllLanguagesMessagesJS(){
        //vidagage de la table
        $this->deleteAll();
        
        //dossier des fichiers
        $folder = QUIZMAKER_PATH_QUIZ_ORG . QUIZMAKER_FLD_LANGUAGE_JS;
        
        //liste des fichiers langue pour la partie javascript
        $jsFiles = \XoopsLists::getFileListByExtension($folder,  array('js'));
            
        foreach($jsFiles as $key=>$file){
          //echo "<br>image : {$key} | {$file}";
          $fullName = $folder . '/' . $key;
          //chargement du fichier.
          $this->loadMessagesJS($fullName);
        }
    }
/* ******************************
 * charge les fichiers de langue javascript
 * *********************** */
    public function loadMessagesJS($f){
    global $quizUtility;
    
    //recheche de la langue
    $h=strrpos($f,"-")+1;
    $j=strrpos($f,".");
    $language = substr($f, $h, $j-$h);
    
    
    
    
        $content = $quizUtility->loadTextFile($f); 
        $h = strpos($content, '{')+1;
        $j = strrpos($content, '}')-1;
        $exp = substr($content,$h,$j-$h+1);
        $arr = explode("\n", $exp);
//        echo "<h>|{$exp}|<hr>";
//echo "<hr>js language<pre>" . print_r($arr, true) . "</pre><hr>";

        foreach($arr as $key=>$value){
            $h = strpos($value, ':');
            if($h !==false){
                $code = trim(substr($value,0,$h));                              //exrtraction du code
                $exp = trim(substr($value,$h+1));                               //extraction du message brute
                if (substr($exp,-1,1)  == ',') $exp = trim(substr($exp,0,-1));  //suppresion de la virgule de fin de ligne
                $exp = substr($exp,1,-1);                                       //suppression des quotes de debut et de fin
//echo "<pre><code>===>{$code}=>{$exp}</code></pre>";
                $messagesObj = $this->create();
                $messagesObj->setVar('msg_code', $code);
                $messagesObj->setVar('msg_language', $language);
                $messagesObj->setVar('msg_message', $exp);
                $messagesObj->setVar('msg_editable', 1);

                $ok = $this->insert($messagesObj);
            }
        }
    }
/* ************************************************
*
* ************************************************* */
public function buildAllJsLanguage(){

    $tLang = $this->getLanguages();
    foreach($tLang as $key=>$language){
        $this->buildJsLanguage($language);
    }
}

/* ************************************************
*
* ************************************************* */
public function buildJsLanguage($language){
    
    $criteria = new \criteriaCompo(new \Criteria("msg_language", $language, "="));
    $rst = $this->getAllMessages($criteria, $start = 0, $limit = 0, $sort = 'msg_code', $order = 'ASC');
    
    $tDef = array();
    
	foreach(array_keys($rst) as $i) {
		$key = $rst[$i]->getVar('msg_code');
        //$value = constant('_JS_QUIZMAKER_' . $rst[$i]->getVar('msg_constant')) ;
		$value = $rst[$i]->getVar('msg_message');

        //$value = strreplace("\"", , $value) ;
        $tDef[] = "{$key} : \"{$value}\"";      
	}
    
    $content = "const quiz_messages = {\n" . implode(",\n", $tDef) . "\n};\n";
    $content = html_entity_decode($content);
    $fileName = QUIZMAKER_PATH_QUIZ_ORG . QUIZMAKER_FLD_LANGUAGE_JS . "/quiz-{$language}.js";
    \JJD\FSO\saveTexte2File($fileName, $content);

}

    
    
}
