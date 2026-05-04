<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>
<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='readme.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="0" />
<input type="hidden" name="quest_parent_id" value="0" />

<div class="floatleft">
    <div class="xo-buttons">
<{$smarty.const._AM_QUIZMAKER_CATEGORIES_NAME}> : <{$inpCategory}>
<{$smarty.const._AM_QUIZMAKER_QUIZ_NAME}> : <{$inpQuiz}>
<{$btn.razCookies}>        
    </div>
    </div>

<div class="floatright">
    <div class="xo-buttons">
        <{$initWeight}>
        <{$btn.imgTest}>
        
    </div>
</div>

</form>
<{* =================================================================== *}>

<{if $readme_list}>
	<table class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_CATEGORY}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_USER}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_COUNT}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_CREATION}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_UPDATE}></th>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_ACTION}></th>
			</tr>
		</thead>
		<{if $readme_count}>
		<tbody>
			<{foreach item=readme from=$readme_list}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class='center'><{$readme.id}></td>
				<td class='center'>
                    <a href='<{$smarty.const.XOOPS_URL}>/modules/quizmaker/admin/categories.php'>
                    (#<{$readme.cat_id}>) <{$catArr[$readme.cat_id]}>
                    </a>
                </td>
				<td class='center'>
                    <a href='<{$smarty.const.XOOPS_URL}>/modules/profile/userinfo.php?uid=<{$readme.uid}>'>
                    (#<{$readme.uid}>) <{$readme.email}>
                    </a>
                </td>
				<td class='center'><{$readme.count}></td>
				<td class='center'><{$readme.creation}></td>
				<td class='center'><{$readme.update}></td>                
                
				<td class="center  width5">
					<{*<a href="readme.php?op=edit&amp;readme_id=<{$readme.id}>&uid=<{$readme.uid}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16}>/edit.png" alt="readme" /></a> *}>
					<a href="readme.php?op=delete&amp;readme_cat_id=<{$readme.cat_id}>&readme_id=<{$readme.id}>&uid=<{$readme.uid}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16}>/delete.png" alt="readme" /></a>
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
