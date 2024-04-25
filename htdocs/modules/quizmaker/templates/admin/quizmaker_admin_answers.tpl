<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<{if !$form}>

<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='answers.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="0" />


<div class="floatleft">
<{$smarty.const._AM_QUIZMAKER_CATEGORIES}> : <{$inpCategory}>
<{$smarty.const._AM_QUIZMAKER_QUIZ}> : <{$inpQuiz}>
<{$smarty.const._AM_QUIZMAKER_QUESTION}> : <{$inpQuest}>
</div>

<{* 
<div class="floatleft">
    <div class="xo-buttons">
        <{$btnNewAnswer}>
    </div>
</div>
<div class="floatright">
    <div class="xo-buttons">
        <{$initWeight}>
    </div>
</div>
*}> 

</form>


<{if $answersCount > 0}>
	<table class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ANSWERS_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ANSWERS_QUESTION_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ANSWERS_PROPOSITION}></th>
				<th class="center"><{$smarty.const._CO_QUIZMAKER_GROUP}></th>
				<th class="center"><{$smarty.const._CO_QUIZMAKER_POINTS}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_CAPTION}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_IMAGE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_COLOR}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_BACKGROUND}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_WEIGHT}></th>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_ACTION}></th>
			</tr>
		</thead>
		<{if $answers_count}>
		<tbody>
            <{assign var="fldImg" value="blue"}>
			<{foreach item=Answers from=$answers_list name=ans}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class='center'><{$Answers.id}></td>
				<td class='center'><{$Answers.quest_id}></td>
                
				<td class='left'>
					<a href="answers.php?op=edit&amp;answer_id=<{$Answers.id}>" title="<{$smarty.const._EDIT}>">
                    <{$Answers.proposition}></a></td>
                    
				<td class='center'>
                    <{$Answers.group}></td>
				
				<td class='center'>
                    <{$Answers.points}></td>
                    
                <td class='left'>    
                    <{$Answers.caption}></td>
                    
                <td class='left'>    
                    <{$Answers.image1}></td>
                    
                <td class='center width10'>
                    <{$Answers.color}></td>
                <td class='center width10'>
                    <{$Answers.background}></td>
                
                <{* ---------------- Arrows -------------------- *}>
                <td class='center width10' >
                    <{if $smarty.foreach.ans.first}>
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/first-0.png" title="<{$smarty.const._AM_QUIZMAKER_FIRST}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/up-0.png" title="<{$smarty.const._AM_QUIZMAKER_UP}>">
                    <{else}>
                      <a href="answers.php?op=weight&answer_id=<{$Answers.id}>&sens=first&quest_id=<{$Answers.quest_id}>&answer_weight=<{$Answers.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/first-1.png" title="<{$smarty.const._AM_QUIZMAKER_FIRST}>">
                      </a>
                    
                      <a href="answers.php?op=weight&answer_id=<{$Answers.id}>&sens=up&quest_id=<{$Answers.quest_id}>&answer_weight=<{$Answers.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/up-1.png" title="<{$smarty.const._AM_QUIZMAKER_UP}>">
                      </a>
                    <{/if}>
                 
                    <img src="<{$modPathIcon16}>/blank-08.png" title="">
                    <{$Answers.weight}>
                    <img src="<{$modPathIcon16}>/blank-08.png" title="">
                 
                    <{if $smarty.foreach.ans.last}>
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/down-0.png" title="<{$smarty.const._AM_QUIZMAKER_DOWN}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/last-0.png" title="<{$smarty.const._AM_QUIZMAKER_LAST}>">
                    <{else}>
                    
                    <a href="answers.php?op=weight&answer_id=<{$Answers.id}>&sens=down&quest_id=<{$Answers.quest_id}>&answer_weight=<{$Answers.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/down-1.png" title="<{$smarty.const._AM_QUIZMAKER_DOWN}>">
                      </a>
                 
                    <a href="answers.php?op=weight&answer_id=<{$Answers.id}>&sens=last&quest_id=<{$Answers.quest_id}>&answer_weight=<{$Answers.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/last-1.png" title="<{$smarty.const._AM_QUIZMAKER_LAST}>">
                      </a>
                    <{/if}>
                <{* ---------------- /Arrows -------------------- *}>
                </td>
                
                
				<td class="center  width5">
					<a href="answers.php?op=edit&amp;answer_id=<{$Answers.id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16}>/edit.png" alt="answers" /></a>
					<a href="answers.php?op=delete&amp;answer_id=<{$Answers.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16}>/delete.png" alt="answers" /></a>
				</td>
			</tr>
			<{/foreach}>
		</tbody>
		<{/if}>
	</table>
	<div class="clear">&nbsp;</div>
	<{* <{if $pagenav}> 
		<div class="xo-pagenav floatright"><{$pagenav}></div>
		<div class="clear spacer"></div>
	<{/if}>
    *}>
<{/if}>
<{/if}>


<{if $form}>
	<{$form}>
<{/if}>
<{* 
*}> 
<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>
