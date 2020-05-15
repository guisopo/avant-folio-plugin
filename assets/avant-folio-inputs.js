(function($) {
  
  class inputValueCopier {
    constructor( inputID, input2ID) {
      this.input = document.getElementById(inputID);
      this.input2 = document.getElementById(input2ID);
      
      this.addEvents();
    }
  
    addEvents() {
      document.addEventListener('keyup', () => this.copyTitle());
    }

    copyTitle() {
      this.input.value = this.input2.value;
    }
  }

  document.addEventListener("DOMContentLoaded", () => new inputValueCopier('workTitle', 'title'));
  
})(jQuery);