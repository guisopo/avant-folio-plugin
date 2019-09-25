(function($) {

class avantFolioMediaUploader   {

  constructor() {

    this.plugin = document.getElementById('af-gallery');
    this.galleryList = document.getElementById('af-gallery__list');
    this.addImagesButton = document.getElementById('js-af-button-add_image');
    this.removeImageButtons;
    this.setFeaturedImageButtons;

    this.galleryHiddenInput;
    this.featuredImageHiddenInput;
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
    ['renderMediaUploader', 'deleteImage', 'setFeatureImage']
      .forEach( fn => this[fn] = this[fn].bind(this));
  }

  addEvents() {
    // Media Button
    this.addImagesButton.addEventListener('click', this.renderMediaUploader);
    // Delete Button
    this.removeImageButtons = document.querySelectorAll('.js-af-button-remove_image');
    this.removeImageButtons.forEach( button => button.addEventListener('click', this.deleteImage));
    // Set Featured Image Button
    this.setFeaturedImageButtons = document.querySelectorAll('.js-af-button-set_featured_image');
    this.setFeaturedImageButtons.forEach( button => button.addEventListener('click', this.setFeatureImage));
  }

  setInitialState() {
    const initialImages = Array.from(this.galleryList.children);

    initialImages.forEach( image => {
      if( image.dataset.id !== undefined ) {
        this.selectedImages.push(parseInt(image.dataset.id))
      }
    });

    this.setInputValue();
    this.addEvents();
  }

  setInputValue() {
    this.galleryHiddenInput.value = this.selectedImages;
  }

  setFeaturedImage(id) {
    this.featuredImageHiddenInput.value = id;
  }

  setFeatureImage(e) {
    const featuredImage = e.target.parentNode.parentNode;

    // Prevents from selecting ul as a target.
    if(featuredImage.parentNode !== this.galleryList) {
      return;
    }

    const previousSelected = document.querySelector('.featured-image');

    if(previousSelected) { 
      previousSelected.classList.remove('featured-image'); 
    }

    featuredImage.classList.add('featured-image');
    
    const id = featuredImage.dataset.id;
    this.setFeaturedImage(id);
  }
  
  deleteImage(e) {
    const imageToDelete = e.target.parentNode.parentNode;

    // Prevents from selecting ul as a target and deleting it.
    if(imageToDelete.parentNode !== this.galleryList) {
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

    this.featuredImageHiddenInput = document.createElement('input');

    this.featuredImageHiddenInput.setAttribute('type', 'hidden');
    this.featuredImageHiddenInput.setAttribute('id', 'avant_folio_featured_image');
    this.featuredImageHiddenInput.setAttribute('name', 'avant_folio_work_info[featured_image]');

    this.plugin.appendChild(this.featuredImageHiddenInput);
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
      <li tabindex="0" role="checkbox" aria-label="${imageData.title}" aria-checked="true" data-id="${imageData.id}" class="af-gallery__item attachment save-ready selected details">
        <div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait">
          <div class="thumbnail">
            <div class="centered">
              <img src="${imageData.sizes.thumbnail.url}" draggable="false" alt="${imageData.caption}"/>
            </div>
          </div>
        </div>
      
      <button type="button" class="button-link check js-af-button-set_featured_image" tabindex="0">
        <span class="dashicons dashicons-star-filled"></span>
        <span class="screen-reader-text">Select Featured</span>
      </button>
      <button type="button" class="button-link check asap-image-remove js-af-button-remove_image" tabindex="0">
        <span class="media-modal-icon"></span>
        <span class="screen-reader-text">Deselect</span>
      </button>
      
    </li>`;

    return output;
  }

  renderImage(image) {
    this.galleryList.lastElementChild.insertAdjacentHTML( 'beforebegin', image );
  }

  makeGallerySortable() {
    $(this.plugin).sortable({
      cancel: '.unsortable',
      items: 'li',
      start: function () {
        $('.unsortable', this).each(function () {
            const $this = $(this);
            $this.data('pos', $this.index());
        });
      },
      change: function () {
        const $sortable = $(this);
        const $statics = $('.unsortable', this).detach();
        const tagName = $statics.prop('tagName');
        const $helper = $('<' + tagName + '/>').prependTo(this);
        $statics.each(function () {
          const $this = $(this);
          const target = $this.data('pos');

          $this.insertAfter($('li', $sortable).eq(target));
        });
        $helper.remove();
      }
    });
    
    $(this.plugin).on( "sortupdate", (event, ui) => {
      this.selectedImages = [];
      const images = Array.from(this.galleryList.children);

      images.forEach(image => {
        if(image.dataset.id !== undefined) {
          this.selectedImages.push(parseInt(image.dataset.id));
        }
      });

      this.setInputValue();
    });
  }
}

const gallery = new avantFolioMediaUploader();

})(jQuery);