@extends('backend.layouts.master')
@section('title')
    Company create Page
@endsection
@section('css')

@endsection
<!-- page content -->
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Company Management </h3>
                </div>
                <div class="title_right">
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                            
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
                            <h2>Create Company</h2>
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
                            <form action="{{route('companiess.update')}}" method="post" enctype="multipart/form-data">
                                {{ csrf_field()}}
                                <div class="form-group">
                                   <div class="col-md-6">
                                    
                                <div class="col-md-9">
                                    <label><strong>Company Name:</strong></label>
                                    <input type="text" class="form-control" placeholder="Enter Company Name" name="company_name" value="{{$companys->company_name}}"/>
                                    <span class="error"><b>
                                           @if($errors->has('company_name'))
                                                {{$errors->first('company_name')}}
                                            @endif</b>
                                     </span>
                                   
                                </div>
                                </div>
                                <div class="col-md-6">
                                   
                                <div class="col-md-9">
                                     <label><strong> Email:</strong></label>
                                    <input type="email" class="form-control" placeholder="Enter Company Email Address" name="company_email" value="{{$companys->email}}"/>
                                    <span class="error"><b>
                                           @if($errors->has('company_email'))
                                                {{$errors->first('company_email')}}
                                            @endif</b>
                                     </span>
                                   
                                </div>
                                </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                    
                                <div class="col-md-9">
                                    <label><strong> Contact No</strong></label>
                                    <input type="text" class="form-control" placeholder="Enter Company Contact Number" name="company_mobile" value="{{$companys->contact_no}}"/>
                                    <span class="error"><b>
                                           @if($errors->has('company_mobile'))
                                                {{$errors->first('company_mobile')}}
                                            @endif</b>
                                     </span>
                                    
                                </div>
                                </div>
                                <div class="col-md-6">
                                    
                                <div class="col-md-9">
                                    <label><strong> Address:</strong></label>
                                    <input type="text" class="form-control" placeholder="Enter Company Address" name="company_address" value="{{$companys->address}}"/>
                                    <span class="error"><b>
                                           @if($errors->has('company_address'))
                                                {{$errors->first('company_address')}}
                                            @endif</b>
                                     </span>
                                    
                                </div>
                                </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                    
                                <div class="col-md-9">
                                    <label><strong> City:</strong></label>
                                    <input type="text" class="form-control" placeholder="Enter company City" name="company_city" value="{{$companys->city}}"/>
                                    <span class="error"><b>
                                           @if($errors->has('company_city'))
                                                {{$errors->first('company_city')}}
                                            @endif</b>
                                     </span>
                                    
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="col-md-9">
                                    <label><strong> Image:</strong></label>

                                    <input type="file" class="form-control" placeholder="logo" name="company_image" value="{{$companys->image}}"/>
                                    <span class="error"><b>
                                           @if($errors->has('company_image'))
                                                {{$errors->first('company_image')}}
                                            @endif</b>
                                     </span>
                                   
                                </div>
                                </div>
                                </div>
                                <div class="form-group">
                                   
                               <div class="col-md-6">
                                    
                                <div class="col-md-9">
                                    <input type="hidden" class="form-control" placeholder="logo" />
                                    <button type="submit" name="btnCreate" class="btn btn-primary">Save Role</button>
                                </div>
                                </div>
                                
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
<style>
.newspaper {
    -webkit-column-count: 6; /* Chrome, Safari, Opera */
    -moz-column-count: 6; /* Firefox */
    column-count: 6;
}
</style>
<style>
.permissions {
    -webkit-column-count: 6; /* Chrome, Safari, Opera */
    -moz-column-count: 6; /* Firefox */
    column-count: 6;
}
</style>
<script>
    $('.search-dropdown input[type="checkbox"]').on("change", function(){
    var categories = [];
    $('.checkbox:checked').each(function(){        
        var category = $(this).next().text();
        categories.push(category);
    });
    $(".category-holder").html(categories.join(", "));
    if (!$(".category-holder").text().trim().length) {
    $(".category-holder").text("Select Category");
    }
});
</script>
@endsection