<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='plugins.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="plugin_list" />
<input type="hidden" name="quest_parent_id" value="0" />
<input type="hidden" name="quest_plugin" value="" />
    <div class="floatleft xo-buttons">
        <{$smarty.const._AM_QUIZMAKER_PLUGINS_CATEGORY}> : <{$inpCatTQ}>
         <{* <{$btnGoToQuestion}> *}>
    </div>


</form>


<{if $plugin_list}>
	<table id='quiz_plugin_list' name='quiz_plugin_list' class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_PLUGIN}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_NAME}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_SHORTDESC}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_INSTALL}></th>
				<th class="center"><{$smarty.const._EDIT}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_PLAY}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_HELP}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_IMAGE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ID}></th>

			</tr>
		</thead>
		<{if $plugin_count}>
		<tbody><br>      
            <{assign var="index" value="0"}>       
			<{foreach item=Plugin from=$plugin_list}>
             <{assign var=index value=$index+1}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class='center'><{$index}></td>
				<td class='left'><a name="signet-<{$Plugin.type}>"><{$Plugin.type}><br>(version : <{$Plugin.version}>)</a></td>
				<td class='left'><{$Plugin.name}></td>
				<td class='left width50'><{$Plugin.description}></td>
                
				<td class='center'>
                    <{if $Plugin.isArchive}> 
                      <a href='plugins.php?op=install&plugin=<{$Plugin.type}>&catPlugins=<{$catPlugins}>#signet-<{$Plugin.type}>'>
            			<img src="<{$modPathIcon32}>/install-red.png" alt="Play quiz" title='<{$smarty.const._AM_QUIZMAKER_INSTALL_QUIZ_EXEMPLE}>' />
                      </a>
                    <{else}>
            			<img src="<{$modPathIcon32}>/install-grey.png" alt="Play quiz" title='<{$smarty.const._AM_QUIZMAKER_INSTALL_QUIZ_EXEMPLE}>' />
                    <{/if}>
                </td>  
                
				<td class='center'>
                    <{if $Plugin.quiz_id <> 0}> 
                      <a href='questions.php?op=list&quiz_id=<{$Plugin.quiz_id}>'>
            			<img src="<{$modPathIcon32}>/edit-ok.png" alt="Play quiz" title='<{$smarty.const._AM_QUIZMAKER_EDIT_QUIZ}>' />
                      </a>
                    <{else}>
            			<img src="<{$modPathIcon32}>/edit-no.png" alt="Play quiz" title='<{$smarty.const._AM_QUIZMAKER_EDIT_QUIZ}>' />
                    <{/if}>
                </td>  
                
                
				<td class='center'>
                    <{if $Plugin.isBuild}> 
                      <a href='plugins.php?op=play&plugin=<{$Plugin.type}>&catPlugins=<{$catPlugins}>' target='blank'>
            			<img src="<{$modPathIcon32}>/play-green.png" alt="Play quiz" title='<{$smarty.const._AM_QUIZMAKER_PLAY_QUIZ}>' />
                      </a>
                    <{else}>
            			<img src="<{$modPathIcon32}>/play-grey.png" alt="Play quiz" title='<{$smarty.const._AM_QUIZMAKER_PLAY_QUIZ}>' />
                    <{/if}>
                </td>   
				<td class='center'>
                    <a href='' onclick="javascript:openWithSelfMain('<{$smarty.const.XOOPS_URL}>/modules/quizmaker/admin/plugins_help.php?op=view&plugin=<{$Plugin.type}>','<{$Plugin.type}>',680,600);return false;">
          				<img src="<{xoModuleIcons32}>faq.png" alt="" title='<{$smarty.const._AM_QUIZMAKER_HELP}>' />
                    </a>
                </td>   
                    
                <td class='left width20'>
<{* 
                    <div class='highslide-gallery'>
			         <{foreach item=modele from=$Plugin.modeles}>    
                        <a href='<{$modele}>' class='highslide' onclick='return hs.expand(this);' >
                            <img src="<{$modele}>" alt="slides" style="max-width:50px" />
                        </a>
                        <div class='highslide-heading'></div>
			         <{/foreach}>
                    </div>
*}>                 
                    <{$Plugin.modelesHtml}>
                                     
                </td>

				<td class='center'><{$index}></td>
                
			</tr>
			<{/foreach}>
		</tbody>
		<{/if}>
	</table>
	<div class="clear">&nbsp;</div>

<{/if}>

<script>
tth_set_value('last_asc', true);
tth_trierTableau('quiz_plugin_list', 2, "1,2,3,4");  
quizmaker_scrollWin();
</script>

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
