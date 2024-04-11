<{*
<{include file='db:quizmaker_header.tpl' }>
*}>

<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<{include file='db:quizmaker_admin_download.tpl' }>

<{if $form}>
	<{$form}>
<{/if}>

<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>

