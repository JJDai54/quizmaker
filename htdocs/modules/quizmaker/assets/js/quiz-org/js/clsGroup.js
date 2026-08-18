
/*********************************************
 * ================ class clsGridImg =========
 * cette class est utilisé par les plugins qui gèrent des groupe
 * elle n'est constituée que de function statique pour muatilisé le code
 * imagesDaDGroups, listboxClassItems, Plugin_ulDaDGroups, ...
 * 
 * *******************************************/

class clsGroup {


/* ************************************

 constructor() {
 }
*************************************** */

/* ************************************

*************************************** */
static repartir(clPlugin, bShuffle = true){
    var currentQuestion = clPlugin.question;
    var options = currentQuestion.options;
    options.groupDefault = options.groupDefault*1;
    var ansGroup = 0;
    var groupTo = 0; //groupe ou seront affiché les réponses (image ou texte)
    //recupere la liste des groupes
    //this.data.groupsLib = thisGetGroups(options);
    var groups = clsGroup.getGroups(clPlugin, options);
    
    var pMinMax = {pMax : 0, pMin : 0};
    
    //conversion de string en int
    for(var h = 0; h< currentQuestion.answers.length; h++){
        currentQuestion.answers[h].points = currentQuestion.answers[h].points*1;
        currentQuestion.answers[h].group  = currentQuestion.answers[h].group*1;
    }
    //-----------------------------------------
    if(options.groupDefault >= 0 && bShuffle){
        //on met tous le monde dans le même groupe par defaut
        for(var h = 0; h< currentQuestion.answers.length; h++){
            groups[options.groupDefault].ansArr.push(currentQuestion.answers[h]);
            if(currentQuestion.answers[h].group != options.groupDefault){
                pMinMax.pMax += currentQuestion.answers[h].points;
            }
        }
    }else{
        for(var h = 0; h< currentQuestion.answers.length; h++){
            ansGroup = currentQuestion.answers[h].group;
            if(bShuffle){
                groupTo = getRandom(options.nbGroups-1);
                while (ansGroup == groupTo){
                    groupTo = getRandom(options.nbGroups-1);
                }
            }else{
                groupTo = ansGroup;
            }
            //alert(groups[groupTo].index);
            groups[groupTo].ansArr.push(currentQuestion.answers[h]);
            pMinMax.pMax += currentQuestion.answers[h].points;
        }
        
    }
    
    clPlugin.scoreMaxiQQ = pMinMax.pMax;
    clPlugin.scoreMiniQQ = pMinMax.pMin;
    options.nbGroups = groups.length;
    clPlugin.data.nbGroups = groups.length
    
    return groups;

}

/* ************************************

*************************************** */
static getGroups(clPlugin, options){
    var k = 0;
    var groupArr = [];
    var itemArr = {};
    while(true){
        var libKey = 'group' + k;
        var bgKey = 'bgGroup' + k;
        if(!options[libKey]) {break;}
        itemArr = {groupNum: k, id: clPlugin.getId('group', k), caption: options[libKey], background: options[bgKey], ansArr:[]};
        groupArr.push(itemArr);
        k++;
    }    

    options.nbGroups = groupArr.length;
    return groupArr;
}


} // ------------- FIN DE La CLASSE

