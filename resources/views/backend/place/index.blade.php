@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Places') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Places') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Places') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add Place') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.places.destroy_groups') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($places) == 0)
                <h3 class="text-center mt-2">{{ __('NO PLACE FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Thumbnail') }}</th>
                        <th scope="col">{{ __('Images') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('X_Location') }}</th>
                        <th scope="col">{{ __('Y_Location') }}</th>
                        <th scope="col">{{ __('Description') }}</th>
                        <th scope="col">{{ __('Language') }}</th>

{{--                        <th scope="col">{{ __('Featured') }}</th>--}}

                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($places as $place)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $place->id }}">
                          </td>
                          <td>
                            <img src="{{ $place->thumbnail }}"
                              class="img-fluid mh60" alt="">
                          </td>
                          <td>
                            @if($place->images)
                              @foreach($place->images as $image)
                                <img src="{{ $image }}"
                                     class="img-fluid mh60" alt="">
                              @endforeach
                            @else
                              _____
                            @endif
                          </td>
                          <td>
                            {{ $place->title }}
                          </td>
                          <td>
                            {{ $place->x_location }}
                          </td>
                          <td>
                            {{ $place->y_location }}
                          </td>
                          <td>
                            {{ strlen($place->description) > 50 ? mb_substr($place->description, 0, 50, 'UTF-8') . '...' : $place->description }}
                          </td>

                          <td>
                            {{ $place->language->name }}
                          </td>

                          <td>
                            <a class="btn btn-secondary mt-1 btn-xs mr-1 editBtn" href="#" data-toggle="modal"
                              data-target="#editPlaceModal">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.places.destroy', $place->id) }}"
                              method="post">

                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger mt-1 btn-xs deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer"></div>
      </div>
    </div>
  </div>

  {{-- create modal --}}
  @include('backend.place.create')

  @if(count($places))
    {{-- edit modal --}}
    @include('backend.place.edit')
  @endif

@endsection
