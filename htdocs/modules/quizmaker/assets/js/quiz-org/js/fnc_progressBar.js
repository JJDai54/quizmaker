/* ------------------------ */
/* ----- progress Bar ----- */
/* ------------------------ */
var pb = {
    maxWidth : 0,
    maxValue : 250,   // total à atteindre
    value : 0,  // valeur courante
    itv : 0  // id pour setinterval
};

function pb_stop()
{
    clearInterval(pb.itv);
    pb.itv = 0;
}

function pb_runAuto(maxValue, newValue = 0){
    pb_init(maxValue, newValue);
    pb_run();
}

function pb_run()
{
  if(pb.itv == 0){
    pb.itv = setInterval(pb_run, 100);
    pb.value=0;
    return;
  }

  if(pb.value >= pb.maxValue) 
  {
    pb_stop();   	
    return;
  }	
  pb.value += 1;	
  if(pb.value >= pb.maxValue) stop();   
  pb_showProgression();   
}

function pb_init(maxValue, newValue = 0)
{
  pb.maxValue = maxValue;
  pb.value = newValue;
  var obContenair = document.getElementById('pb_contenair');
  var obBase = document.getElementById('pb_base');
  var obText = document.getElementById('pb_text');
  
  pb.maxWidth = obContenair.offsetWidth - obText.offsetWidth - 12-120;
  obBase.style.width = pb.maxWidth + 'px';
  pb.value = newValue;
  pb_showProgression();   
}


function pb_increment()
{
  if(pb.value >= pb.maxValue) 
  {
    return;
  }	
  pb.value += 1;	
  pb_showProgression();
}
function pb_setValue(newValue)
{
  pb.value = newValue;	
  pb_showProgression();
}

function pb_showProgression()
{
  var obContenair = document.getElementById('pb_contenair');
  //var obBase = document.getElementById('pb_base');
  //obBase.style.width = pb.maxWidth + 'px';
  var obText = document.getElementById('pb_text');
  var obIndicator = document.getElementById('pb_indicator');
  
  var newWidth = pb.value / pb.maxValue * pb.maxWidth;
  obIndicator.style.width=newWidth + "px";
  obText.innerHTML = pb.value + ' / ' + pb.maxValue;
}

