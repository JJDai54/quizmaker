<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<script>
function addNewChild(parentId){

  document.quizmaker_select_filter.op.value='new';
  document.quizmaker_select_filter.quest_parent_id.value=parentId;
  document.quizmaker_select_filter.submit();
  
  //document.quizmaker_select_filter.op.value + " - " + document.quizmaker_select_filter.quest_parent_id.value)
  //event.stopImmediatePropagation();
  return false;
}
</script>
<{* <{assign var='download' value=0}> *}>
<{include file='db:quizmaker_admin_download.tpl' }>


<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='questions.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="0" />
<input type="hidden" name="quest_parent_id" value="0" />
<div>
<div class="floatleft">

      <{* ======================================================== *}> 
      <table>
        <tr>
          <td class='right'><{$smarty.const._AM_QUIZMAKER_CATEGORIES_NAME}> : </td>
          <td><{$inpCategory}></td>
          <td class='right'><{$smarty.const._AM_QUIZMAKER_QUIZ_NAME}> : </td>
          <td><{$inpQuiz}></td>
        </tr>
      </table>
      <table>
        <tr>
          <td class='right'><{$smarty.const._CO_QUIZMAKER_PLUGIN}> : </td>
          <td><{$inpTypeQuest}></td>
          <td><div id='btnAddQuestion' class="xo-buttons"><{$btnNewQuestion}></div></td>
          <td><div style="height:55px;"><{$imgModelesHtml}></div></td>
        </tr>
      </table>
    </div>
</div>
<{* ======================================================== *}> 
<br>
<div class="floatright">
    <div class="xo-buttons">
        <{$btnEditQuiz}>
        <{$btnCategory}>
        <{$btnInitWeight}>
        <{$btnPurgerImg}>
        <{$btnExportQuiz}>
        <{$btnBuildHtml}>
        <{$imgTestHtml}>
        
    </div>
</div>
</form>
 <style>
 input[type="number"]{
   border-style:none;
}

 input[type="number"]:hover{
   border-style:solid;
   background:rgb(204,204,204);
   font-weight: bold;
   color:blue;
 }
 </style>   
<{if $questions_list}>
  
<form name='quizmaker_list' id='quizmaker_list' action='questions.php' method='post' enctype=''>
<input type="hidden" name="op"       value="update_list" />
<input type="hidden" name="cat_id"   value="<{$cat_id}>" />
<input type="hidden" name="quiz_id"  value="<{$quiz_id}>" />
<input type="hidden" name="quest_id" value="<{$quest_id}>" />

	<table id='quiz_question_list' name='quiz_question_list' class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center">*</th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_PARENT_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUESTIONS_QUIZ_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUESTIONS_PLUGIN}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_FORM_TYPE_SHORT}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUESTIONS_QUESTION}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_WEIGHT}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUESTIONS_POINTS}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_CHRONO}></th>
				<{* <th class="center"><{$smarty.const._AM_QUIZMAKER_QUESTIONS_CREATION}></th> *}>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_OPTIONS}></th>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_ACTIONS}></th>
			</tr>
		</thead>
		<{if $questions_count}>
		<tbody>
            <{assign var="indexJS" value = -1}>
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
                  
                <{elseif !$Questions.actif}>
                  <{assign var="fldImg" value="blue"}>
                  <{assign var="styleParent" value="style='background:#FFCCFF;color:red;'"}>
                <{else}>
                  <{assign var="fldImg" value="blue"}>
                  <{assign var="styleParent" value=""}>
                <{/if}>
                
                
  			<tr class='<{cycle values='odd, even'}>' style='height:40px;'>
                
                <{if $Questions.actif}>
                    <{assign var="indexJS" value=$indexJS+1}>
				    <td class='center width5' <{$styleParent}> ><{$index}>/<{$indexJS}></td>
                <{else}>
				    <td class='center width5' <{$styleParent}> ><{$index}>/##</td>
                <{/if}>
                
				<td class='center' <{$styleParent}> ><a name='question-<{$Questions.id}>' /><{$Questions.id}></td>
				<td class='center' <{$styleParent}> ><{$Questions.parent_id}>
                    <{if $Questions.parent_id > 0}>
    					<a href="questions.php?op=set_value&quest_id=<{$Questions.id}>&quiz_id=<{$Questions.quiz_id}>&field=quest_parent_id&value=0&doItForGroup=0" title="<{$smarty.const._AM_QUIZMAKER_OUT_OF_GROUP}>">
                            <img src="<{$modPathIcon16}>/out_of_group-green.png" alt="questions" />
                            </a>
                    <{elseif $Questions.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_GROUP}>
    					<a href="questions.php?op=set_value&quest_id=<{$Questions.id}>&quiz_id=<{$Questions.quiz_id}>&field=quest_parent_id&value=0&doItForGroup=1" title="<{$smarty.const._AM_QUIZMAKER_CHILDREZN_OUT_OF_GROUP}>">
                            <img src="<{$modPathIcon16}>/out_of_group-red.png" alt="questions" />
                            </a>
                    <{else $Questions.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_GROUP}>
                          <img src="<{$modPathIcon16}>/blank.png" alt="" />
                          <img src="<{$modPathIcon16}>/blank.png" alt="" />
                    <{/if}>              




                </td>
				<td class='center' <{$styleParent}> ><{$Questions.quiz_id}></td>
				<td class='left' <{$styleParent}> ><{$Questions.plugin}></td>
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
                
				<td class='center' <{$styleParent}> ><{$Questions.inpPoints}>
				
                <td class='center width10' <{$styleParent}> >
                      <{$Questions.inpTimer}><{$Questions.inpStartTimer}>
                </td>
                
				<{* <td class='center' <{$styleParent}> ><{$Questions.creation}></td> *}>
                
				<td class="center  width10" <{$styleParent}> >
                        
                    <{if $Questions.isQuestion}>
                        |<a href="questions.php?op=change_etat&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>&field=quest_numbering&modulo=4"  title='<{$smarty.const._AM_QUIZMAKER_NUMBERING}>' >
                            <{$Questions.flags.numbering}>
                            </a>|
                    <{/if}>
				</td>
                
				<td class="center width10" <{$styleParent}> >
                    <{if $Questions.plugin <> 'pageBegin'}>

                    <a href="questions.php?op=change_etat&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>&field=quest_actif" title='<{$smarty.const._AM_QUIZMAKER_ACTIF}>' >
        				 <img src="<{$modPathIcon16}>actif-<{$Questions.actif}>.png" alt="actif" title='<{$smarty.const._AM_QUIZMAKER_ACTIF}>' />
                        </a>
                    <{/if}>              
                        
                    <a href="questions.php?op=change_etat&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>&field=quest_visible" title='<{$smarty.const._AM_QUIZMAKER_VISIBLE}>' >
        				 <img src="<{$modPathIcon16}>visible-<{$Questions.visible}>.png" alt="visible" title='<{$smarty.const._AM_QUIZMAKER_VISIBLE}>' />
                        </a>
                                                     
					<a href="questions.php?op=edit&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>" title="<{$smarty.const._EDIT}>">
                        <img src="<{xoModuleIcons16}>/edit.png" alt="questions" />
                        </a>
                        
                    <{if $Questions.canDelete}>
<{* action du clone a revoir : probleme : copie de la table enfant au détriment de la questionsource
    					<a href="questions.php?op=clone&quiz_id=<{$Questions.quiz_id}>&quest_id=<{$Questions.id}>" title="<{$smarty.const._CLONE}>">
                            <img src="<{xoModuleIcons16}>/editcopy.png" alt="Clone" />
                            </a>
*}>                     
    					<a href="questions.php?op=delete&quest_id=<{$Questions.id}>" title="<{$smarty.const._DELETE}>">
                            <img src="<{xoModuleIcons16}>/delete.png" alt="questions" />
                            </a>
                    <{else}>
                          <img src="<{$modPathIcon16}>/blank.png" alt="" />
                          <img src="<{$modPathIcon16}>/blank.png" alt="" />
                    <{/if}>              

                    <{if $Questions.plugin == 'pageGroup' || $Questions.plugin == 'pageBegin'}>
    					<a  title="<{$smarty.const._ADD}>" onclick="addNewChild(<{$Questions.id}>);" >
                          <img src="<{xoModuleIcons16}>/add.png" alt="_ADD" />
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
<center><input type="submit" value="<{$smarty.const._SUBMIT}>" /><br /></center><hr>    
	<div class="clear">&nbsp;</div>
<{* 
	<{if $pagenav}>
		<div class="xo-pagenav floatright"><{$pagenav}></div>
		<div class="clear spacer"></div>
	<{/if}>
*}>     
</form>
<script>

function disabledItemParent(){
//alert('disabledItemParent');
  //var options = document.forms['aForm']['quest_plugin'].options;
  var options = document.getElementById('quest_plugin');
  for (var i=0, iLen=options.length; i<iLen; i++) {
  console.log('disabledItemParent : ' + i + '-' + options[i].innerHTML);
    if(options[i].innerHTML.substring(0,3) != "---"){
        options[i].disabled = true; 
    }
  }
}
disabledItemParent();

quizmaker_scrollWin();
reloadImgModeles("modelesTypeQuestionId");

tth_set_value('last_asc', true);
tth_trierTableau('quiz_question_list', 8);  

</script>

<{/if}>
<{if $form}>
	<{$form}>
<{/if}>

<{* <{if $error}> 
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>
*}>
<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>

