<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='questions.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="0" />
<input type="hidden" name="quest_parent_id" value="0" />

<div class="floatleft">
    <div class="xo-buttons">
<{$smarty.const._AM_QUIZMAKER_CATEGORIES}> : <{$inpCategory}>
<{$smarty.const._AM_QUIZMAKER_QUIZ}> : <{$inpQuiz}>
        <{$smarty.const._CO_QUIZMAKER_TYPE_QUESTION_2_ADD}> : <{$inpTypeQuest}> <{$btnNewQuestion}>
    </div>
    </div>

<div class="floatright">
    <div class="xo-buttons">
        <{$initWeight}>
        <{$expQuiz}>
        <{$btn.imgTest}>
        
<{*
        <{$btn.exportQuiz}>
        <{$btn.restorQuiz}>
        <{$btn.importQuiz}>
*}>
    </div>
</div>
<{if $inpTypeQuest}>
<{/if}>
</form>

<script>
function addNewChild(parentId){
//onclick="document.quizmaker_select_filter.op.value='new_question';document.quizmaker_select_filter.parent_id.value=<{$Questions.id}>;document.quizmaker_select_filter.submit();console.log(document.quizmaker_select_filter.op.value);">
document.quizmaker_select_filter.op.value='new_question';
document.quizmaker_select_filter.quest_parent_id.value=parentId;
document.quizmaker_select_filter.submit();

//document.quizmaker_select_filter.op.value + " - " + document.quizmaker_select_filter.quest_parent_id.value)
//event.stopImmediatePropagation();

return false;

}

</script>

<{if $questions_list}>
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
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUESTIONS_MINREPONSE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUESTIONS_CREATION}></th>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_OPTIONS}></th>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_FORM_ACTION}></th>
			</tr>
		</thead>
		<{if $questions_count}>
		<tbody>
			<{foreach item=Questions from=$questions_list name=quest}>
                <{if $Questions.parent_id==0}>
                  <{assign var="fldImg" value="red"}>
                  <{if $Questions.type_form == 2}>
                    <{assign var="styleParent" value="style='background:lightblue;'"}>
                  <{elseif $Questions.type_form == 3}>
                    <{assign var="styleParent" value="style='background:mistyrose;'"}>
                  <{else}>
                    <{assign var="styleParent" value="style='background:navajowhite;'"}>
                  <{/if}>
                <{else}>
                  <{assign var="fldImg" value="blue"}>
                  <{assign var="styleParent" value=""}>
                <{/if}>
                
                  
                
  			<tr class='<{cycle values='odd, even'}>'>
				<td class='center' <{$styleParent}> ><a name='question-<{$Questions.id}>' /><{$Questions.id}></td>
				<td class='center' <{$styleParent}> ><{$Questions.parent_id}></td>
				<td class='center' <{$styleParent}> ><{$Questions.quiz_id}></td>
				<td class='left' <{$styleParent}> ><{$Questions.type_question}></td>
				<td class='left' <{$styleParent}> ><{$Questions.type_form_lib}></td>
                
                
                <td class='left' <{$styleParent}> >
					<a href="questions.php?op=edit&amp;quest_id=<{$Questions.id}>" title="<{$smarty.const._EDIT}>"  >
                    <{$Questions.question}></td></a>
                
				<{* <td class='center'><{$Questions.weight}></td> *}>
                <{* ---------------- Arrows Weight -------------------- *}>
                <td class='center' <{$styleParent}> >
                    <{if $smarty.foreach.quest.first}>
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/first-0.png" title="<{$smarty.const._AM_QUIZMAKER_FIRST}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/up-0.png" title="<{$smarty.const._AM_QUIZMAKER_UP}>">
                    <{else}>
                      <a href="questions.php?op=weight&quest_id=<{$Questions.id}>&sens=first&quiz_id=<{$Questions.quest_quiz_id}>&quest_weight=<{$Questions.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/first-1.png" title="<{$smarty.const._AM_QUIZMAKER_FIRST}>">
                      </a>
                    
                      <a href="questions.php?op=weight&quest_id=<{$Questions.id}>&sens=up&quiz_id=<{$Questions.quest_quiz_id}>&quest_weight=<{$Questions.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/up-1.png" title="<{$smarty.const._AM_QUIZMAKER_UP}>">
                      </a>
                    <{/if}>
                 
                    <img src="<{$modPathIcon16}>/blank-08.png" title="">
                    <{$Questions.weight}>
                    <img src="<{$modPathIcon16}>/blank-08.png" title="">
                 
                    <{if $smarty.foreach.quest.last}>
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/down-0.png" title="<{$smarty.const._AM_QUIZMAKER_DOWN}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/last-0.png" title="<{$smarty.const._AM_QUIZMAKER_LAST}>">
                    <{else}>
                    
                    <a href="questions.php?op=weight&quest_id=<{$Questions.id}>&sens=down&quiz_id=<{$Questions.quest_quiz_id}>&quest_weight=<{$Questions.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/down-1.png" title="<{$smarty.const._AM_QUIZMAKER_DOWN}>">
                      </a>
                 
                    <a href="questions.php?op=weight&quest_id=<{$Questions.id}>&sens=last&quiz_id=<{$Questions.quest_quiz_id}>&quest_weight=<{$Questions.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/last-1.png" title="<{$smarty.const._AM_QUIZMAKER_LAST}>">
                      </a>
                    <{/if}>
                <{* ---------------- /Arrows -------------------- *}>
                </td>
                
				<td class='center' <{$styleParent}> ><{$Questions.minReponse}></td>
				<td class='center' <{$styleParent}> ><{$Questions.creation}></td>
                
				<td class="center  width5" <{$styleParent}> >
                    <a href="questions.php?op=change_etat&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>&field=quest_actif" >
        				<img src="<{xoModuleIcons16}><{$Questions.actif}>.png" alt="actif" title='<{$smarty.const._AM_QUIZMAKER_ACTIF}>' />
                        </a>
                    <a href="questions.php?op=change_etat&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>&field=quest_visible" >
        				<img src="<{xoModuleIcons16}><{$Questions.visible}>.png" alt="Visible" title='<{$smarty.const._AM_QUIZMAKER_VISIBLE}>' />
                        </a>
                    <{if $Questions.isQuestion}>
                    <a href="questions.php?op=change_etat&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>&field=quest_shuffleAnswers" >
        				<img src="<{xoModuleIcons16}><{$Questions.shuffleAnswers}>.png" alt="Visible" title='<{$smarty.const._AM_QUIZMAKER_SHUFFLE_ANS}>' />
                        </a>
                    <{else}>
        				<img src="<{$modPathIcon16}>/grey.gif" alt="Visible" title='<{$smarty.const._AM_QUIZMAKER_NOT_QUESTION}>' />
                    <{/if}>
				</td>
                
				<td class="center  width5" <{$styleParent}> >
                
					<a href="questions.php?op=edit&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>" title="<{$smarty.const._EDIT}>">
                        <img src="<{xoModuleIcons16 edit.png}>" alt="questions" />
                        </a>
					<a href="questions.php?op=clone&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>" title="<{$smarty.const._CLONE}>">
                        <img src="<{xoModuleIcons16 editcopy.png}>" alt="Clone" />
                        </a>
					<a href="questions.php?op=delete&amp;quest_id=<{$Questions.id}>" title="<{$smarty.const._DELETE}>">
                        <img src="<{xoModuleIcons16 delete.png}>" alt="questions" />
                        </a>
                    <{if $Questions.type_form == 2}>
                    
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

