<?php
//echo"<hr>Selection de la disposition<hr>";
      $name = 'disposition'; 
      $path = $this->pathArr['img'] . "/dispositions" . ((isset($dispositionSubfolder)) ? '/' . $dispositionSubfolder: ''); 
      $inptDisposition = new \XoopsFormIconSelect("<br>" . _AP_QUIZMAKER_DISPOSITION, "{$optionName}[{$name}]", $tValues[$name], $path);
      $inptDisposition->setExtension(false); //seule la racine du nom est utilisée dans le JS, pas besoin de l'extension
      $inptDisposition->setSelectedIconSize(64, 64);
      $inptDisposition->setIconSize(64, 64);
      
      $inptDisposition->setDescription(_AP_QUIZMAKER_DISPOSITION_DESC);
      $trayOptions->addElementOption($inptDisposition);     

?>