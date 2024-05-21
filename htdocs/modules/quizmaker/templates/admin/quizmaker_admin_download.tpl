
<{if $download == 1}>
<style>
#quiz_download {
    padding: 8px;
    background-color: red;
    color: white;
    -moz-border-radius: 8px 8px 8px 8px;
    -webkit-border-radius: 8px 8px 8px 8px;
    -khtml-border-radius: 8px 8px 8px 8px;
    border-radius: 8px 8px 8px 8px;
    text-align:center;
    height:32px;
    display:none;
    font-size: 18px;
}
#quiz_download a{
    color: yellow;
    font-size: 18px;
}
</style>

<div id='quiz_download' name ='quiz_download' >
   <{$smarty.const._AM_QUIZMAKER_DOWNLOAD_OK}>
        <a data-auto-download href="<{$href}>"><{$name}></a>
    

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
        //$("#quiz_download").delay(0).hide(0, "linear");
        $("#quiz_download").delay(1000).show(2000, "linear");

        $("#quiz_download").delay(8000).hide(2000, "linear", function(){
            //alert("Titre bien caché");
        });
    });
</script>

</div>
<{/if}>


