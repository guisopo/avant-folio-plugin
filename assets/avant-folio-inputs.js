(function($) {
  
  class inputValueCopier {
    constructor(inputID, input2ID) {
      this.input = document.getElementById(inputID);
      this.input2 = document.getElementById(input2ID);
      
      this.addEvents();
    }
  
    addEvents() {
      if (this.input2.type === 'text') {
        this.input2.addEventListener('keyup', () => this.copyTitle());
      } else {
        this.input2.addEventListener('change', () => this.copyOption());
      }
    }

    copyTitle() {
      this.input.value = this.input2.value;
    }

    copyOption() {
      this.input.value = this.input2.options[this.input2.selectedIndex].text;
    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    new inputValueCopier('work_title_key', 'title');
    new inputValueCopier('work_type_key', 'work_type_select');
    new inputValueCopier('date_completed_key', 'date_completed');
  });
  
})(jQuery);