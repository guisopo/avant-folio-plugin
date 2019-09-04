(function($) {

class avantFolioMediaUploader   {

  constructor() {

    this.plugin = document.getElementById('avant_folio_gallery');
    this.galleryList = document.getElementById('avant_folio_gallery_list');
    this.addImagesButton = document.getElementById('avant_folio_gallery_add_images');
    this.removeImageButton = document.querySelectorAll('.js-avant-folio-gallery-remove-image');
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
    this.makeGallerySortable();
  }

  bindAll() {
    ['renderMediaUploader', 'deleteImage']
      .forEach( fn => this[fn] = this[fn].bind(this));

    console.log('Bind All');
  }

  addEvents() {
    this.addImagesButton.addEventListener('click', this.renderMediaUploader);
    this.removeImageButton.forEach( button => button.addEventListener('click', this.deleteImage));
    console.log('Add events');
  }


  setInputValue() {
    let inputValue = [];
    this.selectedImages.forEach(image => inputValue.push(image));

    this.galleryHiddenInput.setAttribute('value', inputValue);

    console.log(`Set Input Value: ${this.galleryHiddenInput.value || null}`);
  }

  createInput() {
    this.galleryHiddenInput = document.createElement('input');

    this.galleryHiddenInput.setAttribute('type', 'hidden');
    this.galleryHiddenInput.setAttribute('id', 'avant_folio_work_gallery');
    this.galleryHiddenInput.setAttribute('name', 'avant_folio_work_info[gallery]');

    this.plugin.appendChild(this.galleryHiddenInput);

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
        this.createImagesList(imageData);
        this.selectedImages.push(imageData.id);
      });
      this.setInputValue();
      this.renderImages();
    });

    file_frame.open();
  }

  createImagesList(imageData) {

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
    console.log(this.selectedImages);
  }

  deleteImage(e) {
    const imageToDelete = e.target.parentNode.parentNode;
    imageToDelete.classList.add('hidden');
    imageToDelete.remove();

    console.log(e.target, imageToDelete);

    this.selectedImages = this.selectedImages.filter( image => image !== imageToDelete.dataset.id);

    console.log(`Click:  %cImage removed ${e.target.dataset.id}`, 'color: red');

    this.setInputValue();
  }

  makeGallerySortable() {
    $('#avant_folio_gallery_list').sortable();
    $('#avant_folio_gallery_list').on( "sortupdate", (events, ui) => {
      console.log('hola');
    });
  }
}

const gallery = new avantFolioMediaUploader();

})(jQuery);