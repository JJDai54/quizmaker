<?php
use XoopsModules\Quizmaker AS FQUIZMAKER;
/* ***********

************** */
function getNextMessagesgArr($prefixPredefinis, $prefixPlugin){
     $lib = array();
      //ajout des libellés prédéfinis pour tous plugins
      if($prefixPredefinis){
          $h = 0;
          while (defined($prefixPredefinis . $h)){
            $lib[] = htmlentities(constant($prefixPredefinis . $h), ENT_QUOTES);
            $h++;
          }
      }
      
      //ajout des libellés prédéfinis spécifiques au plugin
      if($prefixPlugin){
          $h = 0;
          while (defined($prefixPlugin . $h)){
            $lib[] = htmlentities(constant($prefixPlugin . $h), ENT_QUOTES);
            $h++;
          }
      }
    return $lib;
}      
      /* ************************************************************* */
      $inpMsgTray = new \XoopsFormElementTray($caption, '<br>');

      $trayOptions->insertBreak("<div style='background:#99CCFF;width:100%;padding:0px;margin:0px;'><center><b>" . _AM_QUIZMAKER_NEXT_SLIDE_PARAMS . "</b></center></div>",-1,false);    
      
      $name = 'nextSlideDelai';
      $inpNexSlideDelai = new \XoopsFormNumber(_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_DELAI,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpNexSlideDelai->setMinMax(0, 8, _AM_QUIZMAKER_UNIT_SECONDS);
      $inpNexSlideDelai->setDescription(_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_DELAI_DESC);
      $inpMsgTray->addElement($inpNexSlideDelai);
     
      $inpMsgTray->addElement(new XoopsFormLabel(_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_DELAI_DESC));

      $name = 'nextSlideBG';
      $inpNextSlideMsgBG = new \XoopsFormColorPicker(_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_BG, "{$optionName}[{$name}]", $tValues[$name]);
      $inpMsgTray->addElement($inpNextSlideMsgBG);
     
  
      //----------------------------------------------------
      if(!isset($msgArr)) $msgArr = [true,true,false];
      

      //affichage du message winner
            $name = 'nextSlideMessageWinner'; 
            $inpNextSlideMsgWinnner = new \XoopsEditList(_AM_QUIZMAKER_NEXT_SLIDE_WINNER, "{$optionName}[{$name}]", $tValues[$name], 120) ; 
            //$inpNextSlideMsgWinnner->setHelp(_AM_QUIZMAKER_NEXT_SLIDE_WINNER_DESC);
            $inpNextSlideMsgWinnner->setBackground('#E0FFE0');
            $inpNextSlideMsgWinnner->setWidth(500);
            $inpNextSlideMsgWinnner->addBtnClear('X'); 
            $inpNextSlideMsgWinnner->addOptionArray(getNextMessagesgArr('_AM_QUIZMAKER_NEXT_SLIDE_WINNER_', $prefixPluginWinner)); 
            $inpMsgTray->addElement($inpNextSlideMsgWinnner);

      //affichage du message looser - c'est soit "looser" soit "max_attempts"
            $name = 'nextSlideMessageLooser'; 
            $inpNextSlideMsgLooser = new \XoopsEditList(_AM_QUIZMAKER_NEXT_SLIDE_LOOSER, "{$optionName}[{$name}]", $tValues[$name], 120) ; 
            //$inpNextSlideMsgLooser->setHelp(_AM_QUIZMAKER_NEXT_SLIDE_LOOSER_DESC);
            $inpNextSlideMsgLooser->setBackground('#FFEE00');
            $inpNextSlideMsgLooser->setWidth(500);
            $inpNextSlideMsgLooser->addBtnClear('X'); 
            $inpNextSlideMsgLooser->addOptionArray(getNextMessagesgArr('_AM_QUIZMAKER_NEXT_SLIDE_LOOSER_', $prefixPluginLlooser)); //
            $inpMsgTray->addElement($inpNextSlideMsgLooser);

      $trayOptions->addElementOption($inpMsgTray);
          
      //---------------------------------------------------------

?>