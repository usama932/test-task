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
              <h3 class="card-label">Order Detail
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('orders.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Orders</a>

              
            </div>
          </div>
            <div class="card-body">
                    <div class="card-datatable ">
                      @if(!empty($order))
                        <table class="datatables-demo table table-striped table-bordered">
                            <tbody>
                            <tr>
                                <td>First Name</td>
                                <td>{{$order->first_name}}</td>
                            </tr>
                            <tr>
                                <td>Last Name</td>
                                <td>{{$order->last_name}}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{$order->email}}</td>
                            </tr>
                            <tr>
                                <td>Phone #</td>
                                <td>{{$order->phone_number}}</td>
                            </tr>
                            <tr>
                                <td>Room</td>
                                <td>{{$order->room->title}}</td>
                            </tr>
                            <tr>
                                <td>Bathroom</td>
                                <td>{{$order->bathroom->title}}</td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td>@if($order->discount_id != '0'){{$order->discount->title}} @else No Discount @endif </td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td>{{$order->date ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>Extra Services</td>
                                <td>
                                    @foreach($order->extraorder as $service)
                                    {{$service->service->title}},&nbsp
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>Clean Type</td>
                                <td>
                                    @foreach($order->cleaningtype as $cleantype)
                                    {{$cleantype->cleantype->title}},&nbsp
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>Contact With Covid Person</td>
                                <td>{{$order->contact_with_covid_person}}</td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td>{{$order->date}}</td>
                            </tr>
                            <tr>
                                <td>Total Bill ($)</td>
                                <td>{{$order->total_bill}}</td>
                            </tr>
                            <tr>
                                <td>Created at</td>
                                <td>{{$order->created_at}}</td>
                            </tr>
                            
                            </tbody>
                        </table>
                      @else
                        <h4 class="text-danger"> No Order Found</h4>
                      @endif
                    </div>            
            </div>
        </div>
        <!--end::Card-->

      </div>
      <!--end::Container-->
    </div>
    <!--end::Entry-->
  </div>
@endsection
