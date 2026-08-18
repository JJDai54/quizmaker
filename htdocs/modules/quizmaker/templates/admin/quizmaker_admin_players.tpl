<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>
<{* =================================================================== *}>

<{include file='db:quizmaker_admin_header.tpl' }>
<form name='quizmaker_select_filter' id='quizmaker_select_filter' action='players.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="0" />

<div class="floatleft">
    <div class="xo-buttons">
      <{$smarty.const._AM_QUIZMAKER_CATEGORIES_NAME}> : <{$inpCategory}>
      <{$smarty.const._AM_QUIZMAKER_DOMAINE}> : <{$inpDomaine}>
      <{$smarty.const._AM_QUIZMAKER_PLAYER}> : <{$inpUsers}>

      <{if $countEnr > 0}>  
          <{if $allowed_clear and $clear_participations}>  
            <{$actions}>
          <{/if}>        
          <{$btn.exporCSV}>
      <{/if}>        
           
    </div>
</div>

</form><br>

<{* =================================================================== *}>
<{*<hr>domaine = <{$domaine}><hr> *}>
<{if $domaine == 'cookies'}>
    <{include file='db:quizmaker_admin_players_cookies.tpl' }>
<{else}>             
    <{include file='db:quizmaker_admin_players_results.tpl' }>
<{/if}>

<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>
