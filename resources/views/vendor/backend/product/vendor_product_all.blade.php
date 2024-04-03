@extends('vendor.vendor_dashboard')

@section('vendor')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">All Product</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">all product<span class="badge rounded-pill bg-danger">{{ count($products) }}</span></li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">

                <a href="{{ route('vendor.add.product') }}" class="btn btn-primary">Add Product</a>


            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>QTY</th>
                            <th>Discount</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>

                    </thead>
             <tbody>

                @foreach($products as $key=> $item)

                            <!--$brand brandiiig $item variableaar awahiig deer zaasan baina-->

                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td><img src="{{ asset($item->product_thambnail) }}" style="width:70px; height:40px;"></td>

                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->selling_price}}</td>
                                    <td>{{ $item->product_qty }}</td>
                                    <td>
                                        @if($item->discount_price==Null)

                                        <span class="badge rounded-pill bg-info">No discount</span>
                                        @else
                                        @php

                                        $amount=$item->selling_price - $item->discount_price;
                                        $discount=($amount/$item->selling_price)*100;


                                        @endphp
                                        <span class="badge rounded-pill bg-danger">{{ round($discount) }}%</span>



                                        @endif

                                    </td>
                                    <td>
                                        @if ($item->status ==1)
                                        <span class="badge rounded-pill bg-success">Active</span>

                                        @else
                                        <span class="badge rounded-pill bg-danger">inActive</span>

                                    @endif
                                </td>
                                    <td>
                                        <a href="{{ route('edit.product',$item->id) }}" class="btn btn-info" title="Edit Data"><i class="fa fa-pencil"></i></a>
                                        <a href="{{ route('delete.product',$item->id) }}" class="btn btn-danger" id="delete" title="Delete Data"><i class="fa fa-trash"></i></a>


                                        <a href="{{ route('delete.category',$item->id) }}" class="btn btn-warning" title="Details Data"><i class="fa fa-eye"></i></a>
                                        @if ($item->status ==1)

                                        <a href="{{ route('product.inactive',$item->id) }}" class="btn btn-primary"  title="Delete Data"><i class="fa fa-thumbs-down"></i></a>
                                        @else
                                        <a href="{{ route('product.active',$item->id) }}" class="btn btn-primary"  title="Delete Data"><i class="fa fa-thumbs-up"></i></a>

                                        @endif

                                    </td>



                                </tr>

                 @endforeach

             </tbody>

                </table>
            </div>
        </div>
    </div>






</div>



@endsection
