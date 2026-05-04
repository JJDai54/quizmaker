<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>

<{include file='db:quizmaker_header.tpl' }>

<{*
<{if $categoriesCount > 1}>
    <{include file='db:quizmaker_categories_theme.tpl' }>
<{/if}>
*}>
 
<{* ************************************************ *}>
  <{foreach item=cat from=$categories }>
            <{include file='db:quizmaker_categories_item.tpl'}>
  <{/foreach}>
  

<{include file='db:quizmaker_footer.tpl' }>
