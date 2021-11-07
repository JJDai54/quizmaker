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
    <div class="itemRound-top <{$quiz.theme_ok}>-itemHead"><center><{$quiz.name}></center></div>
    <div class="itemRound-none <{$quiz.theme_ok}>-itemInfo" style="padding:20px 50px 20px 50px;"><{$quiz.quiz_description}></div>
    <div class="itemRound-bottom <{$quiz.theme_ok}>-itemLegend"><center>...</center></div><br>
 
 

 
    <{* ----- balayage des questions pour construire le sommaire ----- *}>
    <div class="itemRound-top <{$quiz.theme_ok}>-itemHead"><center><{$smarty.const._CO_QUIZMAKER_SOMMAIRE}></center></div>
    <a href='' name='slide-sommaire'></a>
    <div class="itemRound-none <{$quiz.theme_ok}>-itemBody" style="padding:0px 50px 0px 50px;">
    
      <table>
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
                  <td style="text-align:right;"><{$question.numQuestion}> - </td>
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





 


