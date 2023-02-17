@extends('admin.layouts.master')
@section('title','Edit Pages')
@section('content')
  <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader" kt-hidden-height="54" style="">
      <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
          <!--begin::Page Heading-->
          <div class="d-flex align-items-baseline flex-wrap mr-5">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold my-1 mr-5">Dashboard</h5>
            <!--end::Page Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Manage PAge</a>
              </li>
              <li class="breadcrumb-item text-muted">
                Edit Page
              </li>
           
            </ul>
            <!--end::Breadcrumb-->
          </div>
          <!--end::Page Heading-->
        </div>
        <!--end::Info-->
      </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
      <!--begin::Container-->
      <div class="container">
        <!--begin::Card-->
        <div class="card card-custom card-sticky" id="kt_page_sticky_card">
          <div class="card-header" style="">
            <div class="card-title">
              <h3 class="card-label">Page Edit Form
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('sections.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href=""  onclick="event.preventDefault(); document.getElementById('client_update_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>update</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            @include('admin.partials._messages')
            <!--begin::Form-->
            <form class="form" id ="client_update_form" method="POST" action="{{ route('sections.update',$section->id) }}" enctype='multipart/form-data'>
              @csrf
              <div class="row">
                  <div class="col-xl-2"></div>
                  <div class="col-xl-8">
                    <div class="my-5">
                        @method('PUT')
                        @csrf
                        @php $count=0; @endphp
                        @foreach($all_columns as $column)
                        @if($column['type']=="file")
                        <div class="form-group">
                          <label class="form-label">{{$column['label']}}</label>
                          <?php
                              if (isset($storedColumns[$column['name']])) {
                                $storedColumns[$column['name']] = $storedColumns[$column['name']];
                              } else {
                                $storedColumns[$column['name']] = 'abc.png';
                              }
                              ?>
                          <input type="file" name="{{$column['name']}}" placeholder="{{isset($column['place_holder']) ? $column['place_holder'] :''}}" class="{{$column['class']}}" value="{{ isset($storedColumns[$column['name']]) ? $storedColumns[$column['name']]
                              : ''}}" id="{{$column['id']}}">
                          <input type="hidden" name="{{$column['name']}}" placeholder="{{isset($column['place_holder']) ? $column['place_holder'] :''}}"  class="{{$column['class']}}" value="{{ isset($storedColumns[$column['name']]) ? $storedColumns[$column['name']]
                              : ''}}" id="{{$column['id']}}">
                          @if(File::exists('uploads/'.$storedColumns[$column['name']]))
                          <img src="{{asset('uploads/'.$storedColumns[$column['name']])}}" style="{{$column['style']}}" alt="{{$column['name']}} is not found" />
                          @else
                          <img src="{{asset('uploads/img.png')}}" style="{{$column['style']}}" alt="{{$column['name']}} is not found" />
                          @endif
                        </div>
                        @endif
                        @if($column['type']=="text")
                        <div class="form-group">
                          <label class="form-label">{{$column['label']}}</label>
                          <input type="text" name="{{$column['name']}}" placeholder="{{isset($column['place_holder']) ? $column['place_holder'] :''}}" value="{{ isset($storedColumns[$column['name']]) ? $storedColumns[$column['name']]
                              : ''}}" class="{{$column['class']}}" id="{{$column['id']}}">
                        </div>
                        @endif
                        @if($column['type']=="hidden")
                        <input type="hidden" name="{{$column['name']}}" value="{{ isset
                          ($storedColumns[$column['name']]) ? $storedColumns[$column['name']]: ''}}">
                        @endif
                        @if($column['type']=="textarea")
                        <div class="form-group">
                          <label class="form-label">{{$column['label']}}</label>
                          <textarea name="{{$column['name']}}" class="{{$column['class']}}" placeholder="{{ isset($column['place_holder']) ? $column['place_holder'] :''}}" id="{{$column['id']}}">{{ isset($storedColumns[$column['name']]) ? $storedColumns[$column['name']] : ''}}
                          </textarea>
                        </div>
                        @endif
                        @php $count++; @endphp
                        @endforeach
                    </div>
                    <div class="separator separator-dashed my-10"></div>
                  </div>
                  <div class="col-xl-2"></div>
              </div>
            </form>
            <!--end::Form-->
            
          </div>
        </div>
        <!--end::Card-->

      </div>
      <!--end::Container-->
    </div>
    <!--end::Entry-->
  </div>
@endsection
@section("scripts")
<script !src="">
	$('.summernote').summernote();
</script>
@endsection