<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>
<i id='questId_<{$Categories.cat_id}>'><{$Categories.id}>-<{$Categories.name}></i>
<div class='panel-heading'><{$Categories.id}>-<{$Categories.description}>
</div>
<div class='panel-body'>


	<table  >
		<tbody>
				<{foreach item=Quiz from=$Categories.quizChildren}>
			<tr>
                <{*
				<td>
                    <{$Quiz.id}> - 
				</td>
                *}>
				<td>
                    <a href="<{$Quiz.quiz_html}>" target='blank'><{$Quiz.name}></a>
				</td>
				<td>
                    <{$Quiz.description}>
				</td>
                    <{*
				<td>
                    <{if $Quiz.quiz_html <> '' }>
			<a class='btn btn-success right' href='<{$Quiz.quiz_html}>' title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>'><{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}></a>
                    <a href="<{$Quiz.quiz_html}>" target='blank'>
                        <img src="<{$modUrlImg}>/run_quiz-01.gif" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' height='16px'/>
                        </a>
            
                    <{else}>
                    <{/if}>
				</td>
                    *}>


				<td>
                    <{if $Quiz.quiz_html <> '' }>
                    <a href="quiz_display.php?op=run&quiz_id=<{$Quiz.id}>" >
                        <img src="<{$modUrlImg}>/run_quiz-01.gif" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' height='16px'/>
                        </a>
                    <{/if}>
				</td>



			</tr>
                
				<{/foreach}>
		</tbody>
		<tfoot><tr><td>&nbsp;</td></tr></tfoot>
	</table>

</div>



<div class='panel-foot'>
	<div class='col-sm-12 right'>
		<{if $showItem}>
			<a class='btn btn-success right' href='questions.php?op=list&amp;#questId_<{$Questions.quest_id}>' title='<{$smarty.const._MA_QUIZMAKER_QUESTIONS_LIST}>'><{$smarty.const._MA_QUIZMAKER_QUESTIONS_LIST}></a>
		<{else}>
			<a class='btn btn-success right' href='questions.php?op=show&amp;quest_id=<{$Questions.quest_id}>' title='<{$smarty.const._MA_QUIZMAKER_DETAILS}>'><{$smarty.const._MA_QUIZMAKER_DETAILS}></a>
		<{/if}>
		<{if $permEdit}>
			<a class='btn btn-primary right' href='questions.php?op=edit&amp;quest_id=<{$Questions.quest_id}>' title='<{$smarty.const._EDIT}>'><{$smarty.const._EDIT}></a>
			<a class='btn btn-danger right' href='questions.php?op=delete&amp;quest_id=<{$Questions.quest_id}>' title='<{$smarty.const._DELETE}>'><{$smarty.const._DELETE}></a>
		<{/if}>
		<a class='btn btn-warning right' href='questions.php?op=broken&amp;quest_id=<{$Questions.quest_id}>' title='<{$smarty.const._MA_QUIZMAKER_BROKEN}>'><{$smarty.const._MA_QUIZMAKER_BROKEN}></a>
	</div>
</div>
