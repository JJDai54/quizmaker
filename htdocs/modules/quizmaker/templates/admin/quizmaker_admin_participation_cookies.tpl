<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>
<{* =================================================================== *}>

<{if $cookies_list}>
<{$smarty.const._AM_QUIZMAKER_COUNT_COOKIES}> = <{$countEnr}>
	<table id='quiz_cookies_list' class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUIZ_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_NAME}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_EMAIL}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_IP}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ATTEMPTS}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_DEAD_LINE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_DATE_UPDATE}></th>
                <{if $allowed_clear}>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_ACTION}></th>
                <{/if}>
                
			</tr>
		</thead>
		<{if $countEnr}>
		<tbody>
			<{foreach item=Cookie from=$cookies_list}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class='center'><{$Cookie.id}></td>
				<td class='center'><{$Cookie.quiz_id}></td>
				<td class='left'>
					<a href="<{$redirectURL}>&op=edit&cookie_id=<{$Cookie.id}>" title="<{$smarty.const._EDIT}>">
                        <{$Cookie.cookie_uname}> (#<{$Cookie.uid}>)
                </td>
				<td class='left'>
					<a href="<{$redirectURL}>&op=edit&cookie_id=<{$Cookie.id}>" title="<{$smarty.const._EDIT}>">

                        <{$Cookie.cookie_email}>
                </td>
				<td class='left'>
                      <{$Cookie.ip}>
                </td>
				<td class='center width5'>
                      <{$Cookie.attempts}>
                </td>
				<td class='center'><{$Cookie.dead_linef}></td>
				<td class='center'><{$Cookie.update}></td>
                <{if $allowed_clear}>
				<td class="center  width5">
					<a href="<{$redirectURL}>&op=edit&cookie_id=<{$Cookie.id}>" title="<{$smarty.const._EDIT}>">
                        <img src="<{xoModuleIcons16}>/edit.png" alt="cookies" />
                    </a>
					<a href="<{$redirectURL}>&op=delete&cookie_id=<{$Cookie.id}>" title="<{$smarty.const._DELETE}>">
                        <img src="<{xoModuleIcons16}>/delete.png" alt="cookies" />
                    </a>
				</td>
                <{/if}>
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

<script>
tth_set_value('last_asc', true);
tth_trierTableau('quiz_cookies_list', 1, "1,2,3,4,5,6,7,8");  
quizmaker_scrollWin();
</script>


