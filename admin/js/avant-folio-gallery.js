console.log('Avant-Folio-Gallery');

class mediaUploader   {

  constructor() {
    console.log('constructed');

    this.file_frame;
    this.image_data;
    this.json;

    this.addImagesButton = document.getElementById('js-add-images');
    this.removeImagesButton = document.getElementById('js-remove-images');
    this.galleryContainer = document.getElementById('js-gallery-container');
    this.selectedImages = [];

    this.init();
  }

  bindAll() {
    console.log('bindAll');

    ['renderMediaUploader', 'removeImage']
      .forEach( fn => this[fn] = this[fn].bind(this));
  }

  renderMediaUploader(e) {
    console.log('Click: Render Media Uploader!');
    
    e.preventDefault();

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
      this.renderImages();
    });

    this.file_frame.open();
  }

  createImages() {
    
    this.json.forEach( imageData => {
      console.log('Image Created');
      if ( 0 > imageData.url.trim() ) {
        return;
      }
      // Create Image
      const image = document.createElement('img');
      image.classList.add('work-image');
      image.setAttribute('src', imageData.url);
      image.setAttribute('alt', imageData.caption);
      image.setAttribute('title', imageData.title);
      
      image.addEventListener('click', this.removeImage);

      this.galleryContainer.appendChild(image);
      this.selectedImages.push(image);
    });
  }

  renderImages() {
    console.log('Show Images!');

    this.galleryContainer.classList.remove('hidden');
  }

  removeImage(e) {
    console.log('Image removed');

    e.preventDefault();

    e.target.classList.add('hidden');

    this.selectedImages = this.selectedImages.filter( image => image !== e.target);
  }

  addEvents() {
    console.log('add events');

    this.addImagesButton.addEventListener('click', this.renderMediaUploader);
    this.removeImagesButton.addEventListener('click', this.removeImage);
  }

  init() {
    console.log('init');

    this.bindAll();
    this.addEvents();
  }
}

const gallery = new mediaUploader();