<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>
<{* =================================================================== *}>

<{if $results_list}>
<{$smarty.const._AM_QUIZMAKER_COUNT_PARTICIPATIONS}> = <{$countEnr}>
	<table id='quiz_result_list' class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_QUIZ_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_NAME}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_EMAIL}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_IP}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_SCORE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_NOTE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_SCORE_MINMAX}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_NBANSWERS}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_DURATION}></th>
				<th class="center"><{$smarty.const._CO_QUIZMAKER_TIME}></th>
                <{if $allowed_clear == true}>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_ACTION}></th>
                <{/if}>
                
			</tr>
		</thead>
		<{if $countEnr}>
		<tbody>
			<{foreach item=Result from=$results_list}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class='center'><{$Result.id}></td>
				<td class='center'><{$Result.quiz_id}></td>
				<td class='left'>
					<a href="<{$redirectURL}>&op=edit&result_id=<{$Result.id}>" title="<{$smarty.const._EDIT}>">
                        <{$Result.result_uname}> (#<{$Result.uid}>)
                </td>
				<td class='left'>
					<a href="<{$redirectURL}>&op=edit&result_id=<{$Result.id}>" title="<{$smarty.const._EDIT}>">
                        <{$Result.result_email}>
                </td>
				<td class='left'>
                      <{$Result.ip}>
                </td>
				<td class='center'><{$Result.score_achieved}> / <{$Result.score_max}></td>
				<td class='center'><{$Result.note}> / 100</td>
				<td class='center'><{$Result.score_min}> / <{$Result.score_max}></td>
				<td class='center'><{$Result.answers_achieved}> / <{$Result.answers_total}></td>
				<td class='center'><{$Result.duration}></td>
				<td class='center'><{$Result.creation}></td>
                <{if $allowed_clear}>
				<td class="center  width5">
					<a href="<{$redirectURL}>&op=edit&result_id=<{$Result.id}>" title="<{$smarty.const._EDIT}>">
                        <img src="<{xoModuleIcons16}>/edit.png" alt="results" />
                    </a>
					<a href="<{$redirectURL}>&op=delete&result_id=<{$Result.id}>" title="<{$smarty.const._DELETE}>">
                        <img src="<{xoModuleIcons16}>/delete.png" alt="results" />
                        </a>
				</td>
                <{/if}>
			</tr>
			<{/foreach}>
		</tbody>
		<{/if}>
	</table>
	<div class="clear">&nbsp;</div>
	<{if $pagenav}>
		<div class="xo-pagenav floatright"><{$pagenav}></div>
		<div class="clear spacer"></div>
	<{/if}>
<script>
tth_set_value('last_asc', true);
tth_trierTableau('quiz_result_list', 1, "1,2,3,4,5,6,7,8");  
quizmaker_scrollWin();
</script>

<{/if}>
<{*========================================= *}>
<{if $form}>
	<{$form}>
<{/if}>

<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>


