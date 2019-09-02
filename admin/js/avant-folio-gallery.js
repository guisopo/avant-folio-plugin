console.log('Avant-Folio-Gallery');

class mediaUploader   {

  constructor(id) {
    console.log('constructed');

    this.addImagesButton = document.getElementById(id);
    

    this.file_frame;
    this.image_data;

    this.init();
  }

  bindAll() {
    console.log('bindAll');
    ['handleClick']
      .forEach( fn => this[fn] = this[fn].bind(this));
  }

  handleClick(e) {
    console.log('Added click');
    e.preventDefault();
  
    this.renderMediaUploader();
  }

  renderMediaUploader() {
    console.log('Render Media Uploader!');
    
    if ( this.file_frame !== undefined ) {
        this.file_frame.open();
        return;
    }

    this.file_frame = wp.media.frames.file_frame = wp.media({
        frame:    'post',
        state:    'insert',
        multiple: true
    });

    this.file_frame.on( 'insert', () => {
      this.file_frame.on( 'insert', () => {
        json = this.file_frame.state().get( 'selection' ).first().toJSON();
      });
    });

    this.file_frame.open();
  }


  addEvents() {
    console.log('add events');
    this.addImagesButton.addEventListener('click', this.handleClick);
  }

  init() {
    console.log('init');
    this.bindAll();
    this.addEvents();
  }
}

const gallery = new mediaUploader('js-add-images');









const renderMediaUploader = () => {
}

