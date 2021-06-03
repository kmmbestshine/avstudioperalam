@extends('backend.layouts.master')
@section('title')
    E-Sevai Bill create Page
@endsection
@section('css')

@endsection
<!-- page content -->
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>E-Sevai Report </h3>
                </div>
               <!-- <div class="title_right">
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group top_search">
                        <div class="row">
                            <div class="text-right">
                                <a href="{{route('esevai.service.create')}}" class="btn btn-success">Create E-Service</a>
                                <a href="{{route('gst.create')}}" class="btn btn-success"> Create GST</a>
                                <a href="{{route('units.create')}}" class="btn btn-success"> Create Units</a>
                            </div>
                        </div>
                    </div>
                </div>-->
                
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
                            <h2>Report List</h2>
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
                        <div class="title_right">
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group top_search">
                        <div class="row">
                            <div class="text-right">
                                <a href="{{route('esevai.service.allreport')}}" class="btn btn-success">All Report</a>
                                <a href="{{route('esevai.service.normalreport')}}" class="btn btn-success"> Normal Report</a>
                                <a href="{{route('esevai.service.undeliverdreport')}}" class="btn btn-success">Undeliverd Report</a>
                                <a href="{{route('esevai.service.deliverdreport')}}" class="btn btn-success">Deliverd Report</a>
                            </div>
                        </div>
                    </div>
                </div>
                      <!--  <div class="x_content">
                            <form action="{{route('esevaibill.verify')}}" method="post">
                                {{ csrf_field()}}
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                                    <span class="error"><b>
                                           @if($errors->has('name'))
                                                {{$errors->first('name')}}
                                            @endif</b>
                                     </span>
                                </div>
                                <div class="form-group">
                                    <label for="mobile">Mobile</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile No">
                                    <span class="error"><b>
                                           @if($errors->has('mobile'))
                                                {{$errors->first('mobile')}}
                                            @endif</b>
                                     </span>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address">
                                    <span class="error"><b>
                                           @if($errors->has('address'))
                                                {{$errors->first('address')}}
                                            @endif</b>
                                     </span>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email">
                                    <span class="error"><b>
                                           @if($errors->has('email'))
                                                {{$errors->first('email')}}
                                            @endif</b>
                                     </span>
                                </div>
                            <br>
          <div class="x_content" style="overflow: scroll;">
          <table  id="myTable" style="border: 1px solid black"  class="table table-striped table-bordered table-hover">                      
          
            <thead>
                <tr>
                 <th style="min-width:120px">Service Type</th>
                 <th>Units</th>
                 <th>Qty</th>
                 <th >Rate</th>
                 <th>Delivery Date</th>
                 <th>Delete</th>
                </tr>
                </thead>
            <tbody>
            <?php $j=1; ?>
              <tr>
                  <td>
                      
                  </td>
                  <td>
                   
                  </td>
                  <td>
                      <input type="text" class="fname" name="qty[]" placeholder="Enter Quandity" required/>
                  </td>
                  
                 <td>
                      <input type="text" class="fname" name="rate[]" placeholder="Enter Rate per item"  required/>

                  </td>
                  
                  <td>
                      <input type="date" class="fname" name="deliverd_date[]" placeholder="Enter Delivery Date"  />
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
                               
                                
                                <div class="box-footer">
                                    <button type="submit" name="btnCreate" class="btn btn-primary" >Save ESevai Bill</button>
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
    

     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <script type="text/javascript">
$('#myTable').on('click', 'input[type="button"]', function () {
    $(this).closest('tr').remove();
})
$('p input[type="button"]').click(function () {
  
    $('#myTable').append('<tr><td><input type="text" class="fname" name="qty[]" placeholder="Enter Quandity" required/></td><td><input type="text" class="fname" name="rate[]" placeholder="Enter Rate per item"  required/></td><td><input type="date" class="fname" name="deliverd_date[]" placeholder="Enter Delivery Date"  /></td><td><input type="button" value="Delete" class="btn btn-danger remove"/></td></tr>')
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