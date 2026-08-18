// Credit: Mateusz Rybczonec
/*
un peu de culture :
Chronos (grec ancien : ??????) est la personnification du temps 
qui apparaît principalement dans les traditions orphiques 
qui le considèrent comme le fils de Gaïa (la Terre) et d'Hydros (Eaux primordiales)
*/


const FULL_DASH_ARRAY = 283;
const WARNING_THRESHOLD = 10;
const ALERT_THRESHOLD = 5;

const _AP_QUIZMAKER_COLOR_CODES = {
  info: { color: "green" },
  warning: { color: "orange", threshold: WARNING_THRESHOLD },
  alert: { color: "red", threshold: ALERT_THRESHOLD }
};

export class ChronosComponent extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({ mode: 'open' });
    this.timeLimit = parseInt(this.getAttribute('time-limit')) || 20;
    this.timeLeft = this.timeLimit;
    this.timePassed = 0;
    this.timerInterval = null;
    this.isRunning = false;
  }

  static get observedAttributes() {
    return ['position', 'size', 'time-limit', 'thickness', 'offset-x', 'offset-y', 'background', 'color'];
  }
  
  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue !== newValue && this.shadowRoot.innerHTML !== "") {
      this.render();
    }
  }

  connectedCallback() {
    this.render();
  }

  disconnectedCallback() {
    this.stop();
  }

  formatTime(time) {
    const minutes = Math.floor(time / 60);
    let seconds = time % 60;
    if (seconds < 10) seconds = `0${seconds}`;
    return `${minutes}:${seconds}`;
  }

  calculateTimeFraction() {
    const rawTimeFraction = this.timeLeft / this.timeLimit;
    return rawTimeFraction - (1 / this.timeLimit) * (1 - rawTimeFraction);
  }

  setCircleDasharray() {
    const circleDasharray = `${(this.calculateTimeFraction() * FULL_DASH_ARRAY).toFixed(0)} 283`;
    const pathRemaining = this.shadowRoot.getElementById("base-timer-path-remaining");
    if (pathRemaining) {
      pathRemaining.setAttribute("stroke-dasharray", circleDasharray);
    }
  }

  setRemainingPathColor(timeLeft) {
    const pathRemaining = this.shadowRoot.getElementById("base-timer-path-remaining");
    if (!pathRemaining) return;

    const { alert, warning, info } = _AP_QUIZMAKER_COLOR_CODES;
    pathRemaining.classList.remove(info.color, warning.color, alert.color);

    if (timeLeft <= alert.threshold) {
      pathRemaining.classList.add(alert.color);
    } else if (timeLeft <= warning.threshold) {
      pathRemaining.classList.add(warning.color);
    } else {
      pathRemaining.classList.add(info.color);
    }
  }

  onTimesUp() {
    clearInterval(this.timerInterval);
    this.isRunning = false;
    this.hide();
    
    this.dispatchEvent(new CustomEvent('times-up', { 
      bubbles: true, 
      composed: true,
      detail: { timeLeft: this.timeLeft }
    }));
  }

  start(chrono) {
    if (this.isRunning) return;

    if (chrono !== undefined && chrono !== null) {
      this.timeLimit = parseInt(chrono) || this.timeLimit;
    }
    this.timeLeft = this.timeLimit;
    this.timePassed = 0;
    this.show();
    this.render();

    this.isRunning = true;

    this.dispatchEvent(new CustomEvent('timer-start', { 
      bubbles: true, 
      composed: true,
      detail: { timeLimit: this.timeLimit }
    }));

    this.timerInterval = setInterval(() => {
      this.timePassed += 1;
      this.timeLeft = this.timeLimit - this.timePassed;
      
      const label = this.shadowRoot.getElementById("base-timer-label");
      if (label) {
        label.innerHTML = this.formatTime(this.timeLeft);
      }
      
      this.setCircleDasharray();
      this.setRemainingPathColor(this.timeLeft);

      if (this.timeLeft <= 0) {
        this.onTimesUp();
      }
    }, 1000);
  }

  stop() {
    clearInterval(this.timerInterval);
    this.isRunning = false;
  }
  
  restart(duree) {
    this.stop();
    this.timePassed = 0; 
    this.start(duree);
  }  
  
  reset(newLimit = this.timeLimit) {
    this.stop();
    this.timeLimit = newLimit;
    this.timeLeft = newLimit;
    this.timePassed = 0;
    this.show();
    this.render();
  }

  hide() {
    this.style.display = 'none';
  }

  show() {
    this.style.display = 'block';
  }

  render() {
    const size = parseInt(this.getAttribute('size')) || 48;
    const position = this.getAttribute('position') || 'top-left';
    const thickness = parseInt(this.getAttribute('thickness')) || 7;
    
    // Récupération des nouveaux attributs background et color avec valeurs par défaut
    const bgColor = this.getAttribute('background') || 'white';
    const textColor = this.getAttribute('color') || '#333';
    
    const defaultOffset = - (size / 2);
    const offsetX = parseInt(this.getAttribute('offset-x')) || defaultOffset;
    const offsetY = parseInt(this.getAttribute('offset-y')) || defaultOffset;
    
    let positionStyle = 'position: absolute; ';
    switch (position) {
      case 'top-right':
        positionStyle += `top: ${offsetY}px; right: ${offsetX}px;`;
        break;
      case 'bottom-left':
        positionStyle += `bottom: ${offsetY}px; left: ${offsetX}px;`;
        break;
      case 'bottom-right':
        positionStyle += `bottom: ${offsetY}px; right: ${offsetX}px;`;
        break;
      case 'top-left':
      default:
        positionStyle += `top: ${offsetY}px; left: ${offsetX}px;`;
        break;
    }
        
    this.shadowRoot.innerHTML = `
      <style>
        :host {
          ${positionStyle}
          z-index: 10;
        }
        .base-timer {
          position: relative;
          width: ${size}px;
          height: ${size}px;
          background-color: ${bgColor}; 
          border-radius: 50%;
          box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .base-timer__svg {
          transform: scaleX(-1);
        }
        .base-timer__circle {
          fill: none;
          stroke: none;
        }
        .base-timer__path-elapsed {
          stroke-width: ${thickness}px;
          stroke: #eee;
        }
        .base-timer__path-remaining {
          stroke-width: ${thickness}px;
          stroke-linecap: round;
          transform: rotate(90deg);
          transform-origin: center;
          transition: 1s linear all;
        }
        .green { stroke: green; }
        .orange { stroke: orange; }
        .red { stroke: red; }
        .base-timer__label {
          position: absolute;
          width: 100%;
          height: 100%;
          top: 0;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: ${Math.round(size * 0.3)}px;
          color: ${textColor};
          font-weight: bold;
        }
      </style>
      <div class="base-timer">
        <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <g class="base-timer__circle">
            <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
            <path
              id="base-timer-path-remaining"
              fill="transparent"
              stroke-dasharray="283"
              class="base-timer__path-remaining green"
              d="M 50, 50 m -45, 0 a 45,45 0 1,0 90,0 a 45,45 0 1,0 -90,0"
            ></path>
          </g>
        </svg>
        <span id="base-timer-label" class="base-timer__label">${this.formatTime(this.timeLeft)}</span>
      </div>
    `;
    this.setCircleDasharray();
  }
}

customElements.define('chronos-component', ChronosComponent);