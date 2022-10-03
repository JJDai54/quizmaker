<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>
<{includeq file='db:quizmaker_breadcrumbs.tpl' }>

<{if $ads != ''}>
	<div class='center'><{$ads}></div>
<{/if}>
