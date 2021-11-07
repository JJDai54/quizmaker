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
</style>


<{include file='db:quizmaker_categories_theme.tpl' }>

  <{foreach item=cat from=$categories}>
    <{if $cat.quiz}>
      <div class="itemRound-top <{$cat.theme}>-itemHead"><center><{$cat.name}>
      </center></div>
      <div class="itemRound-none <{$cat.theme}>-itemInfo" style="padding:10px 10px 10px 10px;">
        <{$smarty.const._MA_QUIZMAKER_HOW_TO_RUN_QUIZ}><br>
        <{$smarty.const._MA_QUIZMAKER_HOW_TO_SHOW_SOLUTIONS}>
       </div>

      

      <div class="itemRound-none <{$cat.theme}>-itemBody">
      <table width="90%" style='margin:0px 50px 0px 50px;'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_NAME}></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_NB_QUESTIONS}></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_SCORE}></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_AVERAGE}></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_PARTICIPATION}></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_RESULTS}></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_EXECUTION}></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_SOLUTIONS}></th>
			</tr>
		</thead>
      
      <{foreach item=Quiz from=$cat.quiz}>
          <{if $Quiz.quiz_html <> '' }>
          <tr>
            <td width="30%">
                <{$Quiz.name}>  
            </td>
            <td class='center' width="20px">
                <{$Quiz.stat.countQuestions}>  
            </td>
            
            <{if $Quiz.stat.statOk}>
                <td class='center' width="20px">
                    <{$Quiz.stat.bestScore}> / <{$Quiz.stat.scoreMax}>
                </td>
                <td class='center' width="20px">
                    <{$Quiz.stat.avgScore}>  
                </td>
                <td class='center' width="20px">
                    <{$Quiz.stat.countResults}>  
                </td>
                <td class='center' width="20px">
                    <a href="results.php?op=list&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}>&sender=quiz_id" >
                        <img src="<{$smarty.const.QUIZMAKER_ICONS_URL}>/16/sigma-01.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RESULTS}>' height='16px'/>
                    </a>
                </td>
            <{else}>
                <td class='center' width="20px" colspan='4'>
                    <{$smarty.const._MA_QUIZMAKER_NO_SCORE}>
                </td>
            <{/if}> 
            
            
            <td class='center' width="50px">
                <{if $Quiz.periodeOK}>
                <{if $Quiz.execution == 1}>
                    <a class='run_quiz' href="quiz_display.php?op=run&quiz_id=<{$Quiz.id}>" >
                        <img src="<{$smarty.const.QUIZMAKER_IMAGE_URL}>/run_quiz_01.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' height='16px'/>
                        <img src="<{$smarty.const.QUIZMAKER_IMAGE_URL}>/run_quiz_00.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' height='16px'/>

                    </a>
                <{else}>
                    <a class='run_quiz' href='<{$Quiz.quiz_html}>' title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' target='blank'>
                        <img src="<{$smarty.const.QUIZMAKER_IMAGE_URL}>/run_quiz_02.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' height='16px'/>
                        <img src="<{$smarty.const.QUIZMAKER_IMAGE_URL}>/run_quiz_00.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' height='16px'/>
                    </a>
                <{/if}>
                <{else}>
                    <{$smarty.const._MA_QUIZMAKER_CLOSED}>
                <{/if}>
            </td>
            <td class='center' width="50px">
                <{if $Quiz.showSolutions}>
                    <a class='run_quiz' href='solutions.php?quiz_id=<{$Quiz.id}>' title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' target='blank'>
                        <img src="<{$smarty.const.QUIZMAKER_IMAGE_URL}>/solution-01.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' height='16px'/>
                        <img src="<{$smarty.const.QUIZMAKER_IMAGE_URL}>/solution-02.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' height='16px'/>
                    </a>
                <{else}>
                        <img src="<{$smarty.const.QUIZMAKER_IMAGE_URL}>/solution-00.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' height='16px'/>
                <{/if}>
            </td>

          </tr>
          <{/if}>
          
      <{/foreach}>
      </table>
      </div>
      <div class="itemRound-bottom <{$cat.theme}>-itemLegend"><center>...</center></div><br>
    <{/if}>
  <{/foreach}>
  
<hr>
<{include file='db:quizmaker_footer.tpl' }>
