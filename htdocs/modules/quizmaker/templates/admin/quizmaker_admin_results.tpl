<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<{if $results_list}>
	<table class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_QUIZ_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_UID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_SCORE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_SCORE_MAX}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_SCORE_MIN}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_NBANSWERS}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_DURATION}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_NOTE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_CREATION}></th>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_FORM_ACTION}></th>
			</tr>
		</thead>
		<{if $results_count}>
		<tbody>
			<{foreach item=Results from=$results_list}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class='center'><{$Results.id}></td>
				<td class='center'><{$Results.quiz_id}></td>
				<td class='center'><{$Results.uid}></td>
				<td class='center'><{$Results.score}></td>
				<td class='center'><{$Results.sore_max}></td>
				<td class='center'><{$Results.sore_min}></td>
				<td class='center'><{$Results.answers_achieved}></td>
				<td class='center'><{$Results.duration}></td>
				<td class='center'><{$Results.note}></td>
				<td class='center'><{$Results.creation}></td>
				<td class="center  width5">
					<a href="results.php?op=edit&amp;result_id=<{$Results.id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="results" /></a>
					<a href="results.php?op=delete&amp;result_id=<{$Results.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="results" /></a>
				</td>
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
<{/if}>
<{if $form}>
	<{$form}>
<{/if}>

<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>
