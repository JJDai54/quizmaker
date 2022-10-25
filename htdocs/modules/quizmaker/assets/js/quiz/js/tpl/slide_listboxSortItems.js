

 /*******************************************************************
  *                     _listboxIntruders1
  * *****************************************************************/

class listboxSortItems extends quizPrototype{
name = "listboxSortItems"
//---------------------------------------------------
 constructor(question, chrono) {
    super(question, chrono);
  }

//---------------------------------------------------
  


  
/* *************************************
*
* ******** */
build (){
    var  currentQuestion = this.question;
    var name = this.getName();
    var id = `${name}-1`;
    
var tpl=`
<style>
.quizBtn01{
	width:28px;
	height:18px;
	background-repeat:no-repeat;
	border:none;
    margin-top:10px;
}
</style><center>
<table style="text-align: right; width: 50%;" border="0" cellpadding="2"
cellspacing="2">
<tbody>
<tr>
<td colspan="1" rowspan="3" style="vertical-align: top;width:40px;">{listeItems}</td>
<td style="vertical-align: middle;width:40px;">{btn0}<br>{btn1}<br>{btn2}<br>{btn3}<br></td>
</tr>
</tbody>
</table></center>`;
//alert ("listboxSortItems");
var tplButton = `<input type="button" class="quizBtn01" style="background:url('images/blue/{moveTo}.png');" onclick="quiz_MoveToTop('{id}','{moveTo}');">`;

    var btn0 = tplButton.replaceAll('{moveTo}','top').replace('{id}',id); 
    var btn1 = tplButton.replaceAll('{moveTo}','up').replace('{id}',id); 
    var btn2 = tplButton.replaceAll('{moveTo}','down').replace('{id}',id); 
    var btn3 = tplButton.replaceAll('{moveTo}','bottom').replace('{id}',id); 
    
    var tblHtml = tpl.replace('{btn0}', btn0).replace('{btn1}', btn1).replace('{btn2}', btn2).replace('{btn3}', btn3);
     
    const answers = [];
    answers.push(tblHtml);
//alert(tblHtml);    
    var tItems = this.shuffleArray(this.data.words);
    //alert(tItems);
    var click = (quiz.onClickSimple) ? "onclick" : "ondblclick";
    //var wordsList = tItems.join(",");
    //-------------------------------------
    var extra = ''; //`${click}="quiz_deleteValue('${id}');"`;
    var listItems = getHtmlListbox(name, id, tItems, tItems.length, -1, currentQuestion.numbering, 0, extra);
    var html = tblHtml.replace('{listeItems}', listItems);
    return html;
 }
 
  
/* *************************************
*
* ******** */
prepareData(){
var tItems = [];
    var currentQuestion = this.question;
    
    //on force l'option de mélange des options sinon aucun intéret
    currentQuestion.shuffleAnswers = 1;
    
    var k = 0;
    //alert(currentQuestion.answers[k].proposition);
    this.data.words = currentQuestion.answers[k].proposition.split(",");  
} 

/* *************************************
*
* ******** */
computeScoresMinMax(){

    var currentQuestion = this.question;
    var tItems = this.data.items;
    
    for(var key in tItems)
    {
        if (tItems[key]*1 > 0) this.scoreMaxi += tItems[key]*1;
        if (tItems[key]*1 < 0) this.scoreMini += tItems[key]*1;
    }
     return true;
 }
 
/* *************************************
*
* ******** */

getScore ( answerContainer){
var bolOk = true;

    var currentQuestion = this.question;
    var id = `${this.getName()}-1`;
    
    var listObj = document.getElementById(id);
    var tItems = this.data.words;

    var options = listObj.getElementsByTagName("OPTION");

    var tRep = [];
    for (var i = 0; i < options.length ; i++) {
        console.log("===> getScore-listSortItems : " + options[i].text + " == " + i + " => " + tItems[i]);
        tRep.push(options[i].text) 
    }
    var strRep = tRep.join(',');
    bolOk = strRep == currentQuestion.answers[0].proposition;
    
//        alert(currentQuestion.options.toUpperCase() );
    if(!bolOk && currentQuestion.options.toUpperCase() == "R"){
        tRep.reverse();
        var strRep = tRep.join(',');
        //alert("inver : " + strRep);
        bolOk = (strRep == currentQuestion.answers[0].proposition);
    }
//     
//     
//     
// //alert ('quiz_MoveToTop -> ' + listObj.name + ' -> '  + where);
// 
//     console.log("====================================");
//     for (var i = 0; i < options.length ; i++) {
//         console.log("===> getScore-listSortItems : " + options[i].text + " == " + i + " => " + tItems[i]);
//         if (options[i].text != tItems[i]) bolOk = false; 
//     }
//     if(!bolOk && currentQuestion.options.toLowerCase() == "R"){
//         var tItems = duplicateArray(this.data.words).reverse();
//     }
    return (bolOk) ? currentQuestion.answers[0].points : 0;
  }

  
/* *************************************
*
* ******** */
isInputOk ( answerContainer){
    var currentQuestion = this.question;

var bolOk = true;

/*
    var id = `${this.getName()}-1`;
    var obList = getObjectById(id);

       var tOptions = obList.options;
       var minReponse = currentQuestion.minReponse;
       var nbRep = currentQuestion.answers[0].keys.length - tOptions.length;

       if (minReponse == 0){
         bolOk = (nbRep > 0);
       }else{
         bolOk = (nbRep >= minReponse);
       }

*/


      return bolOk;

 }

  
/* *************************************
*
* ******** */

getAllReponses (flag = 0){
      var  currentQuestion = this.question;


    var tReponses = [];
    var k = 0; 
    var t = [];
    for(var k in this.data.words){
        t.push ([k*1+1, this.data.words[k]]);
    }

    return formatArray0(t,"-","");
 }

  
/* *************************************
*
* ******** */

// getGoodReponses (){
//     var currentQuestion = this.question;
//     var tReponses = [];
//     var tItems = this.data.items;
// 
//     for(var key in tItems)
//     {
//         if (tItems[key] > 0) tReponses.push (`${key} ===> ${tItems[key]} points`) ;       
//     }
// 
//     return tReponses.join("<br>");
//  }

  

  
/* *************************************
*
* ******** */

update(nameId) {
}

  
/* *************************************
*
* ******** */

incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 

//--------------------------------------------------------------------------------
reloadQuestion() {
    var currentQuestion = this.question;
    var name = this.getName();
    var id = `${name}-1`;
    var ob = document.getElementById(id);
    ob.innerHTML = "";

    var tItems = this.shuffleArray(this.data.words);
    for(var key in tItems)
    {
        console.log(key + " = " +  tItems[key]);
        var option = document.createElement("option");
        //alert(tItems[key]);
        option.text = tItems[key];
        option.value = tItems[key];
        ob.add(option);
    }
    ob.selectedIndex = 0;
}
 
/* ************************************
*
* **** */
showGoodAnswers()
  {
    var currentQuestion = this.question;
    var name = this.getName();
    var id = `${name}-1`;
    var ob = document.getElementById(id);
    ob.innerHTML = "";


    var tItems = this.data.words;
    for(var key in tItems)
    {
    //alert(`showGoodAnswers - ${key} = ${tItems[key]}`);
        console.log(key + " = " +  tItems[key]);

          var option = document.createElement("option");
          option.text = tItems[key];
          option.value = tItems[key];
          ob.add(option);

    }
}

/* ************************************
*
* **** */
showBadAnswers()
  {
    var currentQuestion = this.question;
    var name = this.getName();
    var id = `${name}-1`;
    var ob = document.getElementById(id);
    ob.innerHTML = "";


    var tItems = this.shuffleArray(this.data.words);
    for(var key in tItems)
    {
        console.log(key + " = " +  tItems[key]);

          var option = document.createElement("option");
          option.text = tItems[key];
          option.value = tItems[key];
          ob.add(option);

    }
}
  
 
  
 
} // ----- fin de la classe ------
