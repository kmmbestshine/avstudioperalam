@extends('backend.layouts.master')
@section('title')
    Deposit Listing Page
@endsection
@section('css')

@endsection
<!-- page content -->
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Deposit Management</h3>
                </div>
                <div class="title_right">
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group top_search">
                        <div class="row">
                           <!-- <div class="text-right">
                                <a href="{{route('product.create')}}" class="btn btn-success">Create Product</a>
                                <a href="{{route('gst.create')}}" class="btn btn-success"> Create GST</a>
                                <a href="{{route('units.create')}}" class="btn btn-success"> Create Units</a>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>
            
            <div class="clearfix"></div>
            @if(Session::has('success_message'))
                <div class="alert alert-success">
                    {{ Session::get('success_message') }}
                </div>
            @endif
            @if(Session::has('error_message'))
                <div class="alert alert-danger">
                    {{ Session::get('error_message') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Listing Deposit</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#">Settings 1</a>
                                        </li>
                                        <li><a href="#">Settings 2</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="overflow: scroll;">
                            <table style="border: 1px solid black" class="table table-striped table-bordered table-hover" id="categorytable">
                                <thead>
                                <tr>
                                    <th>S.N.</th>
                                   <th>Date</th>
                                    <th>Name</th>
                                    <th>Bank Name</th>
                                    <th>Account No</th>
                                    <th>Branch Name</th>
                                    <th>IFSC Code</th>
                                    <th>Amount</th>
                                    <th>Available Amount</th>
                                    <th>Status</th>
                                    
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1 ?>
                                @foreach($amt_list as $pc)
                                    <tr>
                                        <th> {{$i++}}</th>
                                       <td>{{$pc->date}} </td>
                                        <td>{{$pc->name}} </td>
                                        <td>{{$pc->bankname}} </td>
                                     <td> {{$pc->account_no }}</td>
                                        <td> {{$pc->branchname}}</td>
                                        <td> {{$pc->ifsc}}</td>
                                        <td> {{$pc->deposit_amt}}</td>
                                        <td> {{$pc->available_amt}}</td>
                                        <td>
                                            @if($pc->status == 1)
                                                <span class="label label-success"> Active </span>
                                            @else
                                                <span class="label label-danger">DeActive</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <a href="{{route('product.edit',$pc->id)}}" class="btn btn-info"><i class="fa fa-pencil"></i></a>
                                                </div>
                                                <div class="col-md-2">
                                                    <form action="{{route('product.delete' ,$pc->id)}}" method="post">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        {{ csrf_field()}}
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('are you sure to delete?')" ><i class="fa fa-trash-o"></i></button>
                                                    </form>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="{{route('deposit.amount.edit',$pc->id)}}" class="btn btn-info"><i class="fa fa-plus"></i> Deposit Amt Update</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection

@section('script')
    <script type="text/javascript" src="{{asset('backend/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#categorytable').DataTable();
        } );
    </script>
@endsection