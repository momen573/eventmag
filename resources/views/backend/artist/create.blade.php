<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Artist') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="modalForm" class="modal-form create" action="{{ route('admin.artists.store') }}"
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
            <label for="image_media">{{ __('Image') . '*' }}</label>
            <br>
            <div class="thumb-preview">
              <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
            </div>

            <div class="mt-3">
              <div role="button" class="btn btn-primary btn-sm upload-btn">
                {{ __('Choose Image') }}
                <input type="file" class="img-input" name="image_media" id="image_media">
              </div>
              <p id="err_image_media" class="mt-1 mb-0 text-danger em"></p>
            </div>
          </div>

          <div class="form-group">
            <label for="status">{{ __('Status') . '*' }}</label>
            <select id="status" name="status" class="form-control">
              <option selected disabled>{{ __('Select a Status') }}</option>
              @foreach(\App\Models\Artist::$statuses as $status)
                <option value="{{$status}}">{{ $status }}</option>
              @endforeach
            </select>
            <p id="err_status" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="full_name">{{ __('FullName') . '*' }}</label>
            <input id="full_name" type="text" class="form-control" name="full_name" placeholder="Enter Artist FullName">
            <p id="err_full_name" class="mt-1 mb-0 text-danger em"></p>
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
