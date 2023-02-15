@extends('admin.layouts.master')
@section('title',$title)
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
                <a href="" class="text-muted">Manage Discount</a>
              </li>
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Add Discount</a>
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
              <h3 class="card-label">Discount Add Form
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('discounts.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href="{{ route('discounts.store') }}"  onclick="event.preventDefault(); document.getElementById('discount_add_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>Save</a>



              </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
            {{ Form::open([ 'route' => 'discounts.store','class'=>'form' ,"id"=>"discount_add_form", 'enctype'=>'multipart/form-data']) }}
              @csrf
              <div class="row">
                <div class="col-xl-2"></div>
                <div class="col-xl-8">
                  <div class="my-5">
                    <h3 class="text-dark font-weight-bold mb-10">Discounts Info: </h3>
                    <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                      <label class="col-3">Title</label>
                      <div class="col-9">
                        {{ Form::text('title', null, ['class' => 'form-control form-control-solid','id'=>'title','placeholder'=>'Enter title','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('title') }}</span>
                      </div>
                    </div>
                    <div class="form-group row {{ $errors->has('coupen') ? 'has-error' : '' }}">
                      <label class="col-3">Coupen</label>
                      <div class="col-9">
                        {{ Form::text('coupen', null, ['class' => 'form-control form-control-solid','id'=>'coupen','placeholder'=>'Enter coupen','required'=>'true']) }}
                        <div class="mt-3">
                         <button type="button" class="btn btn-primary btn-sm" onclick="myFunction()">Generate Coupen</button>
                        </div>

                        <span class="text-danger">{{ $errors->first('coupen') }}</span>
                      </div>
                    </div>
                    <div class="form-group row {{ $errors->has('expire_at') ? 'has-error' : '' }}">
                      <label class="col-3">Expire at</label>
                      <div class="col-9">
                        {{ Form::date('expire_at', null, ['class' => 'form-control form-control-solid','id'=>'expire_at','placeholder'=>'Enter expire_at','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('expire_at') }}</span>
                      </div>
                    </div>
                    <div class="form-group row {{ $errors->has('price') ? 'has-error' : '' }}">
                      <label class="col-3">Amount ($)</label>
                      <div class="col-9">
                        {{ Form::number('amount', null, ['class' => 'form-control form-control-solid','id'=>'amount','placeholder'=>'Enter amount','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                      </div>
                    </div>
                    
                  </div>

                </div>
                <div class="col-xl-2"></div>
              </div>
          {{Form::close()}}
            <!--end::Form-->
          </div>
        </div>
        <!--end::Card-->

      </div>
      <!--end::Container-->
    </div>
    <!--end::Entry-->
  </div>
    <script>
      function myFunction() {
        var id = "#" + Math.random().toString(16).slice(2)        
        document.querySelector('input[name="coupen"]').value = id ?? ''; 

      }
    </script>
@endsection
