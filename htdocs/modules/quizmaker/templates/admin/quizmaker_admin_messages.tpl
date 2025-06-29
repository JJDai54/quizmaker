<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<{if $messages_list}>
<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='messages.php?op=list' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
    <input type="hidden" name="op" value="list" />
    <{$smarty.const._AM_QUIZMAKER_LANGUAGE}> : <{$inpLanguage}>
</form>

	<table id='quiz_message_list' class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ID}></th>
				<th class="center"></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_MESSAGES_CODE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_MESSAGES_LANGUAGE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_MESSAGES_MESSAGE}></th>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_ACTION}></th>
			</tr>
		</thead>
		<{if $messages_count}>
		<tbody>
			<{foreach item=Messages from=$messages_list key=index}>
                <{assign var="chrono" value=$index+1}>
    			<tr class='<{cycle values='odd, even'}>'>
    				<td class='center'><{$Messages.id}></td>
    				<td class='center'><{$chrono}></td>
    				<td class='left'>
                        <a href="messages.php?op=edit&amp;msg_id=<{$Messages.id}>" title="<{$smarty.const._EDIT}>">
                        <{$Messages.code}></a></td>

    				<td class='left'><{$Messages.language}></td>
    				<td class='left'><{$Messages.message}></td>
    				<td class="center  width5">
    					<a href="messages.php?op=edit&amp;msg_id=<{$Messages.id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16}>/edit.png" alt="messages" /></a>
    					<a href="messages.php?op=delete&amp;msg_id=<{$Messages.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16}>/delete.png" alt="messages" /></a>
    				</td>
    			</tr>
			<{/foreach}>
		</tbody>
		<{/if}>
	</table>
<script>    
tth_set_value('last_asc', true);
tth_trierTableau('quiz_message_list', 2);  
</script>    
    
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
