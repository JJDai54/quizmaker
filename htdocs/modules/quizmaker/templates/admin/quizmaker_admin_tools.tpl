<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<{if $actions_list}>
<form name="form" id="form_tools" action="tools.php?op=list" method="post" enctype="multipart/form-data">
	<table class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_CODE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_DESCRIPTION}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ACTIONS}></th>
			</tr>
		</thead>
		<tbody>
			<{foreach item=Action from=$actions_list name=actions key=code}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class='left'><{$code}></td>
				<td class='left'><{$Action}></td>
				<td class='center'>
<input type="submit" class="formbutton" name="action[<{$code}>][minifie]"  id="action[<{$code}>][minifie]"  value="<{$smarty.const._AM_QUIZMAKER_TOOLS_MINIFIER}>" style='width:150px;'>                
<input type="submit" class="formbutton" name="action[<{$code}>][restaure]" id="action[<{$code}>][restaure]" value="<{$smarty.const._AM_QUIZMAKER_TOOLS_RESTAURE}>" style='width:150px;'>                
                </td>
			</tr>
			<{/foreach}>
		</tbody>
	</table>
</form> 
<{/if}>

<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>
<{*  ----------------------------------------------
*}> 

<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>
