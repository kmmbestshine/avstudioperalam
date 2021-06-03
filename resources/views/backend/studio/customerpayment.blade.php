@extends('backend.layouts.master')
@section('title')
   Payment Details
@endsection
@section('css')
    <link  href="{{asset('backend/plugins/datepicker/datepicker.css')}}" rel="stylesheet">
@endsection
<!-- page content -->
@section('content')
    <div class="right_col" role="main">
        <div class="">
            
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
                            <h2>Create Order ( Customer ID :{{$customer->customer_id}} )</h2>
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
                        <div class="x_content">
                            <form action="{{route('studiobill.verify.exist')}}" method="post">
                                {{ csrf_field()}}
                                <div class="row">
                              <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{$customer->client_name}}" placeholder="Enter name" readonly>
                                    <input type="hidden" class="form-control" id="name" name="customer_id" value="{{$customer->customer_id}}" placeholder="Enter name" >
                                    <span class="error"><b>
                                           @if($errors->has('name'))
                                                {{$errors->first('name')}}
                                            @endif</b>
                                     </span>
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="mobile">Mobile</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" value="{{$customer->phone}}" placeholder="Enter Mobile No" readonly>
                                    <span class="error"><b>
                                           @if($errors->has('mobile'))
                                                {{$errors->first('mobile')}}
                                            @endif</b>
                                     </span>
                                </div>
                                </div>
                            </div>
                               <br>
                               <div class="row">
                              
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{$customer->address}}" placeholder="Enter Address" readonly>
                                    <span class="error"><b>
                                           @if($errors->has('address'))
                                                {{$errors->first('address')}}
                                            @endif</b>
                                     </span>
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{$customer->email}}" placeholder="Enter Email" readonly>
                                    <span class="error"><b>
                                           @if($errors->has('email'))
                                                {{$errors->first('email')}}
                                            @endif</b>
                                     </span>
                                </div>
                                </div>
                            </div>
                            <br>
                               
                                
                                
          <div class="x_content" style="overflow: scroll;">
          <table  id="myTable" style="border: 1px solid black"  class="table table-striped table-bordered table-hover">                      
          
            <thead>
                <tr>
                 <th style="min-width:120px">Package Size</th>
                 <th>Size</th>
                 <th>Qty</th>
                 <th>Amount</th>
                 <th>Delivery Date</th>
                 <th>Delete</th>
                </tr>
                </thead>
            <tbody>
            <?php $j=1; ?>
              <tr>
                  <td>@if($sizes)
                      <select class="fname" name="pack_size[]" >
                            <option value="">Size</option>
                              @foreach($sizes as $size)
                            <option value="{{ $size->size }}">{{ $size->size }}</option>
                              @endforeach
                            </select>
                        @endif
                  </td>
                  <td>
                      <input type="text" class="fname" name="size[]" placeholder="Enter Size" size="10" />

                  </td>
                  
                  <td>
                      <input type="text" class="fname" name="qty[]" placeholder="Enter Qty" maxlength="4" size="6" required/>
                  </td>
                  <td>
                      <input type="text" class="fname" name="amt[]" placeholder="Enter Amount"  size="7" required/>
                  </td>
                 
                  
                  <td>
                      <input type="date" class="fname" name="deliverd_date[]" placeholder="Enter Delivery Date" size="10" />
                  </td>
                 
                  <td>
                      <input type="button" value="Delete" class="btn btn-danger remove"/>
                  </td>
              </tr>
              
              </tbody>
          </table>
          <p>
              <input type="button" value="Insert row" class="btn btn-info">
          </p>

        </div>
        <br>
                               
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    
                                    <button type="submit" name="btnCreate" class="btn btn-primary" >Save Studio Bill</button>
                                </div>
                            </form>
                        </div>





                        <div class="x_content" style="overflow: scroll;">
                           <form method="post" action="{{route('studio.collection.verify')}}">
                            {!! csrf_field() !!}
                            <div class="panel-body">
                                <div class="panel-heading">
                                <h3 class="panel-title">Payable Details</h3>
                                </div>
                        
                        @if(!empty($unpaid_ids))
                            <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                               <th>S.No</th>
                               <th>Order</th>
                                <th>Size</th>
                                <th>Quandity</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $j=1;  ?>
                            <?php for($i = 0; $i<count($unpaid_ids); $i++) : ?>
                            <tr>
                                
                               <td style="width: 2%"><?php echo  $j++ ?></td>
                                
                                <td style="width: 25%"><div class="input-field"> <input type="checkbox" name="unpaid_ids[]" value="{{$unpaid_ids[$i]}}" checked >
                                  <input type="hidden" name="unpaid_service_id[]" value="{{$unpaid_service_id[$i]}}"  >{{ $unpaid_service_id[$i] }} <span class="fee-format"></span><br></div></td>
                                   </td>
                                   <td> <input type="hidden" name="unpaid_size[]" value="{{$unpaid_size[$i]}}"  >{{ $unpaid_size[$i] }} <span class="fee-format"></span><br></div></td>
                                <td style="width: 10%"><input type="hidden" name="unpaid_quantity[]" value="{{ $unpaid_quantity[$i] }}" > {{$unpaid_quantity[$i]}}<br></td>
                                <td style="width: 10%"><input type="hidden" name="checkboxamt[]" value="{{ $unpaid_amt[$i] }}" > {{$unpaid_amt[$i]}}<br></td>
                            
                            </tr>
                                
                                
                                <?php endfor ?>
                                <tr>
                                    <th colspan="3"></th>
                                    <th ><p align="right">Total Balance Amount</p></th>
                                    <th>{{$unpaid_totamt}}</th>
                                </tr>
                           <tbody>
                        </table>
                        @else
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                               <th>S.No</th>
                                <th>Size</th>
                                <th>Quandity</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr><th colspan="4">
                            No Data Available</th></tr>
                           <tbody>
                        </table>
                        @endif
                              
                        </div>
                            
                        <div>
                        
                               <input type="hidden"name="customer_id" value="{{$customer_id}}"  >
                               <th><input type="submit"name="amt" class="btn btn-primary" value='SUBMIT' ></th>
                               <p id="msg"></p>
                        </div>
                        </form>






                        <div class="panel-body">
                                <div class="panel-heading">
                                <h3 class="panel-title">Paid Details</h3>
                                </div>
                        @if(!empty($allpaid_ids))
                            <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                               <th>S.No</th>
                                <th>Paid Date</th>
                                <th>Invoice No</th>
                                <th>Size</th>
                                <th>Quandity</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $m=1;  ?>
                            <?php for($k = 0; $k<count($allpaid_ids); $k++) : ?>
                            <tr>
                                
                               <td style="width: 2%"><?php echo  $m++ ?></td>
                                <td style="width: 10%"> {{$allpaid_date[$k]}}<br></td>
                                <td style="width: 10%"> {{$invoiceids[$k]}}<br></td>
                                <td style="width: 10%"> {{$allpaid_qty[$k]}}<br></td>
                                <td style="width: 10%"> {{$allpaid_qty[$k]}}<br></td>
                                <td style="width: 10%"> {{$allpaid_amt[$k]}}<br></td>
                            </tr>
                                
                                
                                <?php endfor ?>
                                <tr>
                                    <th colspan="4"></th>
                                    <th ><p align="right">Total Amount</p></th>
                                    <th>{{$total_paidAmt}}</th>
                                </tr>
                           </tbody>
                        </table>
                        @else
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                               <th>S.No</th>
                                <th>Size</th>
                                <th>Quandity</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr><th colspan="4">
                            No Data Available</th></tr>
                           </tbody>
                        </table>
                        @endif
                    </div>
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
    <script src="{{asset('backend/plugins/datepicker/datepicker.js')}}"></script>
    <script type="text/javascript">
        $('[data-toggle="start"]').datepicker({
            format: 'yyyy-mm-dd'
        });

        $('[data-toggle="end"]').datepicker({
            format: 'yyyy-mm-dd'
        });
    </script>

    

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">

</script>
    <script type="text/javascript">
$('#myTable').on('click', 'input[type="button"]', function () {
    $(this).closest('tr').remove();
})
$('p input[type="button"]').click(function () {
  
    $('#myTable').append('<tr><td><select class="fname" name="pack_size[]" ><option value="">Size</option>@foreach($sizes as $size)<option value="{{ $size->size }}">{{ $size->size }}</option>@endforeach</select></td><td><input type="text" class="fname" name="size[]" placeholder="Enter Size" size="10" /></td><td><input type="text" class="fname" name="qty[]" placeholder="Enter Qty" size="6" required/></td><td><input type="text" class="fname" name="amt[]" placeholder="Enter Amount"  size="7" required/></td><td><input type="date" class="fname" name="deliverd_date[]" placeholder="Enter Delivery Date" size="10" /></td><td><input type="button" value="Delete" class="btn btn-danger remove"/></td></tr>')
});
    </script>
    <script>
function deleteRow(id,row) {
    document.getElementById(id).deleteRow(row);
}

function insRow(id) {  
    var filas = document.getElementById("myTable").rows.length;
    var x = document.getElementById(id).insertRow(filas);
    var y = x.insertCell(0);
    var z = x.insertCell(1);
    y.innerHTML = '<input type="text" id="fname">';
    z.innerHTML ='<button id="btn" name="btn" > Delete</button>';
}
</script>
<script></script>
@endsection