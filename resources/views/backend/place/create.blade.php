<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Place') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="modalForm" class="modal-form create" action="{{ route('admin.places.store') }}"
              method="post" enctype="multipart/form-data">
          @csrf

          <div class="form-group">
            <label for="language_id">{{ __('Language') . '*' }}</label>
            <select id="language_id" name="language_id" class="form-control">
              <option selected disabled>{{ __('Select a Language') }}</option>
              @foreach ($langs as $lang)
                <option value="{{ $lang->id }}">{{ $lang->name }}</option>
              @endforeach
            </select>
            <p id="err_language_id" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="images_media[]">{{ __('Images') . '*' }}</label>
            <br>
            <div class="thumb-preview">
              <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
            </div>

            <div class="mt-3">
              <div role="button" class="btn btn-primary btn-sm upload-btn">
                {{ __('Choose Image') }}
                <input id="images_media" type="file" class="img-input" name="images_media[]" multiple>
              </div>
              <p id="err_images" class="mt-1 mb-0 text-danger em"></p>
            </div>
          </div>

          <div class="form-group">
            <label for="thumbnail_media">{{ __('Thumbnail') . '*' }}</label>
            <br>
            <div class="thumb-preview">
              <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
            </div>

            <div class="mt-3">
              <div role="button" class="btn btn-primary btn-sm upload-btn">
                {{ __('Choose Image') }}
                <input type="file" class="img-input" name="thumbnail_media" id="thumbnail_media">
              </div>
              <p id="err_thumbnail_media" class="mt-1 mb-0 text-danger em"></p>
            </div>
          </div>

          <div class="form-group">
            <label for="title">{{ __('Title') . '*' }}</label>
            <input id="title" type="text" class="form-control" name="title" placeholder="Enter Place Name">
            <p id="err_title" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="x_location">{{ __('X_location') }}</label>
            <input id="x_location" type="text" class="form-control" name="x_location"
                   placeholder="Enter Place X_location">
            <p id="err_x_location" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="y_location">{{ __('Y_location') }}</label>
            <input id="y_location" type="text" class="form-control" name="y_location"
                   placeholder="Enter Place Y_location">
            <p id="err_y_location" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="description">{{ __('Description') }}</label>
            <textarea id="description" name="description" class="form-control"></textarea>
            <p id="err_description" class="mt-1 mb-0 text-danger em"></p>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="modalSubmit" type="button" class="btn btn-primary btn-sm">
          {{ __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>
