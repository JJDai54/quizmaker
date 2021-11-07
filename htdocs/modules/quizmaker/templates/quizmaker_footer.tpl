<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>
<div class='pull-left'><{$copyright}></div>

<{if $pagenav != ''}>
	<div class='pull-right'><{$pagenav}></div>
<{/if}>
<br>
<{if $xoops_isadmin != ''}>
	<div class='text-center bold'><a href='<{$admin}>'><{$smarty.const._MA_QUIZMAKER_ADMIN}></a></div>
<{/if}>

<{if $comment_mode}>
	<div class='pad2 marg2'>
		<{if $comment_mode == "flat"}>
			<{include file='db:system_comments_flat.tpl' }>
		<{elseif $comment_mode == "thread"}>
			<{include file='db:system_comments_thread.tpl' }>
		<{elseif $comment_mode == "nest"}>
			<{include file='db:system_comments_nest.tpl' }>
		<{/if}>
	</div>
<{/if}>
<{include file='db:system_notification_select.tpl' }>
