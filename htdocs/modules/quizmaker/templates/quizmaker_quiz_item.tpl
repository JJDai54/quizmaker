<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>
<i id='quizId_<{$Quiz.quiz_id}>'></i>
<div class='panel-heading'>
</div>
<div class='panel-body'>
</div>
<div class='panel-foot'>
	<div class='col-sm-12 right'>
		<{if $showItem}>
			<a class='btn btn-success right' href='quiz.php?op=list&amp;#quizId_<{$Quiz.quiz_id}>' title='<{$smarty.const._MA_QUIZMAKER_QUIZ_LIST}>'><{$smarty.const._MA_QUIZMAKER_QUIZ_LIST}></a>
		<{else}>
			<a class='btn btn-success right' href='quiz.php?op=show&amp;quiz_id=<{$Quiz.quiz_id}>' title='<{$smarty.const._MA_QUIZMAKER_DETAILS}>'><{$smarty.const._MA_QUIZMAKER_DETAILS}></a>
		<{/if}>
		<{if $permEdit}>
			<a class='btn btn-primary right' href='quiz.php?op=edit&amp;quiz_id=<{$Quiz.quiz_id}>' title='<{$smarty.const._EDIT}>'><{$smarty.const._EDIT}></a>
			<a class='btn btn-danger right' href='quiz.php?op=delete&amp;quiz_id=<{$Quiz.quiz_id}>' title='<{$smarty.const._DELETE}>'><{$smarty.const._DELETE}></a>
		<{/if}>
		<a class='btn btn-warning right' href='quiz.php?op=broken&amp;quiz_id=<{$Quiz.quiz_id}>' title='<{$smarty.const._MA_QUIZMAKER_BROKEN}>'><{$smarty.const._MA_QUIZMAKER_BROKEN}></a>
	</div>
</div>
