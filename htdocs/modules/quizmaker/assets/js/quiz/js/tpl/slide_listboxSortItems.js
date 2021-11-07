

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

//     var btn0 = `<input type="button" class="quizBtn01" style="background:url('images/blue/top.png');" onclick="quiz_MoveToTop('${id}',top');">`;
//     var btn1 = `<input type="button" class="quizBtn01" style="background:url('images/blue/up.png');" onclick="quiz_MoveToTop('${id}','up');">`;
//     var btn2 = `<input type="button" class="quizBtn01" style="background:url('images/blue/down.png');" onclick="quiz_MoveToTop('${id}','down');">`;
//     var btn3 = `<input type="button" class="quizBtn01" style="background:url('images/blue/bottom.png');" onclick="quiz_MoveToTop('${id}','bottom');">`;
    
    var tblHtml = tpl.replace('{btn0}', btn0).replace('{btn1}', btn1).replace('{btn2}', btn2).replace('{btn3}', btn3);
     
    const answers = [];
    answers.push(tblHtml);
//alert(tblHtml);    
    var keys = this.shuffleArray(Object.keys(this.data.items));
    var click = (quiz.onClickSimple) ? "onclick" : "ondblclick";
    var wordsList = keys.join(",");
    //-------------------------------------
    var extra = `${click}="quiz_deleteValue('${id}');"`;
    var listItems = getHtmlListbox(name, id, keys, keys.length, -1, currentQuestion.numbering, 0, extra);
    var html = tblHtml.replace('{listeItems}', listItems);
    return html;
 }
 
  
/* *************************************
*
* ******** */
prepareData(){
var tItems = [];
    var currentQuestion = this.question;
        
     for (var k=0; k < 1; k++){
       var tw = currentQuestion.answers[k].proposition.split(",");  
       var tp = padStr2Array(currentQuestion.answers[k].points, tw.length);    
       currentQuestion.answers[k].words = tw;  
       
       for (var h=0; h < tw.length; h++){
           tItems[tw[h]] = tp[h]*1;
       }
     }
     
     this.data.items = tItems;
     //this.shuffleArrayKeys();  
     currentQuestion.answers[0].keys = Object.keys(tItems);  
    
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
var points = 0;
var bolOk = true;

    var currentQuestion = this.question;
    var id = `${this.getName()}-1`;
    
    var listObj = document.getElementById(id);
    var tItems = this.data.items;
    var keys = Object.keys(tItems);
//alert ('quiz_MoveToTop -> ' + listObj.name + ' -> '  + where);

        console.log("====================================");
    var options = listObj.getElementsByTagName("OPTION");
    for (var i = 0; i < options.length ; i++) {
        console.log("===> getScore-listSortItems : " + options[i].text + " == " + keys[i] + " => " + tItems[keys[i]]);
        if (options[i].text == keys[i]) 
            points += tItems[keys[i]]*1;
    }
       
      return points;

  }

  
/* *************************************
*
* ******** */
isInputOk ( answerContainer){
    var currentQuestion = this.question;

var bolOk = true;

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



      return bolOk;

 }

  
/* *************************************
*
* ******** */

getAllReponses (flag = 0){
    var currentQuestion = this.question;
    
    var tReponses = [];
    var tItems = this.data.items;
    
    
    //tri desc sur le tableau
    tItems = sortArrayKey(tItems,"d");
    
     
    for(var key in tItems)
    {
        //tReponses.push (`${key} ===> ${tItems[key]} points`) ;       
        tReponses.push ([key, tItems[key]]) ;       
    }

    return formatArray0(sortArrayArray(tReponses, 1, "DESC"), "=>");
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

    var tItems = this.shuffleArrayKeys(this.data.items);
    for(var key in tItems)
    {
        console.log(key + " = " +  tItems[key]);
        var option = document.createElement("option");
        option.text = key;
        option.value = key;
        ob.add(option);
    }

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


    var tItems = this.shuffleArrayKeys(this.data.items);
    for(var key in tItems)
    {
    //alert(`showGoodAnswers - ${key} = ${tItems[key]}`);
        console.log(key + " = " +  tItems[key]);
        if ((tItems[key]*1) <= 0) {
          var option = document.createElement("option");
          option.text = key;
          option.value = key;
          ob.add(option);
        }
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


    var tItems = this.shuffleArrayKeys(this.data.items);
    for(var key in tItems)
    {
        console.log(key + " = " +  tItems[key]);
        if ((tItems[key]*1) > 0) {
          var option = document.createElement("option");
          option.text = key;
          option.value = key;
          ob.add(option);
        }
    }
}
  
 
  
 
} // ----- fin de la classe ------
