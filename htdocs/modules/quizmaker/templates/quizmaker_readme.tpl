<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>

<{include file='db:quizmaker_header.tpl' }>
    

<form name="form_readme" id="form_readme" action="readme.php" method="post" onsubmit="return xoopsFormValidate_form();">
<input type='hidden' name='op' value='readmeIsOk'>
<input type='hidden' name='cat_id' value='<{$cat_id}>'>
<input type='hidden' name='quiz_id' value='<{$quiz_id}>'>
<input type='hidden' name='readmeOk' value='1'>
<input type='hidden' name='player_id' value='<{$player_id}>'>
<input type='hidden' name='from' value='<{$from}>'>
 
    <div class="item-round-top <{$catTheme}>-item-head"><center><{$avertissement}><{$smarty.const._MA_QUIZMAKER_VALIDATE_TO_CONTINUE}></center></div>

    <div class="item-round-none <{$catTheme}>-item-body" style="padding:8px 0px 8px 0px;">
        <{$catReadme}>
    </div>

    <div class="item-round-none <{$catTheme}>-item-body"><center>
        <input type="button" value="&nbsp;&nbsp;&nbsp;<{$smarty.const._MA_QUIZMAKER_CANCEL}>&nbsp;&nbsp;&nbsp;" onclick="history.go(-1);return true;">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" class="formbutton" name="" id="" value="&nbsp;&nbsp;&nbsp;<{$smarty.const._MA_QUIZMAKER_VALIDATE}>&nbsp;&nbsp;&nbsp;">
        <br><br>
    </center></div>
    
    <div class="item-round-bottom <{$catTheme}>-item-legend"><center>...</div><br>
</form>
<{include file='db:quizmaker_footer.tpl' }>
