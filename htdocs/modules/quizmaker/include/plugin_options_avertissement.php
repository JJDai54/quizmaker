<?php

      //---------------------------------------------------------
      $trayOptions->insertBreak("<div style='background:#99CCFF;width:100%;padding:0px;margin:0px;'><center><b>" . _AM_QUIZMAKER_NEXT_SLIDE_PARAMS . "</b></center></div>",-1,false);    
      $name = 'nextSlideDelai';
      $inpNexSlideDelai = new \XoopsFormNumber(_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_DELAI,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpNexSlideDelai->setMinMax(0, 8, _AM_QUIZMAKER_UNIT_SECONDS);
      $inpNexSlideDelai->setDescription(_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_DELAI_DESC);
      $trayOptions->addElementOption($inpNexSlideDelai);     

      $name = 'nextSlideBG';
      $inpNextSlideMsgBG = new \XoopsFormColorPicker(_LG_PLUGIN_FINDOBJECTS_NEXT_SLIDE_BG, "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions->addElementOption($inpNextSlideMsgBG);     
  
      foreach ($arrConst as $key=>$prefix){
        //$prefix = $arrConst[$j];
        $name = $key;  
        $inpNextSlideMsgWin = new \XoopsFormTextPlus(constant($prefix), "{$optionName}[{$name}]",80,120, $tValues[$name]);
        $inpNextSlideMsgWin->setDescription(constant($prefix .'_DESC'));
        $inpNextSlideMsgWin->addBtnClear("X");
        $h = 0;
        while (defined($prefix . $h)){
          $inpNextSlideMsgWin->addOption(htmlentities(constant($prefix . $h)));
          $h++;
        }
        $h = 0;
        $prefix = '_AM_QUIZMAKER_NEXT_SLIDE_MSG';
        while (defined($prefix . $h)){
          $inpNextSlideMsgWin->addOption(constant($prefix . $h));
          $h++;
        }
        $trayOptions->addElementOption($inpNextSlideMsgWin);     
     }    
          
      //---------------------------------------------------------

?>