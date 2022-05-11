<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='questions.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="0" />
<input type="hidden" name="quest_parent_id" value="0" />

<{$smarty.const._AM_QUIZMAKER_CATEGORIES}> : <{$inpCategory}>
<{$smarty.const._AM_QUIZMAKER_QUIZ}> : <{$inpQuiz}>

</form>

<{if $form}>
	<{$form}>
<{/if}>

<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>
