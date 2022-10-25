<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<script>
function addNewChild(parentId){
  //onclick="document.quizmaker_select_filter.op.value='new_question';document.quizmaker_select_filter.parent_id.value=<{$Questions.id}>;document.quizmaker_select_filter.submit();console.log(document.quizmaker_select_filter.op.value);">
  document.quizmaker_select_filter.op.value='new';
  document.quizmaker_select_filter.quest_parent_id.value=parentId;
  document.quizmaker_select_filter.submit();
  
  //document.quizmaker_select_filter.op.value + " - " + document.quizmaker_select_filter.quest_parent_id.value)
  //event.stopImmediatePropagation();
  return false;
}
</script>

<{include file='db:quizmaker_admin_download.tpl' }>

<{if $questions_list}>

<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='questions.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="0" />
<input type="hidden" name="quest_parent_id" value="0" />

<div class="floatleft">
    <div>
      <{* ======================================================== *}> 
      <table>
        <tr>
          <td class='right'><{$smarty.const._AM_QUIZMAKER_CATEGORIES}> : </td>
          <td><{$inpCategory}></td>
          <td class='left'></td>
          <td class='right'></td></tr>
        </tr>
        <tr>
          <td class='right'><{$smarty.const._AM_QUIZMAKER_QUIZ}> : </td>
          <td><{$inpQuiz}></td>
          <td class='right'><{$smarty.const._CO_QUIZMAKER_TYPE_QUESTION}> : </td>
          <td><{$inpTypeQuest}></td>
          <td  class="xo-buttons"><{$btnNewQuestion}></td>
          <td><{$imgModelesHtml}></td>
        </tr>
      </table>
    </div>
</div>
<{* ======================================================== *}> 

<div class="floatright">
    <div class="xo-buttons">
        <{$btnInitWeight}>
        <{$btnExportQuiz}>
        <{$btnBuildHtml}>
        <{$imgTestHtml}>
        
<{*
        <{$btn.exportQuiz}>
        <{$btn.restorQuiz}>
        <{$btn.importQuiz}>
*}>
    </div>
</div>
</form>


	<table id='quiz_question_list' name='quiz_question_list' class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUESTIONS_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_PARENT_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUESTIONS_QUIZ_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUESTIONS_TYPE_QUESTION}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_FORM_TYPE_SHORT}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUESTIONS_QUESTION}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_WEIGHT}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUESTIONS_MINREPONSE2}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_CHRONO}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUESTIONS_CREATION}></th>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_OPTIONS}></th>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_ACTIONS}></th>
			</tr>
		</thead>
		<{if $questions_count}>
		<tbody>
			<{foreach item=Questions from=$questions_list name=quest  key=index}>
                <{if $Questions.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_BEGIN}>
                  <{assign var="fldImg" value="red"}>
                  <{assign var="styleParent" value="style='background:navajowhite;'"}>
                  
                <{elseif $Questions.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_GROUP}>
                  <{assign var="fldImg" value="red"}>
                  <{assign var="styleParent" value="style='background:lightblue;'"}>
                  
                <{elseif $Questions.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_END}>
                  <{assign var="fldImg" value="red"}>
                  <{assign var="styleParent" value="style='background:mistyrose;'"}>
                  
                <{else}>
                  <{assign var="fldImg" value="blue"}>
                  <{assign var="styleParent" value=""}>
                <{/if}>
                
  			<tr class='<{cycle values='odd, even'}>'>
				<td class='center' <{$styleParent}> ><a name='question-<{$Questions.id}>' /><{$Questions.id}></td>
				<td class='center' <{$styleParent}> ><{$Questions.parent_id}></td>
				<td class='center' <{$styleParent}> ><{$Questions.quiz_id}></td>
				<td class='left' <{$styleParent}> ><{$Questions.type_question}></td>
				<td class='left' <{$styleParent}> ><{$Questions.typeForm_lib}></td>
                
                
                <td class='left' <{$styleParent}> >
					<a href="questions.php?op=edit&amp;quest_id=<{$Questions.id}>" title="<{$smarty.const._EDIT}>"  >
                    <{$Questions.question}></td></a>
                
                <{* ---------------- Arrows Weight -------------------- *}>
                <td class='center width15' <{$styleParent}> >

                <{if  $Questions.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_GROUP OR $Questions.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_QUESTION}>
                      <a href="questions.php?op=weight&quest_id=<{$Questions.id}>&sens=first&quiz_id=<{$Questions.quest_quiz_id}>&quest_weight=<{$Questions.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/first-1.png" title="<{$smarty.const._AM_QUIZMAKER_FIRST}>">
                      </a>
                    
                      <a href="questions.php?op=weight&quest_id=<{$Questions.id}>&sens=up&quiz_id=<{$Questions.quest_quiz_id}>&quest_weight=<{$Questions.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/up-1.png" title="<{$smarty.const._AM_QUIZMAKER_UP}>">
                      </a>
                    
                    <img src="<{$modPathIcon16}>/blank-08.png" title="">
                    <{$Questions.weight}>
                    <img src="<{$modPathIcon16}>/blank-08.png" title="">
                 
                      <a href="questions.php?op=weight&quest_id=<{$Questions.id}>&sens=down&quiz_id=<{$Questions.quest_quiz_id}>&quest_weight=<{$Questions.weight}>">
                        <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/down-1.png" title="<{$smarty.const._AM_QUIZMAKER_DOWN}>">
                        </a>
                   
                      <a href="questions.php?op=weight&quest_id=<{$Questions.id}>&sens=last&quiz_id=<{$Questions.quest_quiz_id}>&quest_weight=<{$Questions.weight}>">
                        <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/last-1.png" title="<{$smarty.const._AM_QUIZMAKER_LAST}>">
                        </a>
                <{else}>                     
                    <{$Questions.weight}>
                <{/if}>
                <{* ---------------- /Arrows -------------------- *}>
                </td>
                
				<td class='center' <{$styleParent}> ><{$Questions.minReponse}></td>
				<td class='center' <{$styleParent}> ><{$Questions.quest_timer}></td>
				<td class='center' <{$styleParent}> ><{$Questions.creation}></td>
                
				<td class="center  width10" <{$styleParent}> >
                    <a href="questions.php?op=change_etat&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>&field=quest_actif" title='<{$smarty.const._AM_QUIZMAKER_ACTIF}>' >
        				<{*  <img src="<{xoModuleIcons16}><{$Questions.actif}>.png" alt="actif" title='<{$smarty.const._AM_QUIZMAKER_ACTIF}>' /> *}>
                        <{$Questions.flags.actif}>
                        </a>|
                    <a href="questions.php?op=change_etat&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>&field=quest_visible" title='<{$smarty.const._AM_QUIZMAKER_VISIBLE}>' >
        				<{*  <img src="<{xoModuleIcons16}><{$Questions.visible}>.png" alt="Visible" title='<{$smarty.const._AM_QUIZMAKER_VISIBLE}>' /> *}>
                        <{$Questions.flags.visible}>
                        </a>|
                    <{if $Questions.isQuestion}>
                    <a href="questions.php?op=change_etat&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>&field=quest_numbering&modulo=3"  title='<{$smarty.const._AM_QUIZMAKER_NUMBERING}>' >
                        <{$Questions.flags.numbering}>
                        </a>|
                    
                    <a href="questions.php?op=change_etat&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>&field=quest_shuffleAnswers"  title='<{$smarty.const._AM_QUIZMAKER_SHUFFLE_ANS}>' >
        				<{*  <img src="<{xoModuleIcons16}><{$Questions.shuffleAnswers}>.png" alt="Visible" title='<{$smarty.const._AM_QUIZMAKER_SHUFFLE_ANS}>' /> *}>
                        <{$Questions.flags.shuffleAnswers}>
                        </a>|
                    
                    
                    
                    <a href="questions.php?op=change_etat&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>&field=quest_shuffleAnswers" >
                        </a>
                    
                    
                    
                    <{else}>
        				<img src="<{$modPathIcon16}>/grey.gif" alt="Visible" title='<{$smarty.const._AM_QUIZMAKER_NOT_QUESTION}>' />
                    <{/if}>
				</td>
                
				<td class="center width5" <{$styleParent}> >
					<a href="questions.php?op=edit&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>" title="<{$smarty.const._EDIT}>">
                        <img src="<{xoModuleIcons16 edit.png}>" alt="questions" />
                        </a>
                        
                    <{if $Questions.canDelete}>
    					<a href="questions.php?op=clone&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>" title="<{$smarty.const._CLONE}>">
                            <img src="<{xoModuleIcons16 editcopy.png}>" alt="Clone" />
                            </a>
    					<a href="questions.php?op=delete&amp;quest_id=<{$Questions.id}>" title="<{$smarty.const._DELETE}>">
                            <img src="<{xoModuleIcons16 delete.png}>" alt="questions" />
                            </a>
                    <{else}>
                          <img src="<{$modPathIcon16}>/blank.png" alt="" />
                          <img src="<{$modPathIcon16}>/blank.png" alt="" />
                    <{/if}>              

                    <{if $Questions.type_question == 'pageGroup' || $Questions.type_question == 'pageBegin'}>
    					<a  title="<{$smarty.const._ADD}>" onclick="addNewChild(<{$Questions.id}>);" >
                          <img src="<{xoModuleIcons16 add.png}>" alt="_ADD" />
                          </a>
                    <{else}>
                          <img src="<{$modPathIcon16}>/blank.png" alt="" />
                    <{/if}>    
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
<script>
tth_set_value('last_asc', true);
tth_trierTableau('quiz_question_list', 7);  
</script>

<{/if}>
<{if $form}>
	<{$form}>
<{/if}>
<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>
<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>

