<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>

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

	<{if count($block)}>
        <div class="item-round-all default-item-legend"><center><{$block.options.title}></center></div><br>
  
		<{foreach item=cat from=$block.data key=cat_Id}>    
          <div class="item-round-top <{$cat.cat.theme}>-itemHead">
    	       <center><b><{$cat.cat.name}></b></center>
          </div>
        
           <{* ========================================================== *}>  
          <div class="item-round-none <{$cat.cat.theme}>-itemBody">
          <table class='quizTbl' width='100%'>
    		<thead>
    			<tr class='head'>
    				<{* <th class="center">#</th> *}>
    				<{* <th class="center" colspan='2'><{$smarty.const._MB_QUIZMAKER_NAME}></th> *}>
    			</tr>
    		</thead>
    		<{foreach item=Quiz from=$cat.quiz}>
    		<tr class='<{cycle values="odd, even"}>'>
    			<{* <td class='center' width='80px'><{$Quiz.cat_id}>/<{$Quiz.id}></td> *}>
    			<td class='left'><{$Quiz.name}></td>
                
                
                
                
                <td class='center'>
                <{if $Quiz.periodeOK}>
                  <{if $Quiz.publishQuiz == 1}>
                      <a class='run_quiz' href="modules/quizmaker/quiz_display.php?op=run&quiz_id=<{$Quiz.id}>" >
                          <img src="<{$smarty.const.QUIZMAKER_URL_IMAGE}>/run_quiz_01.png" alt="quiz" title='' height='16px'/>
                          <img src="<{$smarty.const.QUIZMAKER_URL_IMAGE}>/run_quiz_00.png" alt="quiz" title='' height='16px'/>
                          <{$smarty.const._MB_QUIZMAKER_RUN_QUIZ}>
                      </a>
                  <{elseif $Quiz.publishQuiz == 2}>
                      <a class='run_quiz' href='<{$Quiz.quiz_html}>' title='' target='blank'>
                          <img src="<{$smarty.const.QUIZMAKER_URL_IMAGE}>/run_quiz_02.png" alt="quiz" title='' height='16px'/>
                          <img src="<{$smarty.const.QUIZMAKER_URL_IMAGE}>/run_quiz_00.png" alt="quiz" title='' height='16px'/>
                          <{$smarty.const._MB_QUIZMAKER_RUN_QUIZ}>
                      </a>
                  <{else}>
                  <{/if}>
                  </td>
                <{else}>
                    <{$smarty.const._MA_QUIZMAKER_CLOSED}>
                <{/if}>
    		</tr>
    		<{/foreach}>
          </table>
        
            </div>
            <{* <div class="itemRound-bottom <{$cat.cat.theme}>-itemLegend"><center>...</center></div> *}>
            <br>
    		<{/foreach}>
    
	</tbody>
	<{/if}>

