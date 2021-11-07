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

    <{foreach item=css from=$allCss name=cssName}>
        <link rel="stylesheet" type="text/css" media="screen" href="<{$urlApp}>/css/<{$css}>"/>
    <{/foreach}>
    
    <script src="<{$urlApp}>/js/<{$prototype}>"></script>
    <{foreach item=tpljs from=$allTpljs name=tpljsName}>
        <script src="<{$urlApp}>/js/tpl/<{$tpljs}>"></script>
    <{/foreach}>
    

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
<div id='quiz_box_main' name='quiz_box_main'>
</div>
<!-- *****************************************  -->
</center>

<div id='quiz_questions_js' name='quiz_questions_js'>
<script  src="<{$quizUrl}>/<{$questions}>.js"></script>
</div>
<script src="<{$quizUrl}>/<{$options}>.js"></script>


<script src="<{$urlApp}>/js/<{$quiz_functions}>.js"></script>
<script src="<{$urlApp}>/js/<{$quiz_events}>.js"></script>
<script src="<{$urlApp}>/js/<{$quiz_main}>.js"></script>

<{if $outline}>
    </body>
</html>
<{/if}>
