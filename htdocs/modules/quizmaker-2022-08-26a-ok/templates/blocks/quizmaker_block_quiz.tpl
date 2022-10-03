<table class='table table-<{$table_type}>'>
	<thead>
		<tr class='head'>
			<th>&nbsp;</th>
			<th class='center'><{$smarty.const._MB_QUIZMAKER_QUIZ_CAT_ID}></th>
			<th class='center'><{$smarty.const._MB_QUIZMAKER_QUIZ_NAME}></th>
		</tr>
	</thead>
	<{if count($block)}>
	<tbody>
		<{foreach item=Quiz from=$block}>
		<tr class='<{cycle values="odd, even"}>'>
			<td class='center'><{$Quiz.id}></td>
			<td class='center'><{$Quiz.cat_id}></td>
			<td class='center'><{$Quiz.name}></td>
		</tr>
		<{/foreach}>
	</tbody>
	<{/if}>
	<tfoot><tr><td>&nbsp;</td></tr></tfoot>
</table>
