<?php
/*
            //chargement des operations communes à tous les plugins
            include(QUIZMAKER_PATH_MODULE . "/include/plugin_saveAnswers.php");
            if (is_null($ansObj)) continue;
            //---------------------------------------------------           
*/
    $answerId = $ans['id'];

    if($answerId > 0){
        $ansObj = $answersHandler->get($answerId);
        if(!isset($ans['delete_Proposition'])) $ans['delete_Proposition'] = 0;
    }else{
        $ansObj = $answersHandler->create();
        $ansObj->setVar('answer_quest_id', $questId);
        $ans['delete_Proposition'] = 0;
    }

    //Suppression de la proposition et de l'image si besoin
    if($ans['delete_Proposition']==1) {
        $this->delete_answer_by_image($ans,$path);
        $ansObj = null;
    }  
    //-----------------------------------
  	$ans['proposition'] = trim($ans['proposition']);
  	$ans['caption']     = trim($ans['caption']);
  	$ans['buffer']      = trim($ans['buffer']);
    
?>