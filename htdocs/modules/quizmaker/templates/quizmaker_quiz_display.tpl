<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>
<{include file='db:quizmaker_header.tpl' }>

<{* <hr><{$quiz_html}><hr> *}>
<{include file="$quiz_html"}>

<{include file='db:quizmaker_footer.tpl' }>