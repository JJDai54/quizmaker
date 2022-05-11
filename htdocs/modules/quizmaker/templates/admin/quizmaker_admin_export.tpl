<{*
<{include file='db:quizmaker_header.tpl' }>
*}>

<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<{if $download==1}>
<div id='quiz_download' name ='quiz_download' style='background:red;color:white;'>
<p><h1><{$smarty.const._AM_QUIZMAKER_DOWNLOAD_OK}>
<a data-auto-download href="<{$href}>"><{$name}></a></h1></p>
<script>
$(function() {
  $('a[data-auto-download]').each(function(){
    var $this = $(this);
    setTimeout(function() {
      window.location = $this.attr('href');
    }, <{$delai}>);
  });
});

$(document).ready(function(){     

        $("#quiz_download").delay(8000).hide(2000, "linear", function(){
            //alert("Titre bien caché");
        });
    });
</script>

</div>
<{/if}>





<{if $form}>
	<{$form}>
<{/if}>





<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>






<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>

