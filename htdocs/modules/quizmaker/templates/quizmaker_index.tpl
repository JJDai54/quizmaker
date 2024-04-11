<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>
<{include file='db:quizmaker_header.tpl' }>

<{if $categoriesCount > 0}>
<div class='table-responsive'>
	<table class='table table-<{$table_type}>'>
		<thead>
			<tr class='head'>
				<th colspan='<{$divideby}>'><{$smarty.const._MA_QUIZMAKER_CATEGORIES_TITLE}></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<{foreach item=Categories from=$categories}>
				<td>
					<div class='panel panel-<{$panel_type}>'>
						<{include file='db:quizmaker_categories_item.tpl' }>
					</div>
				</td>
				<{if $Categories.count is div by $divideby}>
					</tr><tr>
				<{/if}>
				<{/foreach}>
			</tr>
		</tbody>
		<tfoot><tr><td>&nbsp;</td></tr></tfoot>
	</table>
</div>
<{/if}>
<{if $form}>
	<{$form}>
<{/if}>
<{if $error}>
	<{$error}>
<{/if}>

<{include file='db:quizmaker_footer.tpl'}>
