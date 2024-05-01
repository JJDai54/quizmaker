<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>
<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='results.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="0" />
<input type="hidden" name="quest_parent_id" value="0" />

<div class="floatleft">
    <div class="xo-buttons">
<{$smarty.const._AM_QUIZMAKER_CATEGORIES}> : <{$inpCategory}>
<{$smarty.const._AM_QUIZMAKER_QUIZ}> : <{$inpQuiz}>
<{$btn.razResults}>        
    </div>
    </div>

<div class="floatright">
    <div class="xo-buttons">
        <{$initWeight}>
        <{$expQuiz}>
        <{$btn.imgTest}>
        
    </div>
</div>

</form>
<{* =================================================================== *}>

<{if $results_list}>
	<table class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_QUIZ_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_NAME}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_SCORE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_NOTE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_SCORE_MINMAX}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_NBANSWERS}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_RESULTS_DURATION}></th>
				<th class="center"><{$smarty.const._CO_QUIZMAKER_TIME}></th>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_ACTION}></th>
			</tr>
		</thead>
		<{if $results_count}>
		<tbody>
			<{foreach item=Results from=$results_list}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class='center'><{$Results.id}></td>
				<td class='center'><{$Results.quiz_id}></td>
				<td class='left'>
                    /* <a href="results.php?op=edit&amp;result_id=<{$Results.id}>" title="<{$smarty.const._EDIT}>"  > */
                        <{$Results.result_uname}> (#<{$Results.uid}>)
                    /* </a> */

                </td>
				<td class='center'><{$Results.score_achieved}> / <{$Results.score_max}></td>
				<td class='center'><{$Results.note}> / 100</td>
				<td class='center'><{$Results.score_min}> / <{$Results.score_max}></td>
				<td class='center'><{$Results.answers_achieved}> / <{$Results.answers_total}></td>
				<td class='center'><{$Results.duration}></td>
				<td class='center'><{$Results.creation}></td>
				<td class="center  width5">
                    <{*
                    *}>
					<a href="results.php?op=edit&amp;result_id=<{$Results.id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16}>/edit.png" alt="results" /></a>
					<a href="results.php?op=delete&amp;quiz_id=<{$Results.quiz_id}>&amp;result_id=<{$Results.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16}>/delete.png" alt="results" /></a>
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
