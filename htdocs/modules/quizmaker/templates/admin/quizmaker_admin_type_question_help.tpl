<!-- template -->
<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span><br></div>
<{/if}>

<!DOCTYPE HTML>
<html class='diapo_doc'>
  <head>

  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta name="generator" content="PSPad editor, www.pspad.com">
  <title><{$plugin}></title>
<{*
<link rel='stylesheet' type='text/css' media='all' href='<{$url_base}>/diapo-all.css' />
<link rel='stylesheet' type='text/css' media='print' href='<{$url_base}>/diapo-print.css' />
*}>

<script src='<{$smarty.const.QUIZMAKER_URL_ASSETS}>/js/quizmaker.js' type='text/javascript'></script>

  </head>
  <body  style="background:#CCFFFF;">

  <!-- avertissement masquer pour l'imression -->
<table  id='' class='avertissement' width='80%'>
  <tr >
    <td  width='80%' ><{$localHeaderInfo}></td>
    <td  width='20%' >
    <div >
      <a class='masquer' href="" onclick='quizmaker_closeDoc();'>
        <img  style="float:right;"  src="<{$smarty.const.XOOPS_URL}>/Frameworks/JJD-Framework/images/icons/48/quitter2.png">
      </a>
      <a class='masquer' href="" onclick='quizmaker_printDoc();'>
        <img style="float:right;" src="<{$smarty.const.XOOPS_URL}>/Frameworks/JJD-Framework/images/icons/48/print.png">
      </a>
    </div>
    </td>
  </tr>
</table><hr>
<!-- ****************************************************************** -->
 

<!-- ****************************************************************** -->

<{$viewHelpTypeQuestion}>

<!-- ****************************************************************** -->
  <!-- avertissement masquer pour l'imression -->
    <div >
      <a class='masquer' href="" onclick='quizmaker_closeDoc();'>
        <img  style="float:right;"  src="<{$smarty.const.XOOPS_URL}>/Frameworks/JJD-Framework/images/icons/48/quitter2.png">
      </a>
      <a class='masquer' href="" onclick='quizmaker_printDoc();'>
        <img style="float:right;" src="<{$smarty.const.XOOPS_URL}>/Frameworks/JJD-Framework/images/icons/48/print.png">
      </a>
    </div>

  </body>
</html>

