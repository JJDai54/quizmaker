<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='type_question.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="type_question_list" />
<input type="hidden" name="quest_parent_id" value="0" />
<input type="hidden" name="quest_type_question" value="" />
    <div class="floatleft xo-buttons">

<{$smarty.const._AM_QUIZMAKER_CATEGORIES}> : <{$inpCategory}>
<{$smarty.const._AM_QUIZMAKER_QUIZ}> : <{$inpQuiz}>
<{$smarty.const._AM_QUIZMAKER_TYPE_QUESTION_CATEGORY}> : <{$inpCatTQ}>

         <{* <{$btnGoToQuestion}> *}>
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
				<th class="center"><{$smarty.const._AM_QUIZMAKER_WEIGHT}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_ACTIONS}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_IMAGE}></th>

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
				<td class='left width50'><{$TypeDeQuestion.description}></td>
				<td class='center'><{$TypeDeQuestion.weight}></td>
				<td class='center'>
                    <{if $TypeDeQuestion.canDelete}>
                      <a  onclick="addNewQuestion('<{$TypeDeQuestion.type}>');">
          				<img src="<{xoModuleIcons32}>add.png" alt="Add Question" title='<{$smarty.const._AM_QUIZMAKER_ADD_NEW_QUESTION}>' />
                      </a>
                    <{/if}>
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
tth_trierTableau('quiz_type_question_list', 5, "1,2,3,4,5");  
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
