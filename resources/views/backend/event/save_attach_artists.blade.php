<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Attach Artists') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="modalForm" class="modal-form create"
              action="{{ route('admin.event_management.attach_artists',$event->id) }}"
              method="post">
          @csrf

          <div class="form-group">
            <label for="artists[]">{{ __('Artists') . '*' }}</label>
            <select multiple id="artists[]" name="artists[]" class="form-control">
              <option selected disabled>{{ __('Select Artists') }}</option>
              @foreach($artists as $artist)
                @if(count($save_ids))
                  <option @if(in_array($artist->id,$save_ids)) disabled @endif value="{{$artist->id}}">{{ $artist->full_name }}</option>
                @else
                  <option value="{{$artist->id}}">{{ $artist->full_name }}</option>
                @endif
              @endforeach
            </select>
            <p id="err_artists" class="mt-1 mb-0 text-danger em"></p>
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
