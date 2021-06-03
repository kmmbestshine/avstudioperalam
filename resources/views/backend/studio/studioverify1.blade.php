@extends('backend.layouts.master')
@section('title')
    Studio Bill Preview Page
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
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group top_search" style="padding-left: 130px;">
                            <div class="input-group">
                                <a href="{{route('esevai.service.create')}}" class="btn btn-success">Create E-sevai</a>
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
                            <h2>Studio Bill Preview</h2>
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
                            <form action="{{route('studiobill.store.exist')}}" method="post">
                                {{ csrf_field()}}
                                <div class="row">
                              <div class="col-md-6">
                                    <div class="form-group">
                                    <label >Name  : </label>{{$name}}<br>
                                    <label >Mobile  : </label>{{$mobile}}<br>
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label>Address  : </label>{{$address}}<br>
                                    <label>Email  : </label>{{$email}}<br>
                                    
                                </div>
                                </div>
                            </div>
                               <br>
          <div class="x_content" style="overflow: scroll;">
          <table  id="myTable" style="border: 1px solid black"  class="table table-striped table-bordered table-hover">                      
          
            <thead>
                <tr>
                  <th>S.No</th>
                  <th>Delivery Date</th>
                 <th style="min-width:120px">Size</th>
                 <th>Qty</th>
                 <th>Amount</th>
                </tr>
                </thead>
            <tbody>

              <?php $j=1; ?>
                <?php for($i = 0; $i < count($qty); $i++) : ?>
                <tr>
                    <td style="width: 5%"><?php echo  $j++ ?></td> 
                    <td width="50%"><b>{{$deliverd_date[$i]}}</b><input type="hidden" value="{{$deliverd_date[$i]}}" name="deliverd_date[]"><br></td> 
                    <td width="20%"><b>{{$sizes[$i]}}</b><input type="hidden" value="{{$sizes[$i]}}" name="sizes[]"><br></td>  
                    <td width="50%"><b> {{$qty[$i]}}</b><input type="hidden" value="{{$qty[$i]}}" name="qty[]"><br></td> 
                    <td width="50%"><b> Rs : {{$amt[$i]}}</b><input type="hidden" value="{{$amt[$i]}}" name="amt[]"><br></td> 
                    </tr>
                    <input type="hidden" value="{{$i+1}}" name="total_photo">
                  <?php endfor ?>
                                            <tr>
                                                <th colspan="3"></th>
                                                <th ><p align="right">Total Amount</p></th>
                                                <th><input type="text" class="form-control" id="subtot" name="subtot" value="{{$grand}}"  readonly ></th>
                                            </tr>
                                            
                                            <tr>
                                                <th colspan="3"></th>
                                                <th ><p align="right">Advance Amount</p></th>
                                                <th><input  type="text" id="txtPassportNumber" name="amountRecieved" onchange="calculateAmount1(this.value)" required /></th>

                                            </tr>
                                            
                                             <tr>
                                                <th colspan="3"></th>
                                                <th ><p align="right">Balance</p></th>
                                               <th><input type="text" class="form-control" id="tot_amount" name="due_amount"   readonly></th>
                                            </tr>
                                            <tr>
                                    <td><b>Payment Mode</b></td>
                                    <td>
                                    <select name="pmMode" class="form-control" id="pmMode" onchange = "selectpaymentmode()" required>
                                        <option value="">Mode</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Online">Online</option>
                                    </select>
                                    </td>
                                </tr>
                                <tr id="trCheq">
                                    <td><b>Cheq No</b></td>
                                    <td>
                                        <input type="text" name="cheqno" class="form-control" id="cheqno">
                                    </td>
                                    <td><b>Cheq Date</b></td>
                                    <td>
                                        <input type="date" class="form-control" name="cheqdate" id="cheqdate">
                                    </td>
                                    <td><b>Bank Name</b></td>
                                    <td>
                                        <input type="text" name="bank_name" class="form-control" id="bank_name">
                                    </td>
                                </tr>
                               <tr id="trTrans">
                                    <td><b>Trans No</b></td>
                                    <td>
                                        <input type="text" name="trans_no" class="form-control" id="trans_no">
                                    </td>
                                    <td><b>Bank Name</b></td>
                                    <td>
                                        <input type="text" name="bank_name1" class="form-control" id="bank_name1">
                                    </td>
                               </tr>
                                   </tbody>
                                    </table>
                                    
                                </div>
                                <br>
                               
                                <!-- /.box-body -->
                                <div class="box-footer">
                                  
                                    <input type="hidden" class="form-control" id="email" name="email" value="{{$email}}">
                                    <input type="hidden" class="form-control" id="address" name="address" value="{{$address}}">
                                    <input type="hidden" class="form-control" id="mobile" name="mobile" value="{{$mobile}}">
                                    <input type="hidden" class="form-control" id="name" name="name" value="{{$name}}">
                                    <input type="hidden" class="form-control" id="name" name="customer_id" value="{{$customer_id}}">

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
<script type="text/javascript">
    window.onload=function()
    {
        document.getElementById("trTrans").style.display='none';
        document.getElementById("trCheq").style.display='none'; 
    }
        function selectpaymentmode()
        { 
    var paymentmode=document.getElementById("pmMode").value;  
    if(paymentmode == "Cheque")
    {
        document.getElementById("trTrans").style.display='none';
        document.getElementById("trCheq").style.display='';
    }
    else if(paymentmode == "Online")
    {
        document.getElementById("trTrans").style.display='';
        document.getElementById("trCheq").style.display='none';
    }
    else
    {
       document.getElementById("trTrans").style.display='none';
        document.getElementById("trCheq").style.display='none'; 
    }
        }
    </script>
    <script>
            function calculateAmount1(val) {
                var tot_paid = val;
                
                var subtot = document.getElementById('subtot').value;

                var grandtot =  parseFloat(subtot)  - parseFloat(tot_paid);
                
                
                /*display the result*/
                var divobj = document.getElementById('tot_amount');
                divobj.value = grandtot;
            }
        </script>

     
@endsection