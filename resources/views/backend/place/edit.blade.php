<div class="modal fade" id="editPlaceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Place') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.places.update',$place->id) }}"
              method="post" enctype="multipart/form-data">

          @method('POST')
          @csrf

          <div class="form-group">
            <label for="images_media[]">{{ __('Images') }}</label>
            <br>
            <div class="thumb-preview">
              <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
            </div>

            @if($place->images)
              @foreach($place->images as $image)
                <img src="{{ $image }}"
                     class="img-fluid mh70" alt="">
              @endforeach
            @endif

            <div class="mt-3">
              <div role="button" class="btn btn-primary btn-sm upload-btn">
                {{ __('Choose Image') }}
                <input id="images_media" type="file" class="img-input" name="images_media[]" multiple>
              </div>
              <p id="err_images_media" class="mt-1 mb-0 text-danger em"></p>
            </div>
          </div>

          <div class="form-group">
            <label for="thumbnail_media">{{ __('Thumbnail') }}</label>
            <br>
            <div class="thumb-preview">
              <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
            </div>

            <img src="{{ $place->thumbnail }}"
                 class="img-fluid mh70" alt="">

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
            <input value="{{$place->title}}" id="title" type="text" class="form-control" name="title"
                   placeholder="Enter Place Name">
            <p id="err_title" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="x_location">{{ __('X_location') }}</label>
            <input value="{{$place->x_location}}" id="x_location" type="text" class="form-control" name="x_location"
                   placeholder="Enter Place X_location">
            <p id="err_x_location" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="y_location">{{ __('Y_location') }}</label>
            <input value="{{$place->y_location}}" id="y_location" type="text" class="form-control" name="y_location"
                   placeholder="Enter Place Y_location">
            <p id="err_y_location" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="description">{{ __('Description') }}</label>
            <textarea id="description" name="description" class="form-control">{{$place->description}}</textarea>
            <p id="err_description" class="mt-1 mb-0 text-danger em"></p>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="updateBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Update') }}
        </button>
      </div>
    </div>
  </div>
</div>
