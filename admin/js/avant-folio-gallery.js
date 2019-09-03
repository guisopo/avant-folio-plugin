console.log('Avant-Folio-Gallery');

class mediaUploader   {

  constructor() {

    this.addImagesButton = document.getElementById('js-add-images');
    this.galleryContainer = document.getElementById('js-gallery-container');

    this.selectedImages = [];
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

    let file_frame;

    if ( file_frame !== undefined ) {
      file_frame.open();
      return;
    }

    file_frame = wp.media.frames.file_frame = wp.media({
      frame:  'select',
      title: 'Select Work Images',
      multiple: true,
      library: {
        order: 'ASC',
        orderby: 'title',
        type: 'image',
        search: null,
        uploadedTo: null
      },
      button: {
        text: 'Publish Images'
      }
    });

    console.log('Click: Render Media Uploader');

    file_frame.on( 'select', () => {
      const json = file_frame.state().get('selection').toJSON();

      json.forEach(imageData => {
        if ( 0 > imageData.url.trim() ) {
          return;
        }
        this.createImage(imageData);
      });

      this.renderImages();
      this.setInputValue();
    });

    file_frame.open();
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

    console.log(`%cImage ${imageData.id} created`, 'color:green');
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

  setInputValue() {
    let inputValue = [];
    this.selectedImages.forEach(image => inputValue.push(image.dataset.id));

    this.galleryHiddenInput.setAttribute('value', inputValue);

    console.log(`Set Input Value: ${this.galleryHiddenInput.value || null}`);
  }

  removeImage(e) {
    e.target.classList.add('hidden');
    e.target.remove();

    this.selectedImages = this.selectedImages.filter( image => image !== e.target);

    console.log(`Click:  %cImage removed ${e.target.dataset.id}`, 'color: red');

    this.setInputValue();
  }

  addEvents() {
    this.addImagesButton.addEventListener('click', this.renderMediaUploader);
    
    console.log('Add events');
  }

  init() {
    console.log('Init');

    this.bindAll();
    this.addEvents();
    this.createInput();
  }
}

const gallery = new mediaUploader();