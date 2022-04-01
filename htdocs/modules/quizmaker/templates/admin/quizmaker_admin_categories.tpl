<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>
                  <{assign var="fldImg" value="blue"}>
                  <{assign var="styleParent" value=""}>

<{if $categories_list}>
	<table class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_CATEGORIES_ID}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_CATEGORIES_NAME}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_WEIGHT}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_CATEGORIES_THEME}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_CREATION}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_UPDATE}></th>
				<th class="center"><{$smarty.const._AM_QUIZMAKER_NB_QUIZ}></th>
				<th class="center width5"><{$smarty.const._AM_QUIZMAKER_FORM_ACTION}></th>
			</tr>
		</thead>
		<{if $categories_count}>
		<tbody>
			<{foreach item=cat from=$categories_list name=catItem}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class='center'><{$cat.id}></td>
				<td class='left'>
                    <a href="categories.php?op=edit&amp;cat_id=<{$cat.id}>" title="<{$smarty.const._EDIT}>">
                    <{$cat.name}></a>
                </td>


                <{* ---------------- Arrows Weight -------------------- *}>
                <td class='center' <{$styleParent}> >
                    <{if $smarty.foreach.catItem.first}>
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/first-0.png" title="<{$smarty.const._AM_QUIZMAKER_FIRST}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/up-0.png" title="<{$smarty.const._AM_QUIZMAKER_UP}>">
                    <{else}>
                      <a href="categories.php?op=weight&cat_id=<{$cat.id}>&sens=first&quiz_id=<{$cat.quest_quiz_id}>&cat_weight=<{$cat.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/first-1.png" title="<{$smarty.const._AM_QUIZMAKER_FIRST}>">
                      </a>
                    
                      <a href="categories.php?op=weight&cat_id=<{$cat.id}>&sens=up&quiz_id=<{$cat.quest_quiz_id}>&cat_weight=<{$cat.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/up-1.png" title="<{$smarty.const._AM_QUIZMAKER_UP}>">
                      </a>
                    <{/if}>
                 
                    <{* ----------------------------------- *}>
                    <img src="<{$modPathIcon16}>/blank-08.png" title="">
                    <{$cat.weight}>
                    <img src="<{$modPathIcon16}>/blank-08.png" title="">
                    <{* ----------------------------------- *}>
                 
                    <{if $smarty.foreach.catItem.last}>
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/down-0.png" title="<{$smarty.const._AM_QUIZMAKER_DOWN}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/last-0.png" title="<{$smarty.const._AM_QUIZMAKER_LAST}>">
                    <{else}>
                    
                    <a href="categories.php?op=weight&cat_id=<{$cat.id}>&sens=down&quiz_id=<{$cat.quest_quiz_id}>&cat_weight=<{$cat.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/down-1.png" title="<{$smarty.const._AM_QUIZMAKER_DOWN}>">
                      </a>
                 
                    <a href="categories.php?op=weight&cat_id=<{$cat.id}>&sens=last&quiz_id=<{$cat.quest_quiz_id}>&cat_weight=<{$cat.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/last-1.png" title="<{$smarty.const._AM_QUIZMAKER_LAST}>">
                      </a>
                    <{/if}>
                <{* ---------------- /Arrows -------------------- *}>
















				<td class='center'><{$cat.theme}></td>
				<td class='center'><{$cat.creation}></td>
				<td class='center'><{$cat.update}></td>
				<td class='center'><{$cat.nbQuiz}></td>
                
                <{* ----- Actions ----- *}>
				<td class="center  width5">
					<a href="categories.php?op=edit&amp;cat_id=<{$cat.id}>" title="<{$smarty.const._EDIT}>">
                        <img src="<{xoModuleIcons16 edit.png}>" alt="categories" />
                        </a>
                    <{if $cat.nbQuiz == 0}>
					<a href="categories.php?op=delete&amp;cat_id=<{$cat.id}>" title="<{$smarty.const._DELETE}>">
                        <img src="<{xoModuleIcons16 delete.png}>" alt="categories" />
                        </a>
                    <{else}>
                        <img src="<{xoModuleIcons16 warning.png}>" alt="categories" title="<{$smarty.const._AM_QUIZMAKER_CAT_NOT_EMPTY}>"/>
                    <{/if}>
					<img src="<{$modPathIcon16}>/blank-16.png" alt="" />
					<a href="quiz.php?op=list&cat_id=<{$cat.id}>&sender='cat_id'" title="<{$smarty.const._AM_QUIZMAKER_QUIZ}>">
                        <img src="<{xoModuleIcons16 inserttable.png}>" alt="Quiz" />
                        </a>
				</td>
			</tr>
			<{/foreach}>
		</tbody>
		<{/if}>
	</table>
	<div class="clear">&nbsp;</div>
	<{if $pagenav}>
		<div class="xo-pagenav floatright"><{$pagenav}></div>
		<div class="clear spacer"></div>
	<{/if}>
<{/if}>
<{if $form}>
	<{$form}>
<{/if}>
<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>
