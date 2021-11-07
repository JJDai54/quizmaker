 /*******************************************************************
  *                     _textareaInput
  * *****************************************************************/

function getClass(className){
    switch(className){
    /*
    case "zzz": obj = zzz; break;
    case "yyy": obj = yyy; break;
    case "xxx": obj = xxx; break;
    */
    case "zzz": obj = new zzz; break;
    case "yyy": obj = new yyy; break;
    case "xxx": obj = new xxx; break;
    
    }
    return obj;
}


class zzz{
 name ="zzz";

    build (exp){
      alert(this.name + " : " + exp);
      return true;
  }
}

class yyy{
 name ="yyy";

    build (exp){
      alert(this.name + " : " + exp);
      return true;
  }
}

class xxx{
 name ="xxx";

    build (exp){
      alert(this.name + " : " + exp);
      return true;
  }
}