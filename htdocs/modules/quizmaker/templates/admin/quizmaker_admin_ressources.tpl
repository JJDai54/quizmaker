<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='ressources.php' method='post' onsubmit='return xoopsFormValidate_form();'  enctype="multipart/form-data">
<input type="hidden" name="op" value="list" />

    <div class="floatleft xo-buttons">
        <{$smarty.const._AM_QUIZMAKER_FOLDER}> : <{$inpFolder}>
        <{$smarty.const._AM_QUIZMAKER_SELECT_NEW_IMAGE}><{$inpNewImg}>

        <{$inpAddNewImg}>
        <br><{$smarty.const._AM_QUIZMAKER_DEL_FILE_NOT_ALLOWED}>
    </div>

</form>





<{if $imgArr}>

<hr>
	<{foreach item="img" from=$imgArr}>
        <img src='<{$img.url}>' width='80px'>
	<{/foreach}>
<hr>

<{*
	<table id='quiz_plugin_list' name='quiz_plugin_list' class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center">index</th>
				<th class="center">icone</th>
				<th class="center">name</th>
				<th class="center">action</th>

			</tr>
		</thead>
		<{if $imgCount > 0}>
		<tbody><br>      
            <{assign var="index" value="0"}>       
			<{foreach item="img" from=$imgArr}>
               <{assign var=index value=$index+1}>            
			     <tr class='<{cycle values='odd, even'}>'>            
				<td class='center'><{$index}></td>
				<td class='center'><img src='<{$img.url}>' width='50px'></td>          
				<td class='left'><{$img.name}></td>            
				<td class='left width50'>action</td>            
            
			     </tr>
			<{/foreach}>
		</tbody>
		<{/if}>
	</table>
 *}>
	<div class="clear">&nbsp;</div>

<{/if}>




<{* 
<{if $form}>
	<{$form}>
<{/if}>
<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>
*}> 

<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>
