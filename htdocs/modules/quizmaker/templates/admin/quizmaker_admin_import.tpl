<{*
<{include file='db:quizmaker_header.tpl' }>
*}>

<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<{if $form}>
	<{$form}>
<{/if}>

<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>

