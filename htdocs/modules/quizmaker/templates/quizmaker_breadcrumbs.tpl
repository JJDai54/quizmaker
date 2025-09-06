<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>
<ol class='breadcrumb'>
	<li class='bc-item'><a href='<{xoAppUrl 'index.php'}>' title='home'><i class="glyphicon glyphicon-home"></i></a></li>
	<{foreach item=itm from=$xoBreadcrumbs name=bcloop}>
        <img src='assets/images/fleche_verte_01.png' style='height:16px;margin:3px 5px 2px 5px;' title='' alt=''>
        <li class='bc-item'>
          <{if isset($itm.link)}>
          	<a href='<{$itm.link}>' title='<{$itm.title}>'><{$itm.title}></a>
          <{else}>
          	<{$itm.title}>
          <{/if}>
        </li>
	<{/foreach}>
</ol>
