<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>

            <td width="30%">
                  <{if $Quiz.publishQuiz == 1}>
                      <a class='run_quiz' href="quiz_display.php?op=run&quiz_id=<{$Quiz.id}>" >
                        <{$Quiz.name}>
                      </a>
                  <{elseif $Quiz.publishQuiz == 2}>
                      <a class='run_quiz' href='<{$Quiz.quiz_html}>?<{$getForQuiz}>' title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' target='blank'>
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
            <td class='left' width="100px">
            
                <{if $Quiz.periodeOK}>
                  <{if $Quiz.publishQuiz == 1}>
                      <a class='run_quiz' href="quiz_display.php?op=run&quiz_id=<{$Quiz.id}>&cat_id=<{$Quiz.cat_id}>&player_id=<{$player_id}>" >
                          <img src="<{$smarty.const.QUIZMAKER_URL_IMAGE}>/run_quiz_01.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' height='16px'/>
                          <img src="<{$smarty.const.QUIZMAKER_URL_IMAGE}>/run_quiz_00.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' height='16px'/>
                          <{$smarty.const._MA_QUIZMAKER_RUN}>
                      </a>
                  <{elseif $Quiz.publishQuiz == 2}>
                      <a class='run_quiz' href='<{$Quiz.quiz_html}>' title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' target='blank'>
                          <img src="<{$smarty.const.QUIZMAKER_URL_IMAGE}>/run_quiz_02.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' height='16px'/>
                          <img src="<{$smarty.const.QUIZMAKER_URL_IMAGE}>/run_quiz_00.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' height='16px'/>
                          <{$smarty.const._MA_QUIZMAKER_RUN}>
                      </a>
                  <{else}>
                  <{/if}>
                <{else}>
                    <{$smarty.const._MA_QUIZMAKER_CLOSED}>
                <{/if}>
            <br>
                <{if $Quiz.publishResultsOk}>
                  <a class='run_quiz' href="results.php?op=list&cat_id=<{$Quiz.cat_id}>&player_id=<{$player_id}>&quiz_id=<{$Quiz.id}>&sender=quiz_id" >
                      <img src="<{$smarty.const.QUIZMAKER_URL_ICONS}>/16/sigma-01.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RESULTS}>' height='16px'/>
                      <img src="<{$smarty.const.QUIZMAKER_URL_ICONS}>/16/sigma-02.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_RESULTS}>' height='16px'/>
                      <{$smarty.const._MA_QUIZMAKER_SCORES}>
                  </a>
                <{else}>
                <{/if}>
            <br>
                <{if $Quiz.publishAnswersOk}>
                    <a class='run_quiz' href='solutions.php?quiz_id=<{$Quiz.id}>&cat_id=<{$Quiz.cat_id}>&player_id=<{$player_id}>' title='<{$smarty.const._MA_QUIZMAKER_RUN_QUIZ}>' target='blank'>
                        <img src="<{$smarty.const.QUIZMAKER_URL_IMAGE}>/solution-01.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_SOLUTIONS}>' height='16px'/>
                        <img src="<{$smarty.const.QUIZMAKER_URL_IMAGE}>/solution-02.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_SOLUTIONS}>' height='16px'/>
                        <{$smarty.const._MA_QUIZMAKER_SOLUTIONS}>
                        
                    </a>
                <{else}>
                        <img src="<{$smarty.const.QUIZMAKER_URL_IMAGE}>/solution-00.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_SOLUTIONS}>' height='16px'/>
                        <{$smarty.const._MA_QUIZMAKER_SOLUTIONS}>
                <{/if}>
            <br>
                <{if $isAdmin}>
                    <a class='run_quiz' href='admin/questions.php?op=list&sender=&cat_id=<{$Quiz.cat_id}>&quiz_id=<{$Quiz.id}> ' title='<{$smarty.const._MA_QUIZMAKER_ADMIN}>' target='blank'>
                        <img src="<{$smarty.const.QUIZMAKER_URL_IMAGE}>/admin-01.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_ADMIN}>' height='16px'/>
                        <img src="<{$smarty.const.QUIZMAKER_URL_IMAGE}>/admin-01.png" alt="quiz" title='<{$smarty.const._MA_QUIZMAKER_ADMIN}>' height='16px'/>
                        <{$smarty.const._MA_QUIZMAKER_ADMIN}>
              
                    </a>
                <{/if}>
            </td>

