<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>
<{include file='db:quizmaker_header.tpl' }>

<div class='panel panel-<{$panel_type}>'>
<div class='panel-heading'>
<{$smarty.const._MA_QUIZMAKER_CATEGORIES_TITLE}></div>

<{foreach item=Categories from=$categories}>
<div class='panel panel-body'>
<{include file='db:quizmaker_categories_list.tpl' Categories=$Categories}>
<{if $Categories.count is div by $numb_col}>
<br>
<{/if}>

</div>

<{/foreach}>

</div>

<{include file='db:quizmaker_footer.tpl' }>
