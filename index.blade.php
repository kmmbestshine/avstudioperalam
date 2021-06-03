@extends('layouts.backend.main')

@section('title', 'Client | ' )

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Client
               
            </h1>
            <ol class="breadcrumb">
                <li><i class="fa fa-dashboard"></i> <a href="{{ route('home') }}">Dashboard</a></li>
                <li><a href="{{ route('backend.client.create') }}">Clients</a></li>
               
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="x_content" style="height: 600px;overflow: scroll;">
                            <table style="border: 1px solid black" class="table table-striped table-bordered table-hover" id="categorytable">
        <thead>
        <tr>
            <th>S.no</th>
            <th>Client Name</th>
            <th>Company Name</th>
            <th>Mobile</th>
            <th>Software Type</th>
            <th>Email</th>
            <th>Address</th>
             <th>Land Mark</th>
            
        </tr>
        </thead>
        <tbody>
            <?php $i=1; ?>
            @foreach($clients as $client)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$client->name}}<br>{{$client->lead_no}}</td>
                <td>{{$client->company_name}}</td>
                <td>{{$client->pri_mobile}}<br>{{$client->sec_mobile}}</td>
                <td>{{$client->software_type}}</td>
                <td>{{$client->email}}</td>
                <td>{{$client->address}}</td>
                <td>{{$client->landmark}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./row -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('script')

@endsection