@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Artists') }}</h4>
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
        <a href="#">{{ __('Artists') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Artists') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                 class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add Artist') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                      data-href="{{ route('admin.artists.destroy_groups') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($artists) == 0)
                <h3 class="text-center mt-2">{{ __('NO ARTIST FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                    <tr>
                      <th scope="col">
                        <input type="checkbox" class="bulk-check" data-val="all">
                      </th>
                      <th scope="col">{{ __('Row') }}</th>
                      <th scope="col">{{ __('FullName') }}</th>
                      <th scope="col">{{ __('Image') }}</th>
                      <th scope="col">{{ __('Status') }}</th>
                      <th scope="col">{{ __('Description') }}</th>
                      <th scope="col">{{ __('Language') }}</th>
                      <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($artists as $key=>$artist)
                      <tr>
                        <td>
                          <input type="checkbox" class="bulk-check" data-val="{{ $artist->id }}">
                        </td>
                        <td>
                          {{ $key+1 }}
                        </td>
                        <td>
                          {{ $artist->full_name }}
                        </td>
                        <td>
                          <img src="{{ $artist->image }}"
                               class="img-fluid mh60" alt="">
                        </td>
                        <td>
                          @if ($artist->status == 'active')
                            <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Active') }}</span>
                            </h2>
                          @else
                            <h2 class="d-inline-block"><span class="badge badge-danger">{{ __('Inactive') }}</span>
                            </h2>
                          @endif
                        </td>
                        <td>
                          @if(!empty($artist->description))
                            {{ strlen($artist->description) > 50 ? mb_substr($artist->description, 0, 50, 'UTF-8') . '...' : $artist->description }}
                          @else
                            <p class="text-center">_____</p>
                          @endif
                        </td>
                        <td>
                          {{ $artist->language->name }}
                        </td>

                        <td>
                          <a class="btn btn-secondary mt-1 btn-xs mr-1 editBtn" href="#" data-toggle="modal"
                             data-target="#editArtistModal">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                          </a>

                          <form class="deleteForm d-inline-block"
                                action="{{ route('admin.artists.destroy', $artist->id) }}"
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
  @include('backend.artist.create')

  @if(count($artists))
    {{-- edit modal --}}
    @include('backend.artist.edit')
  @endif

@endsection
