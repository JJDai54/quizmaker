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
				<th class="center"><{$smarty.const._EDIT}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_INSTALL}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_PLAY}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_HELP}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_IMAGE}></th>

			</tr>
		</thead>
		<{if $plugin_count}>
		<tbody><br>      
            <{assign var="index" value="0"}>       
			<{foreach item=TypeDeQuestion from=$plugin_list}>
             <{assign var=index value=$index+1}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class='center'><{$index}></td>
				<td class='left'><{$TypeDeQuestion.type}></td>
				<td class='left'><{$TypeDeQuestion.name}></td>
				<td class='left width50'><{$TypeDeQuestion.description}></td>
                
				<td class='center'>
                    <{if $TypeDeQuestion.quiz_id <> 0}> 
                      <a href='questions.php?op=list&quiz_id=<{$TypeDeQuestion.quiz_id}>'>
            			<img src="<{$modPathIcon32}>/edit-ok.png" alt="Play quiz" title='<{$smarty.const._EDIT}>' />
                      </a>
                    <{else}>
            			<img src="<{$modPathIcon32}>/edit-no.png" alt="Play quiz" title='<{$smarty.const._EDIT}>' />
                    <{/if}>
                </td>  
                
                
                
				<td class='center'>
                    <{if $TypeDeQuestion.isArchive}> 
                      <a href='plugins.php?op=install&plugin=<{$TypeDeQuestion.type}>&catPlugins=<{$catPlugins}>'>
            			<img src="<{$modPathIcon32}>/install-red.png" alt="Play quiz" title='<{$smarty.const._AM_QUIZMAKER_PLAY_QUIZ}>' />
                      </a>
                    <{else}>
            			<img src="<{$modPathIcon32}>/install-grey.png" alt="Play quiz" title='<{$smarty.const._AM_QUIZMAKER_PLAY_QUIZ}>' />
                    <{/if}>
                </td>  
				<td class='center'>
                    <{if $TypeDeQuestion.isBuild}> 
                      <a href='plugins.php?op=play&plugin=<{$TypeDeQuestion.type}>&catPlugins=<{$catPlugins}>' target='blank'>
            			<img src="<{$modPathIcon32}>/play-green.png" alt="Play quiz" title='<{$smarty.const._AM_QUIZMAKER_PLAY_QUIZ}>' />
                      </a>
                    <{else}>
            			<img src="<{$modPathIcon32}>/play-grey.png" alt="Play quiz" title='<{$smarty.const._AM_QUIZMAKER_PLAY_QUIZ}>' />
                    <{/if}>
                </td>   
				<td class='center'>
                    <a href='' onclick="javascript:openWithSelfMain('<{$smarty.const.XOOPS_URL}>/modules/quizmaker/admin/plugins_help.php?op=view&plugin=<{$TypeDeQuestion.type}>','<{$TypeDeQuestion.type}>',680,600);return false;">
          				<img src="<{xoModuleIcons32}>faq.png" alt="" title='<{$smarty.const._AM_QUIZMAKER_HELP}>' />
                    </a>
                </td>   
                    
                <td class='left width20'>
<{* 
                    <div class='highslide-gallery'>
			         <{foreach item=modele from=$TypeDeQuestion.modeles}>    
                        <a href='<{$modele}>' class='highslide' onclick='return hs.expand(this);' >
                            <img src="<{$modele}>" alt="slides" style="max-width:50px" />
                        </a>
                        <div class='highslide-heading'></div>
			         <{/foreach}>
                    </div>
*}>                 
                    <{$TypeDeQuestion.modelesHtml}>
                                     
                </td>

                
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
