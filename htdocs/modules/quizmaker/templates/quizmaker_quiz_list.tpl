<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>

<div class='panel-heading'>
</div>
<div class='panel-body'>
</div>
<div class='panel-foot'>
	<span class='col-sm-12'><a class='btn btn-primary' href='quiz.php?op=show&amp;quiz_id=<{$Quiz.quiz_id}>' title='<{$smarty.const._MA_QUIZMAKER_DETAILS}>'><{$smarty.const._MA_QUIZMAKER_DETAILS}></a></span>
</div>
