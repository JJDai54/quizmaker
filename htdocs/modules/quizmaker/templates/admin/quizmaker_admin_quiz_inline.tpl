<{if $outline}>
<!doctype html>

<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" /> 
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="generator" content="PSPad editor, www.pspad.com">

    <title><{$quiz.name}></title>
<{/if}>



<{* ===============================================*}>

<script>const quiz_execution=<{$quiz_execution}> </script>
    <script src="<{$quizUrl}>/js/quiz-consignes.js"></script>
<script src="<{$quizUrl}>/js/<{$options}>.js"></script>
<script  src="<{$quizUrl}>/js/<{$questions}>.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<{$urlApp}>/css/infobulle/infobulle.css"/>

    <{foreach item=css from=$allCss name=cssName}>
        <link rel="stylesheet" type="text/css" media="screen" href="<{$urlApp}>/css/<{$css}>"/>
    <{/foreach}>
    
    <script src="<{$urlApp}>/js/<{$prototype}>"></script>
    
    <!-- Insertion des CSS des plugins zzzzz-->
    <{foreach item=css from=$allPluginsCSS name=tplcssName}>
        <link rel="stylesheet" href="<{$urlPlugins}>/<{$css}>" type="text/css" />
    <{/foreach}>

    <!-- Insertion des fichier JS des plugins itou les classes de ces plugins -->
    <{foreach item=js from=$allPluginsJS name=tpljsName}>
        <script src="<{$urlPlugins}>/<{$js}>"></script>
    <{/foreach}>
    <{* ========================================== *}> 

<script>

/**************************************************************************
 *   get instance de classe
 * ************************************************************************/
  function getTplNewClass2 (currentQuestion, chrono){

  var obj;

    switch (currentQuestion.type){
    <{foreach item=pluginJS from=$allPlugins name=tpljsName}>
        <{assign var='className' value=$pluginJS}>
        case "<{$className}>" : obj = getPlugin_<{$className}>(currentQuestion, chrono); break;
    <{/foreach}>
    
    default: alert(quiz_messages.majCtrlF5 + "\n___________________\n" + "getTplNewClass - Classe absente : " + currentQuestion.type); break;
    }

    //blob("getTplNewClass - Classe : " + currentQuestion.type);
    return obj;
}
</script>

    
    <{* ========================================== *}> 

<script src="<{$urlApp}><{$smarty.const.QUIZMAKER_FLD_LANGUAGE_JS}>/quiz-<{$language}>.js"></script>


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


<{foreach item=js from=$jsArr}>
    <script src="<{$urlApp}>/js/<{$js}>.js"></script>
<{/foreach}>


<{if $outline}>
    </body>
</html>
<{/if}>
