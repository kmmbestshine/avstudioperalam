@extends('backend.layouts.master')
@section('title')
    Fund Transfer Preview Page
@endsection
@section('css')

@endsection
<!-- page content -->
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Fund Transfer Management </h3>
                </div>
                <div class="title_right">
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group top_search" style="padding-left: 130px;">
                           <!-- <div class="input-group">
                                <a href="{{route('esevai.service.create')}}" class="btn btn-success">Create E-sevai</a>
                            </div>-->
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
                            <h2>Fund Transfer Preview</h2>
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
                            <form action="{{route('fundtransfer.amt.store')}}" method="post">
                                {{ csrf_field()}}
                                <div class="row">
                              <div class="col-md-6">
                                    <div class="form-group">
                                    <label >Fund Transfer From: </label>{{$from_account_id}}<br>
                                    <label >Name  : </label>{{$name}}<br>
                                    <label>Mobile  : </label>{{$mobile}}<br>
                                    <label>IFSC Code  : </label>{{$ifsccode}}<br>
                                </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label>Bank Name  : </label>{{$bankname}}<br>
                                    <label >Account No  : </label>{{$accountno}}<br>
                                    <label >Branch Name  : </label>{{$branchname}}<br>
                                    <label>Transfer Amount  : </label>{{$amount}}<br>
                                    <label>Commision Amount  : </label><input type="text" class="form-control" id="commision" name="commision" ><br>
                                </div>
                                </div>
                            </div>
                            
                               <br>
          
                                <!-- /.box-body -->
                                <div class="box-footer">
                                  
                                    <input type="hidden" class="form-control" id="accountno" name="accountno" value="{{$accountno}}">
                                    <input type="hidden" class="form-control" id="branchname" name="branchname" value="{{$branchname}}">
                                    <input type="hidden" class="form-control" id="amount" name="amount" value="{{$amount}}">
                                    <input type="hidden" class="form-control" id="name" name="name" value="{{$name}}">
                                    <input type="hidden" class="form-control" id="ifsccode" name="ifsccode" value="{{$ifsccode}}">
                                    <input type="hidden" class="form-control" id="bankname" name="bankname" value="{{$bankname}}">
                                    <input type="hidden" class="form-control" id="mobile" name="mobile" value="{{$mobile}}">
                                    <input type="hidden" class="form-control" id="from_account_id" name="from_account_id" value="{{$from_account_id}}">

                                    <button type="submit" name="btnCreate" class="btn btn-primary" >Fund Transfer Bill</button>
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