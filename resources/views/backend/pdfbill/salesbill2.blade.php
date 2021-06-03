@extends('backend.layouts.master')
@section('title')
    Expenses Tracking Page
@endsection
@section('css')

@endsection
<!-- page content -->
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                 
                
            <div class="clearfix"></div>
            
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        
                        <div class="x_content">
                            <div ng-app="app">
<div class="container" ng-controller="feeRecipt">
                            <div class="row">
    <div id='printMe'>
    <!-- New payment page -->
    <div class="receipt-main col-xs-10 col-sm-10 col-md-8 col-xs-offset-1 col-sm-offset-1 col-md-offset-2" id="paymentRecipt">
   <html>
  <head>
    <meta charset="utf-8" /> <!-- first element so that the encoding is applied to the title etc. -->
   

  </head>
            
                 <body>
    <header> 
      <address class="return-address">
        <div class="col-xs-6 col-sm-4 col-md-4">
            <div class="receipt-left">
                <img class="img-responsive" alt="student-image" src="{{$company->image}}" style="width: 50%;hight: 40%; border-radius: 450px;">
           
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-4">
            <div class="receipt-right">
               <p ><strong>{{$company->company_name}}</strong></p>
                <p >{{$company->city}}</p>
                <p >{{$company->contact_no}}</p>
                 <hr>
            </div>
            
        </div>
         
      </address>
     
    </header>
    
    <div class="content"> <!-- use this div only if it is required for styling -->
        
<table border="0" align="center" class="table table-bordered table-hover" style="width:100%">
    <thead>
    <tr>
        <th class="text-center">S.N.</th>
        <th class="text-center">Product Name</th>
        <th class="text-center">Quantity</th>
        <th class="text-center">GST</th>
        <th class="text-center">Price</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=1 ?>
    @foreach($report as $all)
    <tr>
        <td>{{$i++}}</td>
        <td>{{$all->name}}</td>
        <td>{{$all->quantity}}</td>
        <td>{{$all->gst_percent}}</td>
        <td style="text-align:right">{{$all->price}}</td>
    </tr>
    @endforeach
    
    <tr>
        
        <td colspan="4" style="text-align:right"> Sub Total  </td>
        
        <td style="text-align:right;width:30%">
            
           {{$invoice_details->subtotal}}
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align:right">GST  (+) </td>
        <td style="text-align:right">
            {{$invoice_details->gst_total}}
        </td>
    </tr>
    <tr>
         <td colspan="4" style="text-align:right">Discount  (-) </td>
        <td style="text-align:right">
            {{$invoice_details->discount_amt}}
        </td>
    </tr>
    <tr>
         <td colspan="4" style="text-align:right">Grand Total  </td>
         
        <td style="text-align:right"><?php $grd_total = $invoice_details->grand_total - $invoice_details->discount_amt ?>
            
            {{$grd_total}}
        </td>
    </tr>
    
    <tr>
         <td colspan="4" style="text-align:right"> Paid (-) </td>
        <td style="text-align:right">
           
              {{$invoice_details->paid_amt}}
        </td>
    </tr>
    
    <tr>
         <td colspan="4" style="text-align:right">Total Due  Rs.</td>
        <td style="text-align:right">
            {{$invoice_details->due_amt}}
            
        </td>
    </tr>
    </tbody>
</table>
<br>
<p>Prepared by: {{$all->saller_name}} </p>
<p >Date: {{date("d-m-Y")}}</p>
<p align="center">Thank You</p>






      </div>
        
       
  </body>
        

</html>

</div> 
<button onclick="printDiv('printMe')">Print </button>
    </div>      
    </div> 
    </div>      
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
<script>
        function printDiv(divName){
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;

        }
    </script>
 <link rel="stylesheet" href="letter.css" />
    <style type="text/css">

    .receipt-main {
        background: #ffffff none repeat scroll 0 0;
        border-bottom: 12px solid #333333;
        border-top: 12px solid #f7a62b;
        margin-top: 50px;
        margin-bottom: 50px;
        padding: 40px 30px !important;
        position: relative;
        box-shadow: 0 1px 21px #acacac;
        color: #333333;
        font-family: open sans;
    }
    .receipt-main p {
        color: #333333;
        font-family: open sans;
        line-height: 1.42857;
    }
    .receipt-footer h1 {
        font-size: 15px;
        font-weight: 400 !important;
        margin: 0 !important;
    }
    .receipt-main::after {
        background: #414143 none repeat scroll 0 0;
        content: "";
        height: 5px;
        left: 0;
        position: absolute;
        right: 0;
        top: -13px;
    }
    .receipt-main thead {
        background: #414143 none repeat scroll 0 0;
    }
    .receipt-main thead th {
        color:#fff;
    }
    .receipt-right h5 {
        font-size: 16px;
        font-weight: bold;
        margin: 0 0 7px 0;
    }
    .receipt-right p {
        font-size: 12px;
        margin: 0px;
    }
    .receipt-right p i {
        text-align: center;
        width: 18px;
    }
    .receipt-main td {
        padding: 9px 20px !important;
    }
    .receipt-main th {
        padding: 13px 20px !important;
    }
    .receipt-main td {
        font-size: 13px;
        font-weight: initial !important;
    }
    .receipt-main td p:last-child {
        margin: 0;
        padding: 0;
    }   
    .receipt-main td h2 {
        font-size: 20px;
        font-weight: 900;
        margin: 0;
        text-transform: uppercase;
    }
    .receipt-header-mid .receipt-left h1 {
        font-weight: 100;
        margin: 34px 0 0;
        text-align: right;
        text-transform: uppercase;
    }
    .receipt-header-mid {
        margin: 24px 0;
        overflow: hidden;
    }
</style>
@endsection









