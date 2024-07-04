<{*
<{include file='db:quizmaker_header.tpl' }>
*}>

<!-- Header -->
<{include file='db:quizmaker_admin_header.tpl' }>

<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<form name='quizmaker_select_import' id='quizmaker_select_import' action='import.php?op=list' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
    <input type="hidden" name="op" value="list" />
    <{$smarty.const._AM_QUIZMAKER_TYPE_IMPORT}> : <{$inpTypeImport}>
    <INPUT TYPE='hidden' name='cat_id' id='cat_id' VALUE=''>
    <INPUT TYPE='hidden' name='quiz_id' id='quiz_id' VALUE=''>
    
    <INPUT TYPE='hidden' name='from_cat_id' id='from_cat_id' VALUE=''>
    <INPUT TYPE='hidden' name='from_quiz_id' id='from_quiz_id' VALUE=''>
    <INPUT TYPE='hidden' name='to_cat_id' id='to_cat_id' VALUE=''>
    <INPUT TYPE='hidden' name='to_quiz_id' id='to_quiz_id' VALUE=''>
    <INPUT TYPE='hidden' name='from_type_question' id='from_type_question' VALUE=''>
    <INPUT TYPE='hidden' name='order_by' id='order_by' VALUE=''>
    <INPUT TYPE='hidden' name='group_to' id='group_to' VALUE=''>
                                                  
</form>


<{if $form}>
	<{$form}>
<{/if}>

<script type='text/javascript'>
function quizmaker_reload_import(ev){
 var prefix = 'select_';
 var catId = 'from_cat_id'
 var quizId = 'from_quiz_id'
 var typeQuestionId = 'from_type_question'
 var id = '';

    //alert('ev.currentTarget.id : ' + ev.currentTarget.id);
    switch(ev.currentTarget.id){

        case prefix + 'from_cat_id'  : var idArr=['from_cat_id','to_cat_id','to_quiz_id','order_by','group_to']; break;
        case prefix + 'from_quiz_id' : var idArr=['from_cat_id','from_quiz_id','to_cat_id','to_quiz_id','order_by','group_to']; break;
        case prefix + 'to_cat_id'    : var idArr=['from_cat_id','from_quiz_id','to_cat_id','group_to']; break;
        case prefix + 'order_by'     : var idArr=['from_cat_id','from_quiz_id','to_cat_id','to_quiz_id','order_by','group_to']; break;
        //case prefix + 'to_quiz_id' : var idArr=['from_cat_id','from_quiz_id','to_cat_id','to_quiz_id']; break;
    }
    
    for(var h=0; h < idArr.length; h++){
        id = idArr[h];
        //alert(id);
        if(document.getElementById(prefix + id))
        document.getElementById(id).value = document.getElementById(prefix + id).value;
    }

    //alert('quizmaker_reload_import_quest : ' +  document.getElementById(name2).value);
    

    //document.getElementById('type_question').value = document.getElementById('quest_type_question').value;
    document.quizmaker_select_import.submit();
}

function quizmaker_reload_import_quest(ev, type_import, quizId=0){
    //alert('quizmaker_reload_import_quest');
    var catId = ev.currentTarget.value;
    document.getElementById('cat_id').value = ev.currentTarget.value;
    document.getElementById('quiz_id').value = quizId;
    //document.getElementById('type_question').value = document.getElementById('quest_type_question').value;
    document.quizmaker_select_import.submit();
}
function quizmaker_setValue(ev, id){

    document.getElementById(id).value = ev.currentTarget.value;
    //document.getElementById('type_question').value = ev.currentTarget.value;
    alert( document.getElementById(id).value + "=" +  ev.currentTarget.value);
    return false;
    }
    
function submitImportMod(ev){
    document.form_import.submit();
}
</script>

<!-- Footer -->
<{include file='db:quizmaker_admin_footer.tpl' }>

