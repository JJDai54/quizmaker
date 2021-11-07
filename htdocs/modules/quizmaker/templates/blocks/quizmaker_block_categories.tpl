<table class='table table-<{$table_type}>'>
	<thead>
		<tr class='head'>
			<th>&nbsp;</th>
			<th class='center'><{$smarty.const._MB_QUIZMAKER_CAT_NAME}></th>
			<th class='center'><{$smarty.const._MB_QUIZMAKER_CAT_UPDATE}></th>
		</tr>
	</thead>
	<{if count($block)}>
	<tbody>
		<{foreach item=Categories from=$block}>
		<tr class='<{cycle values="odd, even"}>'>
			<td class='center'><{$Categories.id}></td>
			<td class='center'><{$Categories.name}></td>
			<td class='center'><{$Categories.update}></td>
		</tr>
		<{/foreach}>
	</tbody>
	<{/if}>
	<tfoot><tr><td>&nbsp;</td></tr></tfoot>
</table>
