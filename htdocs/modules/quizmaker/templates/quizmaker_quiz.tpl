<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>

<{include file='db:quizmaker_header.tpl' }>

<style>
   .run_quiz img:last-child {
	  display: none;  
	}
	.run_quiz:hover img:last-child {
	  display: inline;  
      margin-left:10px;
	}
	.run_quiz:hover img:first-child {
	  display: none;  
}
.quizTbl td{
    padding: 8px 0px 8px 0px;
}
</style>
<{* ************************************************ *}>
<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='quiz.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="" />

    <div class="item-round-top <{$catTheme}>-item-head"><center><{$smarty.const._MA_QUIZMAKER_SELECTION}></center></div>

    <div class="item-round-none <{$catTheme}>-item-body" style="padding:8px 0px 8px 0px;"><center>
<{if $smarty.const.QUIZMAKER_SELECTOR_DIFFICUT_MODE == 1}>
        <{$selectors.cat.select}>&nbsp;<{$selectors.subject.select}>&nbsp;<{$selectors.difficulty.select}>          
        <br><{$selectors.inpPlayer.select}>
<{else}>
        <{$selectors.cat.select}>&nbsp;<{$selectors.subject.select}>         
        <br><{$selectors.inpPlayer.select}>    
        <br><{$selectors.difficulty.select}>    
<{/if}> 
 
<{if $readMeLink}>
  <br><{$readMeLink}>  
<{/if}>  

    </center></div>

</form>
      
      <div class="item-round-none <{$catTheme}>-item-info" style="padding:10px 10px 10px 32px;">
        <img src='<{$smarty.const.QUIZMAKER_URL_UPLOAD}>/categories/<{$categorie.image}>' class='left' style='height:120px;' title='' alt=''>
        <{$categorie.description}>
      </div>
      <div class="item-round-none <{$catTheme}>-item-body">
      
        <{* **************** affichages des quiz ********************** *}>
      <table class='quizTbl'>
      
      <{foreach item=Quiz from=$quizArr name=quizItem}>
          <{if $Quiz.quiz_html <> '' }>
          
          <{if !$smarty.foreach.quizItem.first}>
              <td class='center' width="100%" style='height:10px' colspan='4'><hr class='<{$catTheme}>-hr-style-one' style='margin-top:0px;margin-bottom:0px;'></td>
          <{/if}>
          <{include file='db:quizmaker_quiz_item.tpl'}>
          <{/if}>
          
      <{/foreach}>
      </table>
      </div>
      <div class="item-round-bottom <{$catTheme}>-item-legend"><center>...</center></div><br>
  
<hr>
<{include file='db:quizmaker_footer.tpl' }>
