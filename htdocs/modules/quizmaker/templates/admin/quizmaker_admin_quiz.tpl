<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<{assign var="fldImg" value="blue"}>
<{assign var="styleParent" value=""}>


<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='quiz.php?op=list' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />

<{$smarty.const._AM_QUIZMAKER_CATEGORIES}> : <{$inpCategory}>

</form>

<style>				
img{
    margin: 0px 3px 0px 3px;
}
</style>				


<{if $quiz_list}>
	<table id='quiz_quiz_list' name='quiz_quiz_list' class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUIZ_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_CATEGORY}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUIZ_NAME}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_WEIGHT}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_FILE_NAME}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_THEME}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_DATE_BEGIN_END}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_PERIODE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_PUBLISH}></th>
                                
				<th class="center"><{$smarty.const._AM_QUIZMAKER_OPTIONS}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_CONFIGS_OPTIONS}></th>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_FORM_ACTION}></th>
			</tr>
		</thead>
		<{if $quiz_count}>
		<tbody>
			<{foreach item=Quiz from=$quiz_list name=quizItem}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class='center'><{$Quiz.id}></td>
                <{* ========================================================== 
				<td class='left'><{$cat[$Quiz.cat_id]}>
                </td>
                *}>
                
				<td class='left'>
                    <a href="categories.php?op=edit&cat_id=<{$Quiz.cat_id}>" title="<{$smarty.const._EDIT}>">
                    <{$cat[$Quiz.cat_id]}></a>
                </td>
                
                
                <{*

                *}>
                <{* ========================================================== *}>
				
                <td class='left'>
					<a href="quiz.php?op=edit&amp;quiz_id=<{$Quiz.id}>" title="<{$smarty.const._EDIT}>">
                        <{$Quiz.name}></a></td>
                        
                <{* ---------------- Arrows Weight -------------------- *}>
                <td class='center width10'>
                  <{if $smarty.foreach.quizItem.first}>
                    <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/first-0.png" title="<{$smarty.const._AM_QUIZMAKER_FIRST}>"><img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/up-0.png" title="<{$smarty.const._AM_QUIZMAKER_UP}>">
                  <{else}>
                    <a href="quiz.php?op=weight&quiz_id=<{$Quiz.id}>&sens=first&&quiz_weight=<{$Quiz.weight}>">
                    <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/first-1.png" title="<{$smarty.const._AM_QUIZMAKER_FIRST}>">
                    </a>
                  
                    <a href="quiz.php?op=weight&quiz_id=<{$Quiz.id}>&sens=up&&quiz_weight=<{$Quiz.weight}>">
                    <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/up-1.png" title="<{$smarty.const._AM_QUIZMAKER_UP}>">
                    </a>
                  <{/if}>
               
                  <{* ----------------------------------- *}>
                  <img src="<{$modPathIcon16}>/blank-08.png" title="">
                  <{$Quiz.weight}>
                  <img src="<{$modPathIcon16}>/blank-08.png" title="">
                  <{* ----------------------------------- *}>
               
                  <{if $smarty.foreach.quizItem.last}>
                    <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/down-0.png" title="<{$smarty.const._AM_QUIZMAKER_DOWN}>">
                    <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/last-0.png" title="<{$smarty.const._AM_QUIZMAKER_LAST}>">
                  <{else}>
                  
                  <a href="quiz.php?op=weight&quiz_id=<{$Quiz.id}>&sens=down&&quiz_weight=<{$Quiz.weight}>">
                    <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/down-1.png" title="<{$smarty.const._AM_QUIZMAKER_DOWN}>">
                    </a>
               
                  <a href="quiz.php?op=weight&quiz_id=<{$Quiz.id}>&sens=last&&quiz_weight=<{$Quiz.weight}>">
                    <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/last-1.png" title="<{$smarty.const._AM_QUIZMAKER_LAST}>">
                    </a>
                  <{/if}>
                </td>
                <{* ---------------- /Arrows -------------------- *}>
                
				<td class='left'>
                    <{$Quiz.quiz_fileName}>
                </td>
                
				<td class='left'>
                    <{$Quiz.theme_ok}>
                </td>
               
				<td class='center'>
                    <{$Quiz.dateBegin}>
                    <img src="<{xoModuleIcons16}><{$Quiz.dateBeginOk}>.png" alt="quiz" /><br>
                    <{$Quiz.dateEnd}>
                    <img src="<{xoModuleIcons16}><{$Quiz.dateEndOk}>.png" alt="quiz" />
                </td>
				<td class='center'>
                    <img src="<{xoModuleIcons16}><{$Quiz.periodeOK}>.png" alt="OK" />
                </td>
				<td class='center'>
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_actif"   title='<{$smarty.const._AM_QUIZMAKER_PUBLISH_ACTIF}>' >
                        <{$Quiz.flags.actif}>
                        </a>
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_publishResults&modulo=3"  title='<{$smarty.const._AM_QUIZMAKER_PUBLISH_RESULTS}>' ><b>
                        <{$Quiz.flags.publishResults}>
                        </b></a>
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_publishAnswers&modulo=3"  title='<{$smarty.const._AM_QUIZMAKER_PUBLISH_ANSWERS}>' ><b>
                        <{$Quiz.flags.publishAnswers}>
                        </b></a>
                        |
                        <img src="<{xoModuleIcons16}><{$Quiz.publishResultsOk}>.png" alt="" title='<{$smarty.const._AM_QUIZMAKER_PUBLISH_RESULTS}>' />
                        <img src="<{xoModuleIcons16}><{$Quiz.publishAnswersOk}>.png" alt="" title='<{$smarty.const._AM_QUIZMAKER_PUBLISH_ANSWERS}>' />
                </td>
                
				<td class='center'>
                        
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_onClickSimple"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_ONCLICK}>' >
                        <{$Quiz.flags.onClickSimple}>
                        </a>|
                        
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_answerBeforeNext"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_ANSWERBEFORENEXT}>' >
                        <{$Quiz.flags.answerBeforeNext}>
                        </a>|
                        
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_allowedPrevious" title='<{$smarty.const._AM_QUIZMAKER_QUIZ_ALLOWEDPREVIOUS}>'  >
                        <{$Quiz.flags.allowedPrevious}>                     
                        </a>|
                        
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_shuffleQuestions" title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHUFFLE_QUESTION}>'  >
                        <{$Quiz.flags.shuffleQuestions}>                     
                        </a>|

                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_showGoodAnswers"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOW_GOOD_ANSWERS}>'>
                        <{$Quiz.flags.showGoodAnswers}>                     
                        </a>|

                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_showBadAnswers" title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOW_BAD_ANSWERS}>' >
                        <{$Quiz.flags.showBadAnswers}>                     
                        </a>|
                        
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_showReloadAnswers" title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOW_RELOAD_ANSWERS}>' >
                        <{$Quiz.flags.showReloadAnswers}>                     
                        </a>|
                        
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_allowedSubmit" title='<{$smarty.const._AM_QUIZMAKER_QUIZ_ALLOWEDSUBMIT}>' >
                        <{$Quiz.flags.allowedSubmit}>                     
                        </a>|
                        
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_useTimer" title='<{$smarty.const._AM_QUIZMAKER_QUIZ_USE_TIMER}>' >
                        <{$Quiz.flags.useTimer}>                     
                        </a>|

                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_showResultPopup" title='<{$smarty.const._AM_QUIZMAKER_QUIZ_RESULT_POPUP}>' >
                        <{$Quiz.flags.showResultPopup}>                     
                        </a>|
                                                
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_showResultAllways" >
        				<img src="<{xoModuleIcons16}><{$Quiz.showResultAllways}>.png" alt="quiz" title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOWRESULTALLWAYS}>' />
                        </a>
                        
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_showReponsesBottom" >
        				<img src="<{xoModuleIcons16}><{$Quiz.showReponsesBottom}>.png" alt="quiz" title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOWREPONSES}>' />
                        </a>
                        
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_showLog" >
        				<img src="<{xoModuleIcons16}><{$Quiz.showLog}>.png" alt="quiz" title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOWLOG}>' />
                        </a>
                        
                    <a href="quiz.php?op=change_etat&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&field=quiz_showTypeQuestion" >
        				<img src="<{xoModuleIcons16}><{$Quiz.showTypeQuestion}>.png" alt="quiz" title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOW_TYPE_QUESTION}>' />
                        </a>
                </td>
                
				<td class="center  width10">
                    <a href="quiz.php?op=config_options&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&config=0" >
                        <img src="<{$modPathIcon16}>/green.gif" alt="quiz" title='<{$smarty.const._AM_QUIZMAKER_CONFIG_PROD}>' />
                        </a>
                    <a href="quiz.php?op=config_options&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&config=1" >
                        <img src="<{$modPathIcon16}>/red.gif" alt="quiz" title='<{$smarty.const._AM_QUIZMAKER_CONFIG_DEV}>' />
                        </a>
                </td>
                
				<td class="center  width10">
					<a href="quiz.php?op=edit&amp;quiz_id=<{$Quiz.id}>" title="<{$smarty.const._EDIT}>">
                        <img src="<{xoModuleIcons16 edit.png}>" alt="quiz" />
                        </a>
                        
					<a href="quiz.php?op=delete&amp;quiz_id=<{$Quiz.id}>" title="<{$smarty.const._DELETE}>">
                        <img src="<{xoModuleIcons16 delete.png}>" alt="quiz" />
                        </a>
					
                    <a href='<{$smarty.const.QUIZMAKER_URL}>/admin/questions.php?quiz_id=<{$Quiz.id}>&cat_id=<{$Quiz.cat_id}>&sender='  title="<{$smarty.const._AM_QUIZMAKER_QUESTIONS}>">
                        <img src="<{xoModuleIcons16 inserttable.png}>" alt="" />
                        </a>
                        
                    <a href="quiz.php?op=export_json&quiz_id=<{$Quiz.id}>&cat_id=<{$Quiz.cat_id}>"  title="<{$smarty.const._AM_QUIZMAKER_QUIZ_BUILD}> : <{$Quiz.build}>">
                        <img src="<{xoModuleIcons16 spinner.gif}>" alt="" />
                        </a>

                    <{if $Quiz.quiz_html <> ''}>
                      <a href="<{$Quiz.quiz_html}>" target="blank">
                          <img src="<{$modPathIcon16}>/quiz-1.png" alt="" title="<{$smarty.const._AM_QUIZMAKER_QUIZ_BUILD}> : <{$Quiz.build}>"/>
                      </a>
                    <{else}>
                          <img src="<{$modPathIcon16}>/quiz-0.png" alt="" title="<{$smarty.const._AM_QUIZMAKER_QUIZ_BUILD}> : <{$Quiz.build}>"/>
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
<{/if}>
<{if $form}>
	<{$form}>
<{/if}>
<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<script>
tth_set_value('last_asc', true);
tth_trierTableau('quiz_quiz_list', 2, "1,2,3,4,5,6");  
</script>

<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>

