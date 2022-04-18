<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>

<{include file='db:quizmaker_header.tpl' }>

<script>
function qm_scrollWin(offsetV = -50){
var intervalID = setTimeout(qm_scrollWin2, 80, offsetV);
}
function qm_scrollWin2(offsetV){
document.scrollTop = -100;
window.scroll(0, window.scrollY + offsetV);
//alert('scrollWin');
}
</script>
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
    <div class="itemRound-top  <{$themeResult}>-itemHead"><center><b><{$smarty.const._MA_QUIZMAKER_RESULTS_FOR}><{$result.result_uname}></b></center></div>
    <div class="itemRound-none <{$themeResult}>-itemBody" style="padding:20px 50px 20px 50px;">
    

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
    <div class="itemRound-bottom <{$themeResult}>-itemLegend"><center><b><{$smarty.const._MA_QUIZMAKER_THANKS_FOR_PARTICIPATION}></b></center></div><br>



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

    <div class="itemRound-top <{$quiz.theme_ok}>-itemHead"><center><{$quiz.name}><br><{$smarty.const._MA_QUIZMAKER_QUIZ_ANSWERS_AND_EXPLANATION}></center></div>
    <div class="itemRound-none <{$quiz.theme_ok}>-itemInfo" style="padding:20px 50px 20px 50px;"><{$quiz.quiz_description}></div>
    <div class="itemRound-bottom <{$quiz.theme_ok}>-itemLegend"><center>...</center></div><br>
 
 

 
    <{* ----- balayage des questions pour construire le sommaire ----- *}>
    <div class="itemRound-top <{$quiz.theme_ok}>-itemHead"><center><{$smarty.const._CO_QUIZMAKER_SOMMAIRE}></center></div>
    <a href='' name='slide-sommaire'></a>
    <div class="itemRound-none <{$quiz.theme_ok}>-itemBody" style="padding:0px 30px 0px 30px;">
    
      <table class='quizTbl'>
        <{foreach item=question from=$questions name=quest}>
        
            <{if $question.type_question == 'pageInfo'}>
                <{if !$smarty.foreach.quest.first}>
                    <tr><td colspan="2"><hr class='default-hr-style-one'></td></tr>
                <{/if}>
        
              <tr>
                  <td colspan='2'><center><b>
                        <a href='#slide-<{$question.id}>' onclick='qm_scrollWin();'> 
                             <{$question.question}><{if $admin}>  (#<{$question.id}> / <{$question.type_question}>)<{/if}>
                        </a>
                  </b></center></td>
              </tr>
            <{else}>
              <tr>
                  <td style="text-align:right;width:50px;"><{$question.numQuestion}>&nbsp;-&nbsp;</td>
                  <td>
                    <a href='#slide-<{$question.id}>' onclick='qm_scrollWin();'> 
                         <{$question.question}><{if $admin}>  (#<{$question.id}> / <{$question.type_question}>)<{/if}>
                    </a>
                  </td>
              </tr>
            <{/if}>
        
        
        

        <{/foreach}>
      </table>
    </div>
    <div class="itemRound-top <{$quiz.theme_ok}>-itemHead"><center>...</center></div>
    <br>
 
 
 
    <{* ----- balayage des questions ----- *}>
    <{foreach item=question from=$questions name=quest}>
        <a href='' name='slide-<{$question.id}>'></a>
        <{if $question.type_question == 'pageInfo'}>
        
          <{if !$smarty.foreach.quest.first}>
              <div class="itemRound-bottom <{$quiz.theme_ok}>-itemInfo" style="padding:0px 50px 0px 50px;">...</div>
          <{/if}>
            <br>
            <div class="itemRound-top <{$quiz.theme_ok}>-itemHead" style="padding:0px 50px 0px 50px;">
                <b><{$question.question}><{if $admin}> - <{$question.question}> (#<{$question.id}> / <{$question.type_question}>)<{/if}>
                <a href='#slide-sommaire' class='sommaire' onclick='qm_scrollWin(-80);'><img src='<{$modPathIcon16}>/sommaire.png' title='<{$smarty.const._CO_QUIZMAKER_SOMMAIRE}>'></a></b>
            </div>
        <{else}>
<{*     <div class="itemRound-none <{$quiz.theme_ok}>-itemInfo"><center>.<hr class='default-hr-style-one'><br></center></div> *}>
          <div class="itemRound-none <{$quiz.theme_ok}>-itemInfo" style="padding:0px 50px 0px 50px;">
              <b><{$question.numQuestion}><{if $admin}> - <{$question.question}> (#<{$question.id}> / <{$question.type_question}>)<{/if}>
              <a href='#slide-sommaire' class='sommaire' onclick='qm_scrollWin(-80);'><img src='<{$modPathIcon16}>/sommaire.png' title='<{$smarty.const._CO_QUIZMAKER_SOMMAIRE}>'></a></b>

          </div>
          <div class="itemRound-none <{$quiz.theme_ok}>-itemInfo" style="padding:0px 50px 0px 50px;">
              <{$question.solutions.libScoreMax}>
          </div>
          <div class="itemRound-none <{$quiz.theme_ok}>-itemBody" style="padding:10px 50px 5px 50px;">
            <{$question.solutions.answers}>
          </div>
          
          <{if $question.explanation}>
            <div class="itemRound-none <{$quiz.theme_ok}>-itemBody" style="padding:5px 50px 5px 50px;">
              <hr class='default-hr-style-one'>
              <span style="color:blue;font-style:oblique;"><{$question.explanation}></span>
            </div>
          <{/if}>
        <{/if}>
        <{if $question.learn_more OR $question.see_also}>
            <div class="itemRound-none <{$quiz.theme_ok}>-itemBody" style="padding:5px 50px 5px 50px;">
            <{if $question.learn_more}>
                <a href="<{$question.learn_more}>"  target="_blank"><{$smarty.const._MA_QUIZMAKER_LEARN_MORE}></a>
            <{/if}>
            <{if $question.see_also}>
                <{if $question.learn_more}><br><{/if}>
                <a href="<{$question.see_also}>" target="_blank"><{$smarty.const._MA_QUIZMAKER_SEE_ALSO}></a>
            <{/if}>
            </div>
        <{/if}>

    
        <{if $smarty.foreach.quest.last}>
            <div class="itemRound-bottom <{$quiz.theme_ok}>-itemInfo" style="padding:0px 50px 0px 50px;">...</div>
        <{/if}>
             
    <{/foreach}>
    <{* ----- fin du balayage des questions ----- *}>

    <div class="itemRound-bottom <{$quiz.theme_ok}>-itemLegend" style='margin-top:0px;'><center>...</center></div><br>

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





 


