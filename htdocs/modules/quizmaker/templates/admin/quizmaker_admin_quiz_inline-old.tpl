<{if $outline}>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?xml version="1.0" encoding="UTF-8"?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" /> 
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="generator" content="PSPad editor, www.pspad.com">

    <title><{$quiz.name}></title>
<{/if}>

<{$highslide}>
<script>const quiz_execution=<{$quiz_execution}> </script>
    <script src="<{$quizUrl}>/js/quiz-consignes.js"></script>
<script src="<{$quizUrl}>/js/<{$options}>.js"></script>
<script  src="<{$quizUrl}>/js/<{$questions}>.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<{$urlApp}>/css/infobulle/infobulle.css"/>

    <{foreach item=css from=$allCss name=cssName}>
        <link rel="stylesheet" type="text/css" media="screen" href="<{$urlApp}>/css/<{$css}>"/>
    <{/foreach}>
    
    <script src="<{$urlApp}>/js/<{$prototype}>"></script>
    <!-- Insertion des classes des slides -->
    <{foreach item=tpljs from=$allPlugins name=tpljsName}>
        <script src="<{$urlApp}>/js/plugins/<{$tpljs}>"></script>
    <{/foreach}>

    <{* ========================================== *}> 

<script>

/**************************************************************************
 *   get instance de classe
 * ************************************************************************/
  function getTplNewClass (currentQuestion, chrono){

  var obj;

    switch (currentQuestion.type){
    <{foreach item=tpljs from=$allPlugins name=tpljsName}>
        <{assign var='className' value=$tpljs|substr:6:-3 }>
        case "<{$className}>" : obj = new (<{$className}>)(currentQuestion, chrono); break;
    <{/foreach}>
    
    default: alert("getTplNewClass - Classe absente : " + currentQuestion.type); break;
    }

    blob("getTplNewClass - Classe : " + currentQuestion.type);
    return obj;
}
</script>

    
    <{* ========================================== *}> 

<script src="<{$urlApp}>/js/language/quiz-<{$language}>.js"></script>


<{if $outline}>
    </head>
    <body>
<{/if}>

<center>
<{*
<h1 class="quiz-main">Quiz en javascript pour le module "quizmaker" pour Xoops</h1>
*}>
<!-- *****************************************  -->
<div id='quiz_div_module_xoops' name='quiz_div_module_xoops'>
</div>
<!-- *****************************************  -->
</center>

<div id='quiz_questions_js' name='quiz_questions_js'>
</div>


<script src="<{$urlApp}>/js/<{$quiz_functions}>.js"></script>
<script src="<{$urlApp}>/js/<{$quiz_events}>.js"></script>
<script src="<{$urlApp}>/js/<{$quiz_main}>.js"></script>

<{if $outline}>
    </body>
</html>
<{/if}>
