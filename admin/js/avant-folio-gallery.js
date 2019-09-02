console.log('Avant-Folio-Gallery');

class mediaUploader   {

  constructor(id) {

    console.log('constructed');

    this.addImagesButton = document.getElementById(id);
    this.galleryContainer = document.getElementById('js-gallery-container');

    this.file_frame;
    this.image_data;
    this.json;

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
        frame:  'post',
        state:  'insert',
        multiple: true
    });

    this.file_frame.on( 'insert', () => {
      this.json = this.file_frame.state().get('selection').toJSON();
      
      this.createImages();

      this.galleryContainer.classList.remove('hidden');
      this.addImagesButton.classList.add('hidden');
    });

    this.file_frame.open();
  }

  createImages() {
    
    this.json.forEach( imageData => {
      if ( 0 > imageData.url.trim() ) {
        return;
      }
      // Create Image
      const image = document.createElement('img');
      image.setAttribute('src', imageData.url);
      image.setAttribute('alt', imageData.caption);
      image.setAttribute('title', imageData.title);
      this.galleryContainer.appendChild(image);
    });
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