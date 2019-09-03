console.log('Avant-Folio-Gallery');

class mediaUploader   {

  constructor() {

    this.file_frame;
    this.image_data;
    this.json;

    this.addImagesButton = document.getElementById('js-add-images');
    this.galleryContainer = document.getElementById('js-gallery-container');

    this.selectedImages = [];
    this.inputValue = [];
    this.galleryHiddenInput;

    console.log('Constructed');
    this.init();
  }

  bindAll() {
    ['renderMediaUploader', 'removeImage']
      .forEach( fn => this[fn] = this[fn].bind(this));

    console.log('Bind All');
  }

  renderMediaUploader(e) {
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

    console.log('Click: Render Media Uploader');

    this.file_frame.on( 'insert', () => {
      this.json = this.file_frame.state().get('selection').toJSON();

      this.json.forEach(imageData => {
        if ( 0 > imageData.url.trim() ) {
          return;
        }
        this.createImage(imageData);
      });
      this.renderImages();
      this.createInput();
      this.setInputValue();
    });

    this.file_frame.open();
  }

  createImage(imageData) {
    // Create Image
    const image = document.createElement('img');
    image.classList.add('work-image');
    image.setAttribute('src', imageData.url);
    image.setAttribute('alt', imageData.caption);
    image.setAttribute('title', imageData.title);
    image.setAttribute('data-id', imageData.id);

    this.selectedImages.push(image);
    // Add Event-Listener to Image
    image.addEventListener('click', this.removeImage);
    // Append to Gallery Container
    this.galleryContainer.appendChild(image);

    console.log(`Image ${imageData.id} created`);
  }

  renderImages() {
    this.galleryContainer.classList.remove('hidden');

    console.log('Show Images!');
  }

  createInput() {
    this.galleryHiddenInput = document.createElement('input');
    this.galleryHiddenInput.setAttribute('type', 'hidden');
    this.galleryHiddenInput.setAttribute('id', 'avant_folio_work_gallery');
    this.galleryHiddenInput.setAttribute('name', 'avant_folio_work_info[gallery]');
    this.galleryContainer.appendChild(this.galleryHiddenInput);

    console.log('Hidden Meta Box Created');
  }

  removeInput() {
    this.galleryHiddenInput.remove();

    console.log(`Remove Input`)
  }

  setInputValue() {
    this.inputValue = [];
    this.selectedImages.forEach(image => this.inputValue.push(image.dataset.id));

    this.galleryHiddenInput.setAttribute('value', this.inputValue);

    console.log(`Set Input Value: ${this.galleryHiddenInput.value || null}`);
  }

  removeImage(e) {

    e.target.classList.add('hidden');
    e.target.remove();
    this.selectedImages = this.selectedImages.filter( image => image !== e.target);

    console.log('Click: Image removed');

    if( this.selectedImages.length > 0 ) {
      this.setInputValue();
    } else {
      this.setInputValue();
      this.removeInput();
    }
  }

  addEvents() {
    this.addImagesButton.addEventListener('click', this.renderMediaUploader);
    
    console.log('Add events');
  }

  init() {
    console.log('Init');

    this.bindAll();
    this.addEvents();
  }
}

const gallery = new mediaUploader();