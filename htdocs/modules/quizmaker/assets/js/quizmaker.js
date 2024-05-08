 
document.onkeydown = quizmaker_applyKey;


/*********************************************
 *  imprime et ferme la fenetre
 *********************************************/ 
function quizmaker_printDoc(closeAfterPrint){

    window.print();
    if (closeAfterPrint==true)   window.close();
	return false;
}

/*********************************************
 *   
 *********************************************/ 
function quizmaker_closeDoc(){
    window.close();
	return false;
}

/*********************************************
 *   
 *********************************************/ 
function quizmaker_applyKey (_event_){
	// --- Retrieve event object from current web explorer
	var winObj = quizmaker_checkEventObj(_event_);
	
	var intKeyCode = winObj.keyCode;
	var intAltKey = winObj.altKey;
	var intCtrlKey = winObj.ctrlKey;
// alert("key : " + intKeyCode);	
// alert("intCtrlKey : " + intCtrlKey);	
  
  switch(intKeyCode){
    case 27:
    case 13:
      window.close();
      break;
    case 80:
    case 112:
      if(intCtrlKey) quizmaker_printDoc(true);
      break;
  }
}

/*********************************************
 *   
 *********************************************/ 
function quizmaker_checkEventObj ( _event_ ){
	// --- IE explorer
	if ( window.event )
		return window.event;
	// --- Netscape and other explorers
	else
		return _event_;
}

