<?php

      $name = 'disposition'; 
      $path = $this->pathArr['img'] . "/dispositions"; 
      $inptDisposition = new \XoopsFormIconSelect("<br>" . _AM_QUIZMAKER_DISPOSITION, "{$optionName}[{$name}]", $tValues[$name], $path);
      $inptDisposition->setSelectedIconSize(64, 64);
      $inptDisposition->setIconSize(64, 64);
      
      $cst = strtoupper('_LG_' . $this->pluginName . '_DISPOSITION_DESC');
      $desc = (defined($cst)) ?  constant($cst) : _AM_QUIZMAKER_DISPOSITION_DESC;
      
      $inptDisposition->setDescription($desc);
      $trayOptions->addElementOption($inptDisposition);     

?>