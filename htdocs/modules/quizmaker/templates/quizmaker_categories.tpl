<{if $smarty.const.QUIZMAKER_SHOW_TPL_NAME==1}>
<div style="text-align: center; background-color: black;"><span style="color: yellow;">Template : <{$smarty.template}></span></div>
<{/if}>

<{include file='db:quizmaker_header.tpl' }>

<{*
<{if $categoriesCount > 1}>
    <{include file='db:quizmaker_categories_theme.tpl' }>
<{/if}>
*}>
 
<style>
.catname{
    font-weight:bold;
    font-size:1.5em;
}
.catdescription{
    font-weight:normal;
    font-size:0.9em;
    color:blue;
}
.quiz table{
    width:100%;
    margin:0px;
    padding:0px;
    border:0px solid transparent ;
    background:transparent;
}
.quiz td{
    border:0px solid transparent ;
    background:transparent;
}


</style> 

<{* ************************************************ *}>
<div class='quiz'>
<table>
  <{foreach item=cat from=$categories }>
    <{assign  var='urlQuizCat' value="`$smarty.const.XOOPS_URL`/modules/quizmaker/quiz.php?cat_id=`$cat.id`"}>
    <tr>
        <td style='padding-left:32px;'>
            <{if $cat.image}>
            <a href='<{$urlQuizCat}>'>
            <img class='left' src='<{$smarty.const.QUIZMAKER_URL_UPLOAD}>/categories/<{$cat.image}>' style='height:92px' alt='' title=''>
            </a>
            <{/if}>
            <a href='<{$urlQuizCat}>'>
            <span class='catname'><{$cat.name}></span><br>
            </a>
            <span class='catdescription'><{$cat.description}></span>
            <hr>
        </td>
    </tr>
    
  <{/foreach}>
</table>
 </div> 

<{include file='db:quizmaker_footer.tpl' }>
