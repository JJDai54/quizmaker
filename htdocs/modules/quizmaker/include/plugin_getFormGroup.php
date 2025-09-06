<?php
/*
            $ans = (isset($answers[$k])) ? $answers[$k] : null;
            //chargement préliminaire des éléments nécéssaires et initialistion du tableau $tbl
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_getFormGroup.php");
            //-------------------------------------------------
*/
    $tVal = array();
    if(!isset($weight)) $weight = 0;
    $weight += 10;
    
    if (isset($ans)){
      $tVal['answerId']     = $ans->getVar('answer_id');
      $vArr['id']           = $ans->getVar('answer_id');
      $vArr['quest_id']     = $ans->getVar('answer_quest_id');
      $vArr['proposition']  = trim($ans->getVar('answer_proposition', 'e'));
      $vArr['buffer']       = trim($ans->getVar('answer_buffer'));
      $vArr['points']       = $ans->getVar('answer_points');
      $vArr['image1']       = $ans->getVar('answer_image1');
      $vArr['image2']       = $ans->getVar('answer_image2');
      $vArr['caption']      = $ans->getVar('answer_caption');
      $vArr['background']   = $ans->getVar('answer_background');
      $vArr['color']        = $ans->getVar('answer_color');
      $vArr['weight']       = $weight;
      //$vArr['weight']       = $ans->getVar('answer_weight');
      $vArr['inputs']       = $ans->getVar('answer_inputs');
      $vArr['group']        = $ans->getVar('answer_group');
      $vArr['isNew']        = false;     
      $vArr['tPropos']      = explode(',', $vArr['proposition']);
      //$vArr['delete']      = true;
    }else{
      $vArr['answerId']     = 0;
      $vArr['id']           = 0;
      $vArr['quest_id']     = 0;
      $vArr['proposition']  = '';
      $vArr['buffer']       = '';
      $vArr['points']       = $points;
      $vArr['image1']       = '';
      $vArr['image2']       = '';
      $vArr['caption']      = '';
      $vArr['background']   = '';
      $vArr['color']        = (isset($color)) ? $color : '#FFFFFF';
      $vArr['weight']       = $weight;
      $vArr['inputs']       = (isset($inputs)) ? $inputs : 0;
      $vArr['group']        = (isset($group))  ? $group  : 0;
      $vArr['isNew']        = true;     
      $vArr['tPropos']      = array();     
      //$vArr['delete']      = false;
     
    }
    //pour les plugins qui stockent des tableaux sous forme de chaine avec separateur
    for ($h = 0; $h>count($vArr['tPropos']); $h++) $vArr['tPropos'][$h] = trim($vArr['tPropos'][$h]);
    //  $vArr = $this->getAnswerValues($answers[$k], $weight,1);
    foreach($vArr as $key=>$value) $$key = $value;
    //-----------------------------------------------------------------------
      $answerId = $id;
      if(!isset($firstItem)) $firstItem = 0;

    $col = 0;
      $i = $k + $firstItem; // $i est le premier index du group a utiliser a la place de $k
      
      if($isNew){
        $delProposition = new \XoopsFormLabel('', '+');  //_CO_QUIZMAKER_NEW                       
      }else{
        $delProposition = new \XoopsFormCheckBoxImage('', $this->getName($i,'delete_Proposition'), [0]);    
        $delProposition->switchImage(true);                    
        $delProposition->showCaption(false);                    
        $delProposition->addOption(1, _AM_QUIZMAKER_DELETE);
      }
    

      //-------------------------------------------------
      if($tbl){
        $col = 0;
        $tbl->addTdStyle($col, 'min-width:80px;font-size:1.5em;vertical-align:middle;padding-top:10px;');
        $tbl->addXoopsFormHidden($this->getName($i,'id'), $answerId);
        $tbl->addXoopsFormHidden($this->getName($i,'chrono'), $i+1, $col, $k, '');
        $tbl->addElement($delProposition, $col, $k, '');
      }
      //-------------------------------------------------
?>