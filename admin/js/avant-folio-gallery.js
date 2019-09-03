console.log('Avant-Folio-Gallery');

class mediaUploader   {

  constructor() {

    this.addImagesButton = document.getElementById('avant_folio_gallery_add_images');
    this.galleryList = document.getElementById('avant_folio_gallery_list');
    this.galleryHiddenInput;

    this.imagesListItems= '';
    this.selectedImages = [];

    console.log('Constructed');
    this.init();
  }

  init() {
    console.log('Init');

    this.bindAll();
    this.addEvents();
    this.createInput();
  }

  bindAll() {
    ['renderMediaUploader', 'removeImage']
      .forEach( fn => this[fn] = this[fn].bind(this));

    console.log('Bind All');
  }

  addEvents() {
    this.addImagesButton.addEventListener('click', this.renderMediaUploader);
    
    console.log('Add events');
  }


  setInputValue() {
    let inputValue = [];
    this.selectedImages.forEach(image => inputValue.push(image.id));

    this.galleryHiddenInput.setAttribute('value', inputValue);

    console.log(`Set Input Value: ${this.galleryHiddenInput.value || null}`);
  }

  createInput() {
    this.galleryHiddenInput = document.createElement('input');

    this.galleryHiddenInput.setAttribute('type', 'hidden');
    this.galleryHiddenInput.setAttribute('id', 'avant_folio_work_gallery');
    this.galleryHiddenInput.setAttribute('name', 'avant_folio_work_info[gallery]');

    this.galleryList.appendChild(this.galleryHiddenInput);

    console.log('Hidden Meta Box Created');
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
        this.selectedImages.push(imageData);
      });
      this.setInputValue();
      this.renderImages();
    });

    file_frame.open();
  }

  createImage(imageData) {

    let output = `
      <li tabindex="0" role="checkbox" aria-label="${imageData.title}" aria-checked="true" data-id="${imageData.id}" class="attachment save-ready selected details">
        <div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait">
          <div class="thumbnail">
            <div class="centered">
              <img src="${imageData.sizes.thumbnail.url}" draggable="false" alt="${imageData.caption}"/>
            </div>
          </div>
        </div>
      
      <button type="button" class="button-link check asap-image-remove" tabindex="0">
        <span class="media-modal-icon"></span>
        <span class="screen-reader-text">Deselect</span>
      </button>
      
    </li>`;

    this.imagesListItems += output;
  }

  renderImages() {
    this.galleryList.innerHTML = this.imagesListItems;
  }

  removeImage(e) {
    e.target.classList.add('hidden');
    e.target.remove();

    this.selectedImages = this.selectedImages.filter( image => image !== e.target);

    console.log(`Click:  %cImage removed ${e.target.dataset.id}`, 'color: red');

    this.setInputValue();
  }
}

const gallery = new mediaUploader();