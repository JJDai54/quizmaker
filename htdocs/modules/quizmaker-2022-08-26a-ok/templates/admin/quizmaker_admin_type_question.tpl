<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='questions.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="new_question" />
<input type="hidden" name="sender" value="type_question_list" />
<input type="hidden" name="quest_parent_id" value="0" />
<input type="hidden" name="quest_type_question" value="" />
    <div class="floatleft xo-buttons">

<{$smarty.const._AM_QUIZMAKER_CATEGORIES}> : <{$inpCategory}>
<{$smarty.const._AM_QUIZMAKER_QUIZ}> : <{$inpQuiz}>

         <{$btnGoToQuestion}>
    </div>


</form>

<script>
function addNewQuestion(typeQuestion){
//alert("addNewQuestion : " + typeQuestion);
    document.quizmaker_select_filter.quest_type_question.value = typeQuestion;
    document.quizmaker_select_filter.submit();

    return true;
}
</script>

<{if $type_question_list}>
	<table id='quiz_type_question_list' name='quiz_type_question_list' class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_TYPE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_NAME}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_SHORTDESC}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_IMAGE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ACTIONS}></th>

			</tr>
		</thead>
		<{if $type_question_count}>
		<tbody><br>      
            <{assign var="index" value="0"}>       
			<{foreach item=TypeDeQuestion from=$type_question_list}>
             <{assign var=index value=$index+1}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class='center'><{$index}></td>
				<td class='left'><{$TypeDeQuestion.type}></td>
				<td class='left'><{$TypeDeQuestion.name}></td>
				<td class='left'><{$TypeDeQuestion.description}></td>
				<td class='left'>
                    <{*
                    <a href="type_question.php?op=add_new_question&type_question=<{$TypeDeQuestion.type}>&quiz_id=<{$Questions.quiz_id}>" >
                    *}>
                    <a  onclick="addNewQuestion('<{$TypeDeQuestion.type}>');">
        				<img src="<{xoModuleIcons32}>add.png" alt="Add Question" title='<{$smarty.const._AM_QUIZMAKER_ADD_NEW_QUESTION}>' />
                    </a>
                <td class='center'>
                    <div class='highslide-gallery'>
                        <a href='<{$TypeDeQuestion.image_fullName}>' class='highslide' onclick='return hs.expand(this);' >
                            <img src="<{$TypeDeQuestion.image_fullName}>" alt="slides" style="max-width:100px" />
                        </a>
                        <div class='highslide-heading'></div>
                    </div>
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

<script>
tth_set_value('last_asc', true);
tth_trierTableau('quiz_type_question_list', 2, "1,2,3,4");  
</script>

<{if $form}>
	<{$form}>
<{/if}>
<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>
