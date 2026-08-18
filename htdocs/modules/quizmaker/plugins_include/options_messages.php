<?php
use XoopsModules\Quizmaker AS FQUIZMAKER;
$forcerDefaultMsg = false;
$myts = MyTextSanitizer::getInstance();

      //----------------------------------------------------------
      // --- messages dtandard à tous les plugins qui utilise les messages
      //----------------------------------------------------------
      $trayOptions->insertBreak(sprintf(QUIZMAKER_OPTIONS_BREAK_STYLE, _AM_QUIZMAKER_NEXT_SLIDE_PARAMS));  
     
      $name = 'msg_nextslide_gotonext';
      $inpNextSlideAllowed = new \XoopsFormRadioYN(_AP_QUIZMAKER_NEXTSLIDE_GOTONEXT, "{$optionName}[{$name}]", $tValues[$name]);
      $inpNextSlideAllowed->setDescription(_AP_QUIZMAKER_NEXTSLIDE_GOTONEXT_DESC);  
      $trayOptions->addElementOption($inpNextSlideAllowed);

      $name = 'msg_nextslide_duree';
      if($tValues[$name] < 3) $tValues[$name] = 3;
      $inpNexSlideDuree = new \XoopsFormNumber(_AP_QUIZMAKER_NEXTSLIDE_DUREE,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpNexSlideDuree->setMinMax(0, 12, _AM_QUIZMAKER_UNIT_SECONDS);
      $inpNexSlideDuree->setDescription(_AP_QUIZMAKER_NEXT_SLIDE_DUREE_DESC);
      $trayOptions->addElementOption($inpNexSlideDuree);

      //-----------------------------------------------  
      $name = 'msg_nextslide_bgWinner';
      $inpNextSlideMsgBGW = new \XoopsFormColorPicker(_AP_QUIZMAKER_BACKGROUND, "{$optionName}[{$name}]", $tValues[$name]);
      $background = $tValues[$name];
      //$trayOptions->addElementOption($inpNextSlideMsgBGW);

      //affichage du message winner
      $name = 'msg_nextslide_winner'; 
      $inpNextSlideMsgWinnner = new \XoopsEditList('', "{$optionName}[{$name}]", $tValues[$name], 120) ; 
      $inpNextSlideMsgWinnner->setHelp(_AP_QUIZMAKER_MSG_NEXTSLIDE_WINNER_DESC);
      $inpNextSlideMsgWinnner->setBackground($background);
      $inpNextSlideMsgWinnner->setWidth(500);
      $inpNextSlideMsgWinnner->addBtnClear('X'); 
      $constRoot = "_AP_QUIZMAKER_" . strtoupper($name);
      $inpNextSlideMsgWinnner->addOptionArray(FQUIZMAKER\getNextMessagesgArr($constRoot)); 
      //$trayOptions->addElementOption($inpNextSlideMsgWinnner);
      
      $xtrayWinner = new \XoopsFormElementTray(_AP_QUIZMAKER_MSG_NEXTSLIDE_WINNER, "");
      $xtrayWinner->addElement($inpNextSlideMsgBGW);
      $xtrayWinner->addElement($inpNextSlideMsgWinnner);
      $trayOptions->addElementOption($xtrayWinner);
      //-----------------------------------------------  
      $name = 'msg_nextslide_bgLooser';
      $inpNextSlideMsgBGL = new \XoopsFormColorPicker(_AP_QUIZMAKER_BACKGROUND, "{$optionName}[{$name}]", $tValues[$name]);
      $background = $tValues[$name];
      //$trayOptions->addElementOption($inpNextSlideMsgBGL);


      //affichage du message looser - c'est soit "looser" soit "max_attempts"
      $name = 'msg_nextslide_looser'; 
      $inpNextSlideMsgLooser = new \XoopsEditList('', "{$optionName}[{$name}]", $tValues[$name], 120) ; 
      $inpNextSlideMsgLooser->setHelp(_AP_QUIZMAKER_MSG_NEXTSLIDE_LOOSER_DESC);
      $inpNextSlideMsgLooser->setBackground($background);
      $inpNextSlideMsgLooser->setWidth(500);
      $inpNextSlideMsgLooser->addBtnClear('X'); 
      $constRoot = "_AP_QUIZMAKER_" . strtoupper($name);
      $inpNextSlideMsgLooser->addOptionArray(FQUIZMAKER\getNextMessagesgArr($constRoot)); //
      //$trayOptions->addElementOption($inpNextSlideMsgLooser);
      
      $xtrayLooser = new \XoopsFormElementTray(_AP_QUIZMAKER_MSG_NEXTSLIDE_LOOSER, "");
      $xtrayLooser->addElement($inpNextSlideMsgBGL);
      $xtrayLooser->addElement($inpNextSlideMsgLooser);
      $trayOptions->addElementOption($xtrayLooser);
      //-----------------------------------------------  
//echoArray(FQUIZMAKER\getNextMessagesgArr($constRoot), $constRoot);

      
      //----------------------------------------------------------
      // --- messages spécifiques au déroulement du plugin
      //----------------------------------------------------------
      if($this->messagesArr){
            $trayOptions->insertBreak(sprintf(QUIZMAKER_OPTIONS_BREAK_STYLE, _AM_QUIZMAKER_MSG_PLUGIN));  
      
            $name = 'msg_duree';
            $inpNexSlideDelai = new \XoopsFormNumber(_AP_QUIZMAKER_MSG_DUREE,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
            $inpNexSlideDelai->setMinMax(1, 12, _AM_QUIZMAKER_UNIT_SECONDS);
            $inpNexSlideDelai->setDescription(_AP_QUIZMAKER_MSG_DUREE_DESC);
            $trayOptions->addElementOption($inpNexSlideDelai);
      
            $name = 'msg_background';
            $inpNextSlideMsgBG = new \XoopsFormColorPicker(_AP_QUIZMAKER_NEXT_SLIDE_BG, "{$optionName}[{$name}]", $tValues[$name]);
            $trayOptions->addElementOption($inpNextSlideMsgBG);
            
            
$forcerDefaultMsg = false;           // pour le dev, laisser false 
            
            $plugin = $this->pluginName;
            $h = 0;
            foreach($this->messagesArr as $key=>$values){
                    //$name = 'msg_' . strtolower($values);
                    $name = 'msg_' . $values;
                    $rootName =  strtoupper("_LG_PLUGIN_{$plugin}_{$name}"); 
                    $default = constant("{$rootName}_0");                   
                    $label = constant(strtoupper("{$rootName}")); 
                    $desc = constant(strtoupper("{$rootName}_DESC")); 
                    //echo "name = {$name} ===> default = {$default}<br>";
                    
                    $fullName = "{$optionName}[{$name}]";
                    if(!$tValues[$name] || $forcerDefaultMsg) $tValues[$name] = $default;
                    $inpMsg = new \XoopsEditList($label, $fullName, $myts->htmlSpecialChars($tValues[$name]), 80) ;                    


                    $inpMsg->setHelp($desc);
                    $inpMsg->setBackground('#E0FFE0');            
                    $inpMsg->addBtnClear('X'); 
                    $inpMsg->addOptionArray(FQUIZMAKER\getNextMessagesgArr($rootName)); 
                    $trayOptions->addElementOption($inpMsg, true);
         
        //             $inpNextSlideMsgWinnner->setWidth(500);
                $h++;
            }
      }  

?>