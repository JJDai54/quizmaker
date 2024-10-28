<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>

<{include file='db:quizmaker_header.tpl' }>

<style>
.sommaire {
    position:absolute;
    right:30px;

    }
    
.solutions {
  border: solid 0px black;
  margin:0px 20px 0px 20px;
  width:90%;
}
.solutions td{
  border: solid 0px black;
}


</style>

<{if $result}>

    <{assign var="themeResult" value="red"}> <{* $quiz.theme_ok *}>
    <div class="item-round-top  <{$themeResult}>-item-head"><center><b><{$smarty.const._MA_QUIZMAKER_RESULTS_FOR}><{$result.result_uname}></b></center></div>
    <div class="item-round-none <{$themeResult}>-item-body" style="padding:20px 50px 20px 50px;">
    

      <table class='quizTbl'>
        <tr>
            <td><{$smarty.const._MA_QUIZMAKER_UNAME}></td>
            <td><{$result.result_uname}></td>
        </tr>
        <tr>
            <td><{$smarty.const._MA_QUIZMAKER_RESULTS_SCORE}></td>
            <td><{$result.score_achieved}> <{$smarty.const._MA_QUIZMAKER_ANSWERS_POINTS}> / <{$result.score_max}> <{$smarty.const._MA_QUIZMAKER_OF_TOTAL}></td>
        </tr>
        <tr>
            <td><{$smarty.const._MA_QUIZMAKER_ANSWERS_AT}></td>
            <td><{$result.answers_achieved}> <{$smarty.const._MA_QUIZMAKER_QUESTIONS}> / <{$result.answers_total}> <{$smarty.const._MA_QUIZMAKER_OF_TOTAL}></td>
        </tr>
        <tr>
            <td><{$smarty.const._MA_QUIZMAKER_RESULTS_DURATION}></td>
            <td><{$result.duration2}></td>
        </tr>
        <tr>
            <td><{$smarty.const._MA_QUIZMAKER_RESULTS_NOTE}></td>
            <td><{$result.note}> / 100</td>
        </tr>
      </table>
    </div>
    <div class="item-round-bottom <{$themeResult}>-item-legend"><center><b><{$smarty.const._MA_QUIZMAKER_THANKS_FOR_PARTICIPATION}></b></center></div><br>



<{*
<hr>result_id = <{$result.result_id }> (affichage du score En cours de développement)<hr>
 [result_id] => 11
    [result_quiz_id] => 5
    [result_uid] => 2
    [] => jjd
    [result_ip] => 2a01:cb04:285:5b00:d999:8351:d01:17a8
    [] => 0
    [] => 49
    [result_score_min] => 0
    [] => 10
    [] => 0
    [] => 5
    [result_note] => 0
    [result_creation] => 2022-04-01 22:04:23.000000
    [result_update] => 2022-04-01 22:04:23.000000
    [id] => 11
    [quiz_id] => 5
    [uname] => jjd
    [uid] => 2
    [ip] => 2a01:cb04:285:5b00:d999:8351:d01:17a8
    [score_achieved] => 0
    [score_max] => 49
    [score_min] => 0
    [answers_achieved] => 0
    [answers_total] => 10
    [duration] => 5s
    [] => 0
    [color] => 000.png
    [creation] => 01-04-2022 22:04:23
    [update] => 2022-04-01 22:04:23.000000
*}>




<{else}>
  <{* A faire : mettre ici la moyenne des scores 
  <hr>Pas de résultat à afficher pour <{$result.result_id }><hr>
  *}>
<{/if}>

    <div class="item-round-top <{$quiz.theme_ok}>-item-head"><center><b><{$quiz.name}></b><br><{$smarty.const._MA_QUIZMAKER_QUIZ_ANSWERS_AND_EXPLANATION}></center></div>
    <div class="item-round-none <{$quiz.theme_ok}>-item-info" style="padding:20px 50px 20px 50px;"><{$quiz.quiz_description}></div>
    <div class="item-round-bottom <{$quiz.theme_ok}>-item-legend"><center>...</center></div><br>
 
 

 
    <{* ----- balayage des questions pour construire le sommaire ----- *}>
    <div class="item-round-top <{$quiz.theme_ok}>-item-head"><center><{$smarty.const._CO_QUIZMAKER_SOMMAIRE}></center></div>
    <a href='' name='slide-sommaire'></a>
    <div class="item-round-none <{$quiz.theme_ok}>-item-body" style="padding:0px 30px 0px 30px;">
    
      <table class='quizTbl'>
        <{foreach item=question from=$questions name=quest}>
            <{if $question.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_QUESTION}>        
              <tr>
                  <td style="text-align:right;width:50px;"><{$question.numQuestion}>&nbsp;-&nbsp;</td>
                  <td>
                    <a href='#slide-<{$question.id}>' onclick='quizmaker_scrollWin();'> 
                         <{$question.question}><{if $admin}>  (#<{$question.id}> / <{$question.plugin}>)<{/if}>
                    </a>
                  </td>
                  <td>
                    <a href='#slide-<{$question.id}>' onclick='quizmaker_scrollWin();'> 
                        <img src='<{$modPathArrows}>/goto.png' title='<{$smarty.const._CO_QUIZMAKER_SEE_ANSWER}>'>                         
                    </a>
                  </td>
              </tr>
              <tr><td colspan="3"><hr class='default-hr-style-one'></td></tr>    
                        
            <{elseif $question.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_BEGIN}>        
            <{elseif $question.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_END}>        
            <{elseif $question.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_GROUP}>        
              <tr>
                  <td colspan="3"><center><b>
                        <a href='#slide-<{$question.id}>' onclick='quizmaker_scrollWin();'> 
                             <{$question.question}><{if $admin}>  (#<{$question.id}> / <{$question.plugin}>)<{/if}>
                        </a>
                  </b></center></td>
                    <tr><td colspan="3"><hr class='default-hr-style-one'></td></tr>
              </tr>
            <{/if}>
       
                        
        

        <{/foreach}>
      </table>
    </div>
    <div class="item-round-bottom <{$quiz.theme_ok}>-item-head"><center>...</center></div>
    <br>
 
 
 
    <{* ----- balayage des questions ----- *}>
    <{foreach item=question from=$questions name=quest}>
        <a href='' name='slide-<{$question.id}>'></a>
        
        
        <{if $question.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_QUESTION}>        
          <div class="item-round-none <{$quiz.theme_ok}>-item-info" style="padding:0px 50px 0px 50px;">
              <b><{$question.numQuestion}><{if $admin}> - <{$question.question}> (#<{$question.id}> / <{$question.plugin}>)<{/if}>
              <a href='#slide-sommaire' class='sommaire' onclick='quizmaker_scrollWin(-80);'><img src='<{$modPathArrows}>/sommaire.png' title='<{$smarty.const._CO_QUIZMAKER_SOMMAIRE}>'></a></b>

          </div>
          <div class="item-round-none <{$quiz.theme_ok}>-item-info" style="padding:0px 50px 0px 50px;">
              <{$question.solutions.libScoreMax}>
          </div>
          <{if $question.explanation}>
            <div class="item-round-none <{$quiz.theme_ok}>-item-body" style="padding:5px 50px 5px 50px;">
              <span style="color:blue;font-style:oblique;"><{$question.explanation}></span>
              <hr class='default-hr-style-one'>
            </div>
          <{/if}>
          
          <div class="item-round-none <{$quiz.theme_ok}>-item-body" style="padding:10px 50px 5px 50px;">
            <{$question.solutions.answers}>
          </div>
          
          
          <{if $question.learn_more OR $question.see_also}>
              <div class="item-round-none <{$quiz.theme_ok}>-item-body" style="padding:5px 50px 5px 50px;">
              <{if $question.learn_more}>
                  <a href="<{$question.learn_more}>"  target="_blank"><{$smarty.const._MA_QUIZMAKER_LEARN_MORE}></a>
              <{/if}>
              <{if $question.see_also}>
                  <{if $question.learn_more}><br><{/if}>
                  <a href="<{$question.see_also}>" target="_blank"><{$smarty.const._MA_QUIZMAKER_SEE_ALSO}></a>
              <{/if}>
              </div>
          <{/if}>
        
        <{elseif $question.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_BEGIN}>        
            <div class="item-round-top <{$quiz.theme_ok}>-item-head" style='margin-top:0px;'><center>...</center><br></div>      
            <{*
            <div class="item-round-top <{$quiz.theme_ok}>-item-head" style="padding:0px 50px 0px 50px;">
                <b><{$question.question}><{if $admin}> (#<{$question.id}> / <{$question.plugin}>)<{/if}>
                <a href='#slide-sommaire' class='sommaire' onclick='quizmaker_scrollWin(-80);'><img src='<{$modPathArrows}>/sommaire.png' title='<{$smarty.const._CO_QUIZMAKER_SOMMAIRE}>'></a></b>
            </div>
            *}>
        
        <{elseif $question.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_END}>        
<{*             <div class="item-round-bottom <{$quiz.theme_ok}>-item-info" style="padding:0px 50px 0px 50px;">...</div> *}>
            <div class="item-round-bottom <{$quiz.theme_ok}>-item-legend" style='margin-top:0px;'><center>...</center></div><br>
        
        <{elseif $question.typeForm == $smarty.const.QUIZMAKER_TYPE_FORM_GROUP}>        
          <div class="item-round-none <{$quiz.theme_ok}>-item-head" style="padding:0px 50px 0px 50px;">
              <center><b><{$question.question}></b></center>
          </div>
        <{/if}>
        
        
        
        <{if $question.isQuestion}>
        <{elseif $smarty.foreach.quest.first}>
        <{elseif $smarty.foreach.quest.last}>
        <{elseif $question.isParent}>
        <{/if}>
<{* ---------------------------------------------------------------------------- *}>         
        
        
        
             
    <{/foreach}>
    <{* ----- fin du balayage des questions ----- *}>

    <{* <div class="item-round-bottom <{$quiz.theme_ok}>-item-legend" style='margin-top:0px;'><center>...</center></div><br> *}>

	<div class="clear">&nbsp;</div>
<{*
	<{if $pagenav}>
		<div class="xo-pagenav floatright"><{$pagenav}></div>
		<div class="clear spacer"></div>
	<{/if}>
<script>
tth_set_value('last_asc', true);
tth_trierTableau('quiz_question_list', 7);  
</script>

<style>
.quiz_legend img{
    text-align: center;
    margin:8px;
}
.quiz_legend2{
    text-align: left;
    margin:8px;
}
</style>
*}>
 
<{include file='db:quizmaker_footer.tpl' }>





 


