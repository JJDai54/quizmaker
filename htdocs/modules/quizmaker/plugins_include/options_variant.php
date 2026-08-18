<?php

    $name = 'variant';  
  
    if(isset($variantsArr) && count($variantsArr) > 0){
        $inpClasse = new \XoopsFormSelect(_AM_QUIZMAKER_VARIANT, "{$optionName}[{$name}]", $tValues[$name]);
        if (!$tValues[$name] || $tValues[$name] == $this::noClass) $inpClasse->addOption($this::noClass, _AM_QUIZMAKER_VARIANT_SELECT);
        $inpClasse->addOptionArray($variantsArr);
                                  
        // change la couleur de fond selon que la variante a été selectionnée ou pas
        if($tValues['variant'] == $this::noClass){ 
              $inpClasse->setExtra('style="background:#FFCCCC;color:red"');
              $inpClasse->setDescription(_AM_QUIZMAKER_VARIANT_VARIANT_DESC1);   
              $isSelectOk = false;
        }else{
              $inpClasse->setExtra('style="background:lime;"');
              $inpClasse->setDescription(_AM_QUIZMAKER_VARIANT_VARIANT_DESC2);  
              $isSelectOk = true;                                    
        }
        $trayOptions->addElementOption($inpClasse);     

    }else{
        $trayOptions->addElementOption(new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]));
        $isSelectOk = true;                                    
    }

?>