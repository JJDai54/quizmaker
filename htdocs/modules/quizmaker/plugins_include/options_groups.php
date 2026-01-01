<?php

      // groupes
      for($h = 0; $h < $this->maxGroups; $h++){
          $requis = ($h < 2);
          $lib = _AM_QUIZMAKER_GROUP_LIB . ' ' .  $h . (($requis)?QUIZMAKER_REQUIS:'');
          $trayGroup = new XoopsFormElementTray($lib, $delimeter = ' ');  
          $name = 'group' . $h;
          $inpGoup = new \XoopsFormText('',  "{$optionName}[{$name}]", $this->lgMot2, $this->lgMot2, $tValues[$name]);
          if($requis){
            $inpGoup->setExtra("required placeholder='" . _AM_QUIZMAKER_REQUIRED . "'");
          }else{
            $inpGoup->setExtra("placeholder='" . _AM_QUIZMAKER_OPTIONAL . "'");
          }
          $trayGroup->addElement($inpGoup);     
  

          $name = 'bgGroup' . $h;  
          $inpBgGroup = new XoopsFormColorPicker('', "{$optionName}[{$name}]", $tValues[$name]);
          $trayGroup->addElement($inpBgGroup);     
          
          $trayOptions->addElementOption($trayGroup);  
      }
  

      //--------------------------------------
      $name = 'groupDefault';  
      $inputGroupDefault = new \XoopsFormRadio(_AM_QUIZMAKER_GROUP_DEFAULT, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputGroupDefault->addOption(-1, _AM_QUIZMAKER_GROUP_ALL);            
      for($h = 0; $h < $this->maxGroups; $h++){ 
        $groupeName = ($tValues['group' . $h]) ? $tValues['group' . $h] : 'group' . $h;
        $inputGroupDefault->addOption($h, $groupeName);            
      }
      $trayOptions ->addElementOption($inputGroupDefault);     
?>