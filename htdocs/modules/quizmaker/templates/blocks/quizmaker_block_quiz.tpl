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
    padding: 8px 0px 8px 8px;
    border:none;
}
</style>

	<{if count($block)}>
        <div class="item-round-top <{$block.options.theme}>-item-head"><center><b>
        <a href='modules/quizmaker/categories.php'><{$block.options.title}></a>
        </b></center></div>
        
        <div class="item-round-none <{$block.options.theme}>-item-body"><center><{$block.options.desc}></center></div>
  
		<{foreach item=cat from=$block.data key=cat_Id}>    
          <div class="item-round-none <{$cat.cat.theme}>-item-head">
    	       <center><b>
               <a href='modules/quizmaker/categories.php?cat_id=<{$cat.cat.id}>' title=''><{$cat.cat.name}></a>
               </b></center>
          </div>

           <{* ========================================================== *}>  
          <div class="item-round-none <{$cat.cat.theme}>-item-body">
          <table class='quizTbl' width='100%' style='border:none;'>
    		<thead>
    			<tr class='head'>
    				<{* <th class="center">#</th> *}>
    				<{* <th class="center" colspan='2'><{$smarty.const._MB_QUIZMAKER_NAME}></th> *}>
    			</tr>
    		</thead>
	<tbody>
    		<{foreach item=Quiz from=$cat.quiz}>
    		<tr class='<{cycle values="odd, even"}>'>
    			<{* <td class='center' width='80px'><{$Quiz.cat_id}>/<{$Quiz.id}></td> *}>
                
                
                <td class='left'>
                <{if $Quiz.periodeOK}>
                  <{if $Quiz.publishQuiz == 1}>
                      <a class='run_quiz' href="modules/quizmaker/quiz_display.php?op=run&quiz_id=<{$Quiz.id}>" title='<{$smarty.const._MB_QUIZMAKER_RUN_QUIZ}>'>
                          <img src="<{$smarty.const.QUIZMAKER_IMAGE_URL}>/run_quiz_01.png" alt="quiz" title='' height='16px'/>
                          <img src="<{$smarty.const.QUIZMAKER_IMAGE_URL}>/run_quiz_00.png" alt="quiz" title='' height='16px'/>
                          <{$Quiz.name}>
                      </a>
                  <{elseif $Quiz.publishQuiz == 2}>
                      <a class='run_quiz' href='<{$smarty.const.QUIZMAKER_UPLOAD_QUIZ_URL}>/<{$Quiz.folderJS}>/index.html' title='<{$smarty.const._MB_QUIZMAKER_RUN_QUIZ}>' target='blank'>
                          <img src="<{$smarty.const.QUIZMAKER_IMAGE_URL}>/run_quiz_02.png" alt="quiz" title='' height='16px'/>
                          <img src="<{$smarty.const.QUIZMAKER_IMAGE_URL}>/run_quiz_00.png" alt="quiz" title='' height='16px'/>
                          <{$Quiz.name}>
                      </a>
                  <{else}>
                  <{/if}>
                  </td>
                <{else}>
                    <{$smarty.const._MA_QUIZMAKER_CLOSED}>
                <{/if}>
    		</tr>
    		<{/foreach}>
	</tbody>
          </table>
        
            </div>
            <{* <div class="item-round-bottom <{$cat.cat.theme}>-item-legend"><center>...</center></div> *}>

    		<{/foreach}>
    
            <div class="item-round-bottom <{$cat.cat.theme}>-item-legend"><center>...</center></div>
	<{/if}>

