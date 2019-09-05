(function($) {

class avantFolioMediaUploader   {

  constructor() {

    this.plugin = document.getElementById('avant_folio_gallery');
    this.galleryList = document.getElementById('avant_folio_gallery_list');
    this.addImagesButton = document.getElementById('avant_folio_gallery_add_images');
    
    this.galleryHiddenInput;
    this.selectedImages = [];

    this.init();
  }

  init() {
    this.bindAll();
    this.createInput();
    this.setInitialState();
    this.makeGallerySortable();
  }

  bindAll() {
    ['renderMediaUploader', 'deleteImage']
      .forEach( fn => this[fn] = this[fn].bind(this));
  }

  addEvents() {
    // Media Button
    this.addImagesButton.addEventListener('click', this.renderMediaUploader);
    // Delete Button
    const removeImageButtons = document.querySelectorAll('.js-avant-folio-gallery-remove-image');
    removeImageButtons.forEach( button => button.addEventListener('click', this.deleteImage));
  }

  setInitialState() {
    const initialImages = Array.from(this.galleryList.children);

    initialImages.forEach( image => this.selectedImages.push(parseInt(image.dataset.id)));

    this.setInputValue();
    this.addEvents();
  }

  setInputValue() {
    this.galleryHiddenInput.value = this.selectedImages;
  }
  
  deleteImage(e) {
    const imageToDelete = e.target.parentNode.parentNode;

    if(!imageToDelete.classList.contains('avant-folio-list-item')) {
      return;
    }

    imageToDelete.classList.add('hidden');
    imageToDelete.remove();

    this.selectedImages = this.selectedImages.filter( image => image !== parseInt(imageToDelete.dataset.id));
    this.setInputValue();
  }

  createInput() {
    this.galleryHiddenInput = document.createElement('input');

    this.galleryHiddenInput.setAttribute('type', 'hidden');
    this.galleryHiddenInput.setAttribute('id', 'avant_folio_work_gallery');
    this.galleryHiddenInput.setAttribute('name', 'avant_folio_work_info[gallery]');

    this.plugin.appendChild(this.galleryHiddenInput);
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
        orderby: 'date',
        type: 'image',
        search: null,
        uploadedTo: null
      },
      button: {
        text: 'Publish Images'
      }
    });

    file_frame.on( 'select', () => {
      const json = file_frame.state().get('selection').toJSON();

      json.forEach(imageData => {
        if ( 0 > imageData.url.trim() ) {
          return;
        }

        if ( this.selectedImages.includes(imageData.id) ) {
          return
        }

        this.selectedImages.push(imageData.id);

        const image = this.createImageListItem(imageData);
        this.renderImage(image);
      });

      this.addEvents();
      this.setInputValue();
    });

    file_frame.open();
  }

  createImageListItem(imageData) {
    let output = `
      <li tabindex="0" role="checkbox" aria-label="${imageData.title}" aria-checked="true" data-id="${imageData.id}" class="avant-folio-list-item attachment save-ready selected details">
        <div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait">
          <div class="thumbnail">
            <div class="centered">
              <img src="${imageData.sizes.thumbnail.url}" draggable="false" alt="${imageData.caption}"/>
            </div>
          </div>
        </div>
      
      <button type="button" class="button-link check asap-image-remove js-avant-folio-gallery-remove-image" tabindex="0">
        <span class="media-modal-icon"></span>
        <span class="screen-reader-text">Deselect</span>
      </button>
      
    </li>`;

    return output;
  }

  renderImage(image) {
    this.galleryList.insertAdjacentHTML( 'beforeend', image);
  }

  makeGallerySortable() {
    $('#avant_folio_gallery_list').sortable();
    
    $('#avant_folio_gallery_list').on( "sortupdate", (event, ui) => {
      this.selectedImages = [];
      const images = Array.from(this.galleryList.children);

      images.forEach(image => {
        this.selectedImages.push(parseInt(image.dataset.id));
      });

      this.setInputValue();
    });
  }
}

const gallery = new avantFolioMediaUploader();

})(jQuery);