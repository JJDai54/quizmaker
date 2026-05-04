<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>


<style>
.div_color_set{
  width:30px;
  height:30px;
}
</style>

<{* =================================================== *}>
      <div class="item-round-top <{$cat.theme}>-item-head" style="padding:10px 10px 10px 32px;">
        <img src='<{$smarty.const.QUIZMAKER_URL_UPLOAD}>/categories/<{$cat.image}>' class='left' style='height:120px;' title='' alt=''>
            <a href="<{$smarty.const.XOOPS_URL}>/modules/quizmaker/quiz.php?cat_id=<{$cat.id}>" >
              <{$cat.name}>
            </a>
      </div>
      <div class="item-round-none <{$cat.theme}>-item-body">
      <{if $cat.description}>
        <{$cat.description}>
      <{else}>
      <center>...</center>
      <{/if}>
      </div>
      <div class="item-round-bottom <{$cat.theme}>-item-legend"><center>...</center></div><br>

<{* =================================================== *}>
