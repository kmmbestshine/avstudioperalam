@extends('backend.layouts.master')
@section('title')
    Make Fund Transfer Page
@endsection
@section('css')

@endsection
<!-- page content -->
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Make Fund Transfer </h3>
                </div>
                <div class="title_right">
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group top_search" style="padding-left: 130px;">
                            <div class="input-group">
                                <a href="{{route('fundtransfer.amount.list')}}" class="btn btn-success">View Fund Transfer</a>
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
                            <h2>Make Fund Transfer</h2>
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
                            <form action="{{route('fundtransfer.amt.verify')}}" method="post">
                                {{ csrf_field()}}
                                <div class="form-group">
                                    <label for="account_id">Choose Account</label>
                                    <select class="form-control" id="account_id" name="account_id">
                                        <option value="">--Select Account--</option>
                                        @foreach($acc_list as $m)
                                            <option value="{{$m->id}}">{{$m->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="error"><b>
                                       @if($errors->has('account_id'))
                                                {{$errors->first('account_id')}}
                                            @endif</b>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="name"> Name*</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                                    <span class="error"><b>
                                           @if($errors->has('name'))
                                                {{$errors->first('name')}}
                                            @endif</b>
                                     </span>
                                </div>
                                <div class="form-group">
                                    <label for="bankname"> Bank Name*</label>
                                    <input type="text" class="form-control" id="bankname" name="bankname" placeholder="Enter bankname">
                                    <span class="error"><b>
                                           @if($errors->has('bankname'))
                                                {{$errors->first('bankname')}}
                                            @endif</b>
                                     </span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="accountno">Account No*</label>
                                    <input type="text" class="form-control" id="accountno" name="accountno" placeholder="Enter Account Number">
                                    <span class="error"><b>
                                           @if($errors->has('accountno'))
                                                {{$errors->first('accountno')}}
                                            @endif</b>
                                     </span>
                                </div>
                                 <div class="form-group">
                                    <label for="branchname">Branch Name*</label>
                                    <input type="text" class="form-control" id="branchname" name="branchname" placeholder="Enter Branch Name">
                                    <span class="error"><b>
                                           @if($errors->has('branchname'))
                                                {{$errors->first('branchname')}}
                                            @endif</b>
                                     </span>
                                </div>
                                <div class="form-group">
                                    <label for="amount">Transfer Amount*</label>
                                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter amount">
                                    <span class="error"><b>
                                         @if($errors->has('amount'))
                                                {{$errors->first('amount')}}
                                            @endif</b></span>
                                </div>
                                <div class="form-group">
                                    <label for="ifsccode">IFSC Code*</label>
                                    <input type="text" class="form-control" id="ifsccode" name="ifsccode" placeholder="Enter IFSC Code">
                                    <span class="error"><b>
                                           @if($errors->has('ifsccode'))
                                                {{$errors->first('ifsccode')}}
                                            @endif</b>
                                     </span>
                                </div>
                                 <div class="form-group">
                                    <label for="mobile">Mobile*</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile">
                                    <span class="error"><b>
                                           @if($errors->has('mobile'))
                                                {{$errors->first('mobile')}}
                                            @endif</b>
                                     </span>
                                </div>
                                
                                
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" name="btnCreate" class="btn btn-primary" >Make Transfer</button>
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
    <script src="{{asset('backend/plugins/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript">
        $(function(){
            var $foo = $('#name');
            var $bar = $('#slug');
            function onChange() {
                $bar.val($foo.val().replace(/\s+/g, '-').toLowerCase());
            };
            $('#name')
                .change(onChange)
                .keyup(onChange);
        });
    </script>
@endsection