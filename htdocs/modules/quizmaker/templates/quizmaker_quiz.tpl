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

<{*
<{if $categoriesCount > 1}>
    <{include file='db:quizmaker_categories_theme.tpl' }>
<{/if}>
*}>
<{* ************************************************ *}>
<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='quiz.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="" />

    <div class="item-round-top <{$catTheme}>-item-head"><center><{$smarty.const._MA_QUIZMAKER_SELECTION}></center></div>
    <div class="item-round-none <{$catTheme}>-item-body" style="padding:8px 0px 8px 0px;"><center>
                <{$selector.inpCategory}><br><{$selector.inpPlayer}>    
    
    
    
 <{*
    <table width='80%'>
        <tr>
            <td class="right" style='padding:5px;'><{$smarty.const._MA_QUIZMAKER_CATEGORIES}> : </td>
            <td class="left" style='padding:5px;'>
                <{$selector.inpCategory}><br><{$selector.inpPlayer}>
            </td>
        </tr>
        
        <tr>
            <td class="right" style='padding:5px;'><{$smarty.const._CO_QUIZMAKER_PLAYER_STATUS}> : </td>
            <td class="left" style='padding:5px;'><{$selector.inpPlayer}></td>
        </tr>
        
    </table>
 *}>
      
      
    </center></div>
    <div class="item-round-bottom <{$catTheme}>-item-legend" style='margin-top:0px;'><center>...</center></div><br>
</form>

<{* ************************************************ *}>
  <{foreach item=cat from=$categories }>
    <{if $cat.quiz}>
      <div class="item-round-top <{$cat.theme}>-item-head"><center><{$cat.name}></center></div>
      
      <div class="item-round-top <{$cat.theme}>-item-info" style="padding:10px 10px 10px 32px;">
        <img src='<{$smarty.const.QUIZMAKER_URL_UPLOAD}>/categories/<{$cat.image}>' class='left' style='height:120px;' title='' alt=''>
        <{$cat.description}>
      </div>
<{* plus utile avec les boutons plus explicites
      <div class="item-round-none <{$cat.theme}>-item-legend" style="padding:10px 10px 10px 32px;">
        <{$smarty.const._MA_QUIZMAKER_HOW_TO_RUN_QUIZ}><br>
        <{$smarty.const._MA_QUIZMAKER_HOW_TO_SHOW_RESULTS}><br>
        <{$smarty.const._MA_QUIZMAKER_HOW_TO_SHOW_SOLUTIONS}>
       </div>
*}>      

      

      <div class="item-round-none <{$cat.theme}>-item-body">
      <table class='quizTbl'>
      <{*
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_NAME}></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_QUESTIONS}></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_SCORES}></th>
			</tr>
		</thead>
      *}>
      
      <{foreach item=Quiz from=$cat.quiz name=quizItem}>
          <{if $Quiz.quiz_html <> '' }>
          
          <{if !$smarty.foreach.quizItem.first}>
              <td class='center' width="100%" style='height:10px' colspan='4'><hr class='<{$cat.theme}>-hr-style-one' style='margin-top:0px;margin-bottom:0px;'></td>
          <{/if}>
          <{include file='db:quizmaker_quiz_item.tpl'}>
          <{/if}>
          
      <{/foreach}>
      </table>
      </div>
      <div class="item-round-bottom <{$cat.theme}>-item-legend"><center>...</center></div><br>
    <{/if}>
  <{/foreach}>
  
<hr>
<{include file='db:quizmaker_footer.tpl' }>
