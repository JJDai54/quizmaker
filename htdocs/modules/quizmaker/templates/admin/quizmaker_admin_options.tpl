<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<{assign var="styleParent" value=""}>


<{if $options_list}>

	<table class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_NAME}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ICONE}></th>
				<th class="center"></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUIZ_OPTIONS_IHM}></th>
				<th class="center"></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_QUIZ_OPTIONS_DEV}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ACTION}></th>
			</tr>
		</thead>
		<{if $options_count}>
		<tbody>
			<{foreach item=binOpt from=$options_list name=optItem}>
			<tr class="<{cycle values='odd, even'}>">            
				<td class='center'><{$binOpt.id}></td>
            
				<td class='left'>
                    <a href="options.php?op=edit&opt_id=<{$binOpt.id}>" title="<{$smarty.const._EDIT}>">
                    <{$binOpt.name}></a>
                </td>
            
				<td class='center'>
                    <img src="<{$modPathIcon16}>/binoptions/<{$binOpt.icone}>" alt="config" title='<{$binOpt.name}>' />
                    <{* <{$binOpt.icone}> *}>
                </td>
				<td class='right'><{$binOpt.optionsIhm}>&nbsp;===></td>
				<td class='left'> 
                   <{assign var="urlOptionsIhm" value="options.php?op=set_bit&opt_id=`$binOpt.id`&field=opt_optionsIhm"}>

                   <a href="<{$urlOptionsIhm}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_ALLOWEDSUBMIT}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_ALLOWEDSUBMIT}>' >
                        <{$binOpt.flags.allowedSubmit}>
                        </a>|
                   <a href="<{$urlOptionsIhm}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_SHOW_SCOREMINMAX}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOW_SCORE_MIN_MAX}>' >
                        <{$binOpt.flags.showScoreMinMax}>
                        </a>|
                   <a href="<{$urlOptionsIhm}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_SHOW_ALLSOLUTIONS}>"  title='<{$smarty.const._AM_QUIZMAKER_VIEW_ALL_SOLUTIONS}>' >
                        <{$binOpt.flags.showAllSolutions}>
                        </a>|
                   <a href="<{$urlOptionsIhm}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_ANSWERBEFORENEXT}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_ANSWER_BEFORENEXT}>' >
                        <{$binOpt.flags.answerBeforeNext}>
                        </a>|
                   <a href="<{$urlOptionsIhm}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_ALLOWEDPREVIOUS}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_ALLOWED_PREVIOUS}>' >
                        <{$binOpt.flags.allowedPrevious}>
                        </a>|
                   <a href="<{$urlOptionsIhm}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_USETIMER}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_USE_TIMER}>' >
                        <{$binOpt.flags.useTimer}>
                        </a>|
                   <a href="<{$urlOptionsIhm}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_SHUFFLEQUESTIONS}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHUFFLE_QUESTION}>' >
                        <{$binOpt.flags.shuffleQuestions}>
                        </a>|
                   <a href="<{$urlOptionsIhm}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_SHOW_RESULTPOPUP}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_RESULT_POPUP}>' >
                        <{$binOpt.flags.showResultPopup}>
                        </a>|
                </td>
                
				<td class='right'><{$binOpt.optionsDev}>&nbsp;===></td>
				<td class='left'>
                   <{assign var="urlOptionsDev" value="options.php?op=set_bit&opt_id=`$binOpt.id`&field=opt_optionsDev"}>
                
                   <a href="<{$urlOptionsDev}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_SHOW_PLUGIN}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOW_PLUGIN}>' >
                        <{$binOpt.flags.showPlugin}>
                        </a>|
                   <a href="<{$urlOptionsDev}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_SHOW_RELOADANSWERS}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOW_RELOAD_ANSWERS}>' >
                        <{$binOpt.flags.showReloadAnswers}>
                        </a>|
                   <a href="<{$urlOptionsDev}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_SHOW_GOTOSLIDE}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOW_GOTO_SLIDE}>' >
                        <{$binOpt.flags.showGoToSlide}>
                        </a>|
                   <a href="<{$urlOptionsDev}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_SHOW_GOODANSWERS}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOW_GOOD_ANSWERS}>' >
                        <{$binOpt.flags.showGoodAnswers}>
                        </a>|
                   <a href="<{$urlOptionsDev}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_SHOW_BADANSWERS}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOW_BAD_ANSWERS}>' >
                        <{$binOpt.flags.showBadAnswers}>
                        </a>|
                   <a href="<{$urlOptionsDev}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_SHOW_LOG}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOW_LOG}>' >
                        <{$binOpt.flags.showLog}>
                        </a>|
                   <a href="<{$urlOptionsDev}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_SHOW_RESULTALLWAYS}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOW_RESULT_ALLWAYS}>' >
                        <{$binOpt.flags.showResultAllways}>
                        </a>|
                   <a href="<{$urlOptionsDev}>&bitIndex=<{$smarty.const.QUIZMAKER_BIT_SHOW_REPONSESBOTTOM}>"  title='<{$smarty.const._AM_QUIZMAKER_QUIZ_SHOW_REPONSES}>' >
                        <{$binOpt.flags.showReponsesBottom}>
                        </a>
                
                </td>

				<td class="center width5">
					<a href="options.php?op=edit&opt_id=<{$binOpt.id}>" title="<{$smarty.const._EDIT}>">
                        <img src="<{xoModuleIcons16}>/edit.png" alt="categories" />
                        </a>
				</td>
            
			</tr>
			<{/foreach}>
		</tbody>
		<{/if}>
	</table>
	<div class="clear">&nbsp;</div>

<{/if}>
<{if $form}>
	<{$form}>
<{/if}>
<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>
