

// Credit: Mateusz Rybczonec

const FULL_DASH_ARRAY = 283;
const WARNING_THRESHOLD = 10;
const ALERT_THRESHOLD = 5;

const COLOR_CODES = {
  info: {
    color: "green"
  },
  warning: {
    color: "orange",
    threshold: WARNING_THRESHOLD
  },
  alert: {
    color: "red",
    threshold: ALERT_THRESHOLD
  }
};

var timer = {
bgColor : "#ffffff",
limit : 20,
passed : 0,
left : 20, // .limit
interval : null,
isRunning : false,
remainingPathColor : COLOR_CODES.info.color
};

const bgColor = "#ffffff";

let TIME_LIMIT = 20;
let timePassed = 0;
let timeLeft = TIME_LIMIT;
let timerInterval = null;
let timerIsRunning = false;
let remainingPathColor = COLOR_CODES.info.color;

function build_timer(){
console.log('build_timer');
    document.getElementById("app").innerHTML = build_timer_html();
}

function build_timer_html(){
console.log('build_timer_html');
var html = `<div id='chronometre' class="base-timer">
  <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <g class="base-timer__circle">
      <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
      <path
        id="base-timer-path-remaining"
        fill=${bgColor}
        stroke-dasharray="283"
        class="base-timer__path-remaining ${remainingPathColor}"
        d="
          M 50, 50
          m -45, 0
          a 45,45 0 1,0 90,0
          a 45,45 0 1,0 -90,0
        "
      ></path>
    </g>
  </svg>
  <span id="base-timer-label" class="base-timer__label">${formatTime(
    timeLeft
  )}</span>
</div>
`;
return html;
}

//startTimer();

function onTimesUp() {
console.log('onTimesUp');

  clearInterval(timerInterval);
  timerIsRunning = false;
  //alert("termine");
  document.getElementById('chronometre').style.display ='none';
  
  // à voir si remplace le timer sur le bouton suivant
  //document.getElementById('quiz_btn_nextSlide').click();

}

/**************************************************************************
 *  Affiche le bouton 'consignes'
 *  mis à par pour envisager un placement personaliser, à voir
 * ************************************************************************/
function initTimer_for_quiz(position, divSize, fontSize){
console.log('initTimer_for_quiz');
 // voir la classe quiz_infobulle

    if(timerIsRunning) return false;

var divSizePix = divSize + 'px';
    
    //document.getElementById('app').setAttribute('display','block');
    
    var obTimer = document.getElementById('chronometre');
    console.log(obTimer.id + "-" + obTimer.getAttribute('width'));
    obTimer.style.width  = divSizePix;
    obTimer.style.height = divSizePix;
    //obTimer.style.display ='block';
    //obTimer.style.top='';
    switch(position){  
    case 0: return '';        
    case 2:  obTimer.style.top    = "+200px"; obTimer.style.right  = "5px"; break;    // Top/Right
    case 3:  obTimer.style.bottom =   "+5px"; obTimer.style.right  = "5px"; break;    // Bottom/Right
    case 4:  obTimer.style.bottom =   "+5px"; obTimer.style.left   = "5px"; break;    // Bottom/Left
    default: obTimer.style.top    = "+200px"; obTimer.style.left   = "5px"; break;    // Top/Left
    }                                                                                                                //
    
    var obLabel = document.getElementById('base-timer-label');
    obLabel.style.width=divSizePix;
    obLabel.style.height=divSizePix;
    obLabel.style.color='red';
    //obLabel.setAttribute('fontSize', '8px');    
    obLabel.style.fontSize = fontSize + 'px';    
    //obLabel.style.background='yellow';


    
}

function initTimer(divSize, divLeft, divTop, fontSize){
console.log('initTimer');
//if(document.getElementById('app').innerHTML=='') build_timer_html();
    if(timerIsRunning) return false;

var divSizePix = divSize + 'px';
    
    //document.getElementById('app').setAttribute('display','block');
    
    var obTimer = document.getElementById('chronometre');
    console.log(obTimer.id + "-" + obTimer.getAttribute('width'));
    obTimer.style.width  = divSizePix;
    obTimer.style.height = divSizePix;
    obTimer.style.left   = divLeft + 'px';
    obTimer.style.top    = divTop + 'px';
    obTimer.style.display ='block';
    
    var obLabel = document.getElementById('base-timer-label');
    obLabel.style.width=divSizePix;
    obLabel.style.height=divSizePix;
    obLabel.style.color='red';
    //obLabel.setAttribute('fontSize', '8px');    
    obLabel.style.fontSize = fontSize + 'px';    
    //obLabel.style.background='yellow';


}
function razTimer(chrono) {
console.log('razTimer');
    if(timerIsRunning) return false;
TIME_LIMIT = chrono;
timePassed = 0;
timeLeft = TIME_LIMIT;
timerInterval = null;

    document.getElementById("base-timer-label").innerHTML = formatTime(chrono);
 //setRemainingPathColor(timeLeft);  
//alert('initTimer');

    document
      .getElementById("base-timer-path-remaining")
      .classList.remove(COLOR_CODES.alert.color);
    document
      .getElementById("base-timer-path-remaining")
      .classList.remove(COLOR_CODES.warning.color);
    document
      .getElementById("base-timer-path-remaining")
      .classList.add(COLOR_CODES.info.color);
}

function startChronometre(chrono) {
console.log('startChronometre');
    if(timerIsRunning) return false;
     razTimer(chrono);
    timerIsRunning = true;
    document.getElementById('chronometre').style.display ='block';;
    //--------------------------------------------------
  timerInterval = setInterval(() => {
    timePassed = timePassed += 1;
    timeLeft = TIME_LIMIT - timePassed;
    document.getElementById("base-timer-label").innerHTML = formatTime(
      timeLeft
    );
    setCircleDasharray();
    setRemainingPathColor(timeLeft);

    //if (timeLeft < 0) {
    if (timeLeft === 0) {
      onTimesUp();
    }
  }, 1000);
}

function formatTime(time) {
console.log('formatTime');
  const minutes = Math.floor(time / 60);
  let seconds = time % 60;

  if (seconds < 10) {
    seconds = `0${seconds}`;
  }

  return `${minutes}:${seconds}`;
}

function setRemainingPathColor(timeLeft) {
console.log('setRemainingPathColor');
  const { alert, warning, info } = COLOR_CODES;
  if (timeLeft <= alert.threshold) {
    document
      .getElementById("base-timer-path-remaining")
      .classList.remove(warning.color);
    document
      .getElementById("base-timer-path-remaining")
      .classList.add(alert.color);
  } else if (timeLeft <= warning.threshold) {
    document
      .getElementById("base-timer-path-remaining")
      .classList.remove(info.color);
    document
      .getElementById("base-timer-path-remaining")
      .classList.add(warning.color);
  }
}

function calculateTimeFraction() {
console.log('calculateTimeFraction');
  const rawTimeFraction = timeLeft / TIME_LIMIT;
  return rawTimeFraction - (1 / TIME_LIMIT) * (1 - rawTimeFraction);
}

function setCircleDasharray() {
console.log('setCircleDasharray');
  const circleDasharray = `${(
    calculateTimeFraction() * FULL_DASH_ARRAY
  ).toFixed(0)} 283`;
  document
    .getElementById("base-timer-path-remaining")
    .setAttribute("stroke-dasharray", circleDasharray);
}


