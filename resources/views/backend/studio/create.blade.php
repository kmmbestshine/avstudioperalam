@extends('backend.layouts.master')
@section('title')
    Studio Bill create Page
@endsection
@section('css')

@endsection
<!-- page content -->
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Studio Bill Management </h3>
                </div>
                <div class="title_right">
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group top_search">
                        <div class="row">
                            <div class="text-right">
                                <a href="{{route('studio.size.create')}}" class="btn btn-success">Create Size</a>
                               <!-- <a href="{{route('studio.order.payment')}}" class="btn btn-success">Studio Payment</a>
                                <a href="{{route('studio.view.customer')}}" class="btn btn-success">All Orders</a>-->
                                <a href="{{route('studio.order.undeliverd')}}" class="btn btn-success">Undeliverd Order</a>
                                <a href="{{route('studio.order.proof')}}" class="btn btn-success">Proof Order</a>
                                <a href="{{route('studio.order.deliverd')}}" class="btn btn-success">Deliverd Order</a>
                            </div>
                        </div>
                    </div>
                </div>
                
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
                            <h2>Create Studio Bill</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                       aria-expanded="false"><i class="fa fa-wrench"></i></a>
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
                            <form action="{{route('studiobill.verify')}}" method="post">
                                {{ csrf_field()}}
                                <div class="row">
                              <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
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
                                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile No" required>
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
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address">
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
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email">
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
                  <td>
                      <select class="fname" name="pack_size[]" >
                            <option value="">Size</option>
                              @foreach($sizes as $size)
                            <option value="{{ $size->size }}">{{ $size->size }}</option>
                              @endforeach
                            </select>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection

@section('script')

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