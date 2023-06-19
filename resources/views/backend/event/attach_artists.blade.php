@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Attach Artists To Event') }}</h4>
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
        <a href="#">{{ __('Attach Artists To Event') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Attach Artists To Event') }}</div>
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                 class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Attach Artists') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                      data-href="{{ route('admin.event_management.detach_artists',$event->id) }}">
                <i class="flaticon-interface-5"></i> {{ __('Detach') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($items) == 0)
                <h3 class="text-center mt-2">{{ __('NO ITEMS FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                    <tr>
                      <th scope="col">
                        <input type="checkbox" class="bulk-check" data-val="all">
                      </th>
                      <th scope="col">{{ __('Row') }}</th>
                      <th scope="col">{{ __('Artist_Id') }}</th>
                      <th scope="col">{{ __('Artist_Full_Name') }}</th>
                      <th scope="col">{{ __('Event_Id') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($items as $key=>$item)
                      <tr>
                        <td>
                          <input type="checkbox" class="bulk-check" data-val="{{ $item->id }}">
                        </td>
                        <td>
                          {{ $key+1 }}
                        </td>
                        <td>
                          {{ $item->id }}
                        </td>
                        <td>
                          {{ $item->full_name }}
                        </td>
                        <td>
                          {{ $event->id }}
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

  {{-- attach modal --}}
  @include('backend.event.save_attach_artists')

@endsection
