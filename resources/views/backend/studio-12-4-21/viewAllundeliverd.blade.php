@extends('backend.layouts.master')
@section('title')
   All Undeliverd Order
@endsection
@section('css')
    <link  href="{{asset('backend/plugins/datepicker/datepicker.css')}}" rel="stylesheet">
@endsection
<!-- page content -->
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Select Dates</h3>
                </div>
                
                

            </div>
            <br>
            <br>
            <div class="row">
                            <form action="{{route('get.order.undeliverd')}}" method="post">
                                {{csrf_field()}}
                                <div class="col-md-3">
                                    <input class="form-control" data-toggle="start" type="text" name="start" placeholder="pick Start Date">
                                </div>
                                <div class="col-md-3">
                                    <input class="form-control" data-toggle="end" type="text" name="end" placeholder="pick End Date">
                                </div>
                                <div class="col-md-3">
                                    <button name="" class="btn btn-info">All Undeliverd Order</button>
                                </div>
                            </form>
                        </div>
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
                            <h2>All Undeliverd Order</h2>
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
                                    <th>Name</th>
                                    <th>Customer ID</th>
                                    <th>Update Order Status</th>
                                   
                                    <th>Mobile No</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Tot Amount</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    
                                    <th>status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1;  ?>
                               @foreach($allundeliverd as $pc)
                                    <tr>
                                        <th> {{$i++}}</th>
                                        <td>{{$pc->client_name}} </td>
                                        <td>{{$pc->customer_id}}</td>
                                        <td><a href="{{route('update.order.proof',$pc->service_id)}}" class="btn btn-primary" color="FF00CC" >{{$pc->service_id}}</a></td>
                                       
                                        <td>{{$pc->phone}} </td>
                                        <td>{{$pc->email}} </td>
                                        <td>{{$pc->address}} </td>
                                        <td>{{$pc->subtotal}} </td>
                                        <td>{{$pc->paid_amt}} </td>
                                        <td>{{$pc->due_amt}} </td>
                                      
                                        
                                        <td>
                                            @if($pc->deliverd_status == 0)
                                                <span class="label label-danger"> Undeliverd </span>
                                            @else
                                                <span class="label label-success"> Deliverd </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                       <!-- <div class="row">
                            <form action="{{route('custom.report')}}" method="post">
                                {{csrf_field()}}
                                <div class="col-md-3">
                                    <input class="form-control" data-toggle="start" type="text" name="start" placeholder="pick Start Date">
                                </div>
                                <div class="col-md-3">
                                    <input class="form-control" data-toggle="end" type="text" name="end" placeholder="pick End Date">
                                </div>
                                <div class="col-md-3">
                                    <button name="" class="btn btn-info">Import Report</button>
                                </div>
                            </form>
                        </div>-->
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
    <script src="{{asset('backend/plugins/datepicker/datepicker.js')}}"></script>
    <script type="text/javascript">
        $('[data-toggle="start"]').datepicker({
            format: 'yyyy-mm-dd'
        });

        $('[data-toggle="end"]').datepicker({
            format: 'yyyy-mm-dd'
        });
    </script>
@endsection