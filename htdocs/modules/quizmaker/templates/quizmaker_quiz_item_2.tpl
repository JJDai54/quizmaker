<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>
<{assign var='iconHeight' value='16px'}>
            <td width="20%">
                <{if $Quiz.image}>
                <img src="<{$smarty.const.QUIZMAKER_URL_UPLOAD_QUIZ}>/<{$Quiz.folderJS}>/images/<{$Quiz.image}>" height='80px'>
                <{/if}>
            </td>
            <td width="30%">
                  <{if $Quiz.publishQuiz == 1}>
                      <a class='quiz_title' href="<{$smarty.const.QUIZMAKER_DISPLAY_QUIZ}>?op=run&quiz_id=<{$Quiz.id}>" >
                        <{$Quiz.name}>
                      </a>
                  <{elseif $Quiz.publishQuiz == 2}>
                      <a class='quiz_title' href='<{$Quiz.quiz_html}>?<{$getForQuiz}>' title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' target='blank'>
                        <{$Quiz.name}>
                      </a>
                  <{else}>
                        <{$Quiz.name}>
                  <{/if}>
                  <{if $Quiz.author <> ''}><br><{$smarty.const._MA_QUIZMAKER_QUIZ_PROPOSED_BY}> : <{$Quiz.author}><{/if}>
            </td>
            <td class='center' width="20px">
                <{$Quiz.countQuestions}>  
            </td>
            <{* =========================================================== *}>
            <{if $Quiz.statOk}>
                <td class='left' width="100px">
                    <{$smarty.const._MA_QUIZMAKER_SCORE}> : <{$Quiz.stat.bestScore}> / <{$Quiz.stat.scoreMax}>
                <br>
                    <{$smarty.const._MA_QUIZMAKER_AVERAGE}> : <{$Quiz.stat.avgScore}>  
                <br>
                    <{$smarty.const._MA_QUIZMAKER_PARTICIPATION}> : <{$Quiz.stat.countResults}>  
                </td>
            <{else}>
                <td class='center' width="250px">
                    <{$smarty.const._MA_QUIZMAKER_NO_SCORE}>
                </td>
            <{/if}> 
            <{* =========================================================== *}>
            </tr>
            <tr>
            
            
            <td class='left' >
             </td'>
            <td class='right' width="100%" colspan='3'>
           
                <{if $Quiz.periodeOK}>
                  <{if $Quiz.publishQuiz == 1}>
                      <a class='quiz_button quiz_button_run'  href="<{$smarty.const.QUIZMAKER_DISPLAY_QUIZ}>?op=run&quiz_id=<{$Quiz.id}>&cat_id=<{$Quiz.cat_id}>&player_id=<{$playerId}>" >
                          <img src="<{$modUrlImages}>/run_quiz_01.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' title='<{$smarty.const._MA_QUIZMAKER_RUN}>' height='<{$iconHeight}>'/>
                          <img src="<{$modUrlImages}>/run_quiz_00.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' title='<{$smarty.const._MA_QUIZMAKER_RUN}>' height='<{$iconHeight}>'/>
                          <{$smarty.const._MA_QUIZMAKER_RUN}>
                      </a>
                  <{elseif $Quiz.publishQuiz == 2}>
                      <a class='quiz_button quiz_button_run' href='<{$Quiz.quiz_html}>' title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' target='blank'>
                          <img src="<{$modUrlImages}>/run_quiz_02.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' title='<{$smarty.const._MA_QUIZMAKER_RUN}>' height='<{$iconHeight}>'/>
                          <img src="<{$modUrlImages}>/run_quiz_00.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' title='<{$smarty.const._MA_QUIZMAKER_RUN}>' height='<{$iconHeight}>'/>
                          <{$smarty.const._MA_QUIZMAKER_RUN}>
                      </a>
                  <{else}>
                      <span class='quiz_button quiz_button_disabled'>
                          <img src="<{$modUrlImages}>/run_quiz_03.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' title='<{$smarty.const._MA_QUIZMAKER_RUN}>' height='<{$iconHeight}>'/>
                          <img src="<{$modUrlImages}>/run_quiz_03.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' title='<{$smarty.const._MA_QUIZMAKER_RUN}>' height='<{$iconHeight}>'/>
                          <{$smarty.const._MA_QUIZMAKER_CLOSED}>
                      </span>
                  <{/if}>
                <{else}>
                      <span class='quiz_button quiz_button_disabled'>
                          <img src="<{$modUrlImages}>/run_quiz_03.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' title='<{$smarty.const._MA_QUIZMAKER_RUN}>' height='<{$iconHeight}>'/>
                          <img src="<{$modUrlImages}>/run_quiz_03.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' title='<{$smarty.const._MA_QUIZMAKER_RUN}>' height='<{$iconHeight}>'/>
                          <{$smarty.const._MA_QUIZMAKER_CLOSED}>
                      </span>
                <{/if}>

                <{if $Quiz.publishResultsOk}>
                  <a class='quiz_button quiz_button_scores' href="results.php?op=list&cat_id=<{$Quiz.cat_id}>&player_id=<{$player_id}>&quiz_id=<{$Quiz.id}>&sender=quiz_id" >
                      <img src="<{$modUrlIcon16}>/sigma-01.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RESULTS}>' title='<{$smarty.const._MA_QUIZMAKER_SCORES}>' nheight='<{$iconHeight}>'/>
                      <img src="<{$modUrlIcon16}>/sigma-02.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RESULTS}>' title='<{$smarty.const._MA_QUIZMAKER_SCORES}>' height='<{$iconHeight}>'/>
                      <{$smarty.const._MA_QUIZMAKER_SCORES}>
                  </a>
                <{else}>
                      <span class='quiz_button quiz_button_disabled'>
                          <img src="<{$modUrlImages}>/sigma-03.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' title='<{$smarty.const._MA_QUIZMAKER_RUN}>' height='<{$iconHeight}>'/>
                          <img src="<{$modUrlImages}>/sigma-03.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' title='<{$smarty.const._MA_QUIZMAKER_RUN}>' height='<{$iconHeight}>'/>

                          <{$smarty.const._MA_QUIZMAKER_SCORES}>
                      </span>
                <{/if}>

                <{if $Quiz.publishAnswersOk}>
                    <a class='quiz_button quiz_button_solutions' href='solutions.php?quiz_id=<{$Quiz.id}>&cat_id=<{$Quiz.cat_id}>&player_id=<{$player_id}>' title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' target='blank'>
                        <img src="<{$modUrlImages}>/solution-01.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_SOLUTIONS}>' height='<{$iconHeight}>'/>
                        <img src="<{$modUrlImages}>/solution-02.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_SOLUTIONS}>' height='<{$iconHeight}>'/>
                        <{$smarty.const._MA_QUIZMAKER_SOLUTIONS}>
                        
                    </a>
                <{else}>
                      <span class='quiz_button quiz_button_disabled'>
                          <img src="<{$modUrlImages}>/solution-00.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' title='<{$smarty.const._MA_QUIZMAKER_RUN}>' height='<{$iconHeight}>'/>
                          <img src="<{$modUrlImages}>/solution-00.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' title='<{$smarty.const._MA_QUIZMAKER_RUN}>' height='<{$iconHeight}>'/>
                          <{$smarty.const._MA_QUIZMAKER_SOLUTIONS}>

                      </span>
                <{/if}>

                <{if $isAdmin}>
                    <a class='quiz_button quiz_button_admin' href='admin/questions.php?op=list&sender=&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}> ' title='<{$smarty.const._MA_QUIZMAKER_ADMIN}>' target='blank'>
                        <img src="<{$modUrlImages}>/admin-01.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_ADMIN}>' height='<{$iconHeight}>'/>
                        <img src="<{$modUrlImages}>/admin-01.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_ADMIN}>' height='<{$iconHeight}>'/>
                        <{$smarty.const._MA_QUIZMAKER_ADMIN}>
              
                    </a>
                <{/if}>
            </td>

