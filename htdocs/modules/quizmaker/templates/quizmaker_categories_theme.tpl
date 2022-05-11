<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>

<style>
.div_color_set{
  width:30px;
  height:30px;
}
</style>

<div>
    <div class="itemLegend">
      <a href="<{$smarty.const.XOOPS_URL}>/modules/quizmaker/categories.php" >
        <{$smarty.const._ALL}>
      </a>

    </div>
    <{foreach item=cat from=$categories}>

          <div class="itemLegend <{$cat.theme}>-itemLegend" >
            <a href="<{$smarty.const.XOOPS_URL}>/modules/quizmaker/categories.php?cat_id=<{$cat.id}>" >
              <{$cat.name}>
            </a>

          </div>

    <{/foreach}>

</div><hr>
















