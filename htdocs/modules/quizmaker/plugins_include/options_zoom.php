<?php
      $name = 'zoom';  
      $inputZoom = new \XoopsFormRadio(_AM_QUIZMAKER_ZOOM, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputZoom->setDescription(_AM_QUIZMAKER_ZOOM_DESC);            
      $inputZoom->addOption(0, _AM_QUIZMAKER_ZOOM_NONE);            
      $inputZoom->addOption(1, _AM_QUIZMAKER_ZOOM_MANUEL);            
      $inputZoom->addOption(2, _AM_QUIZMAKER_ZOOM_AUTO);            
      $trayOptions ->addElementOption($inputZoom);     


?>