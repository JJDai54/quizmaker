

<link rel="stylesheet" type="text/css" media="screen" href="<{$smarty.const.QUIZMAKER_QUIZ_JS_URL}>/css/quiz.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/style-item-design.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/style-item-color.css"/>


<script src="<{$smarty.const.QUIZMAKER_QUIZ_JS_URL}>/js/tpl/slide__prototype.js"></script>


<{*    ----- insertion des classes de slide -----*}>      
<{$smarty.const.QUIZMAKER_QUIZ_JS_URL}>

<{foreach item=slide from=$allSlides}>
    <script src="<{$smarty.const.QUIZMAKER_QUIZ_JS_URL}>/js/tpl/<{$slide}>.js"></script>
<{/foreach}>
            


<script src="<{$smarty.const.QUIZMAKER_QUIZ_JS_URL}>/data/quiz-questions-01.js"></script>
<script src="<{$smarty.const.QUIZMAKER_QUIZ_JS_URL}>/data/quiz-options.js"></script>
<script src="<{$smarty.const.QUIZMAKER_QUIZ_JS_URL}>/js/quiz-fr.js"></script>

<script src="<{$smarty.const.QUIZMAKER_QUIZ_JS_URL}>/js/quiz-functions.js"></script>



<script src="<{$smarty.const.QUIZMAKER_QUIZ_JS_URL}>/js/quiz-events.js"></script>
<script src="<{$smarty.const.QUIZMAKER_QUIZ_JS_URL}>/js/quiz-main.js"></script>


