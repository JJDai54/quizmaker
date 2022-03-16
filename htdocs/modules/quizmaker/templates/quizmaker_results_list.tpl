<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>
<{include file='db:quizmaker_header.tpl' }>

<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='results.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="" />

    <div class="itemRound-top <{$catTheme}>-itemHead"><center><{$smarty.const._MA_QUIZMAKER_SELECTION}></center></div>
    <div class="itemRound-none <{$catTheme}>-itemBody">
    <table width='80%'>
        <tr>
            <td class="right" style='padding:5px;'><{$smarty.const._MA_QUIZMAKER_CATEGORIES}> : </td>
            <td class="left" style='padding:5px;'><{$selector.inpCategory}></td>
        </tr>
        <tr>
            <td class="right" style='padding:5px;'><{$smarty.const._MA_QUIZMAKER_QUIZ}> : </td>
            <td class="left" style='padding:5px;'><{$selector.inpQuiz}></td>
        </tr>
    </table>
      
      
    </div>
    <div class="itemRound-bottom <{$catTheme}>-itemLegend" style='margin-top:0px;'><center>...</center></div><br>
</form>

<{if $resultsCount > 0}>
	<div class="clear">&nbsp;</div>
	<{if $pagenav}>
		<div class="xo-pagenav floatright"><{$pagenav}></div>
		<div class="clear spacer"></div>
	<{/if}>

    <div class="itemRound-top <{$quiz.theme_ok}>-itemHead"><center><{$quiz.name}></center></div>
    <div class="itemRound-none <{$quiz.theme_ok}>-itemInfo" style="padding:20px 50px 20px 50px;"><{$quiz.quiz_description}></div>
    <div class="itemRound-none <{$quiz.theme_ok}>-itemBody">

	<table id='quiz_question_list' name='quiz_question_list' class='table table-bordered' style='margin-bottom=0px;'>
		<thead>
			<tr class='head'>
				<th class="center">#</th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_UNAME}></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_SCORE}></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_ANSWERS}></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_DURATION}></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_NOTE}></th>
				<th class="center"></th>
				<th class="center"><{$smarty.const._MA_QUIZMAKER_DATE}></th>
			</tr>
		</thead>
		<{if $results_count}>
		<tbody>
			<{foreach item=Result from=$results name=res}>
                
  			<tr class='<{cycle values='odd, even'}>'>
				<td class='center'><{$Result.chrono}></td>
				<td class='left'><{$Result.uname}></td>
				<td class='right'><{$Result.score_achieved}> / <{$Result.score_max}></td>
				<td class='right'><{$Result.answers_achieved}> / <{$Result.answers_total}></td>
				<td class='center'><{$Result.duration}></td>
				<td class='center'><{$Result.note}></td>
				<td class='center'><img src="<{$modPathIcon16}>/notes/<{$Result.color}>"></td>
				<td class='center'><{$Result.creation}></td>
			</tr>
			<{/foreach}>
		</tbody>
		<{/if}>
	</table>
    <br>
    </div>
    <div class="itemRound-bottom <{$quiz.theme_ok}>-itemLegend" style='margin-top:0px;'><center>...</center></div><br>

	<div class="clear">&nbsp;</div>
	<{if $pagenav}>
		<div class="xo-pagenav floatright"><{$pagenav}></div>
		<div class="clear spacer"></div>
	<{/if}>
<{*
<script>
tth_set_value('last_asc', true);
tth_trierTableau('quiz_question_list', 7);  
</script>
*}>


<style>
.quiz_legend img{
    text-align: center;
    margin:8px;
}
.quiz_legend2{
    text-align: left;
    margin:8px;
}
.quiz_legend3{
    text-align: left;
    margin:8px;
    width:50px;
}

</style>
    <div class="itemRound-top <{$catTheme}>-itemHead"><center><{$smarty.const._CO_QUIZMAKER_LEGEND}></center></div>
    <div class="itemRound-none <{$catTheme}>-itemBody">
    <center>
<table class='quizTbl'>
    <tr>
        <td class='quiz_legend3'><img src="<{$modPathIcon16}>/notes/005.png"></td>
        <td class='quiz_legend'><{$smarty.const._MA_QUIZMAKER_RESULT_5}></td>
        <td class='quiz_legend3'></td>
        <td class='quiz_legend3'><img src="<{$modPathIcon16}>/notes/002.png"></td>
        <td class='quiz_legend'><{$smarty.const._MA_QUIZMAKER_RESULT_2}></td>
    </tr>
    <tr>
        <td class='quiz_legend3'><img src="<{$modPathIcon16}>/notes/004.png"></td>
        <td class='quiz_legend'><{$smarty.const._MA_QUIZMAKER_RESULT_4}></td>
        <td class='quiz_legend3'></td>
        <td class='quiz_legend3'><img src="<{$modPathIcon16}>/notes/001.png"></td>
        <td class='quiz_legend left'><{$smarty.const._MA_QUIZMAKER_RESULT_1}></td>
    </tr>
    <tr>
        <td class='quiz_legend3'><img src="<{$modPathIcon16}>/notes/003.png"></td>
        <td class='quiz_legend'><{$smarty.const._MA_QUIZMAKER_RESULT_3}></td>
        <td class='quiz_legend3'></td>
        <td class='quiz_legend3'><img src="<{$modPathIcon16}>/notes/000.png"></td>
        <td class='quiz_legend left'><{$smarty.const._MA_QUIZMAKER_RESULT_0}></td>
    </tr>
    
    

</table>
    </center>
    </div>
    <div class="itemRound-bottom <{$catTheme}>-itemLegend" style='margin:0px 0px 0px 0px;'><center>...</center></div><br>



<{else}>

    <div class="itemRound-top <{$catTheme}>-itemHead"><center><{$smarty.const._MA_QUIZMAKER_RESULTS}></center></div>
    <div class="itemRound-none <{$catTheme}>-itemBody">
        <center><{$smarty.const._MA_QUIZMAKER_NO_RESULTS}></center> 
    </div>
    <div class="itemRound-bottom <{$catTheme}>-itemLegend" style='margin-top:0px;'><center>...</center></div><br>
 
<{/if}>

