@extends('admin.includes.main')
@push('title')
    <title>Users</title>
@endpush

@php
    $users = \App\Models\User::with('billing')->get();
@endphp

@section('content')
       
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <div class="card p-4 mt-4">
                            <div class="row">
                                <div class="col-xl-12 col-md-12">
                                    <div class="d-flex">
                                            <h4>Users</h4>
                                    </div>
                                    <div class="mt-3">
                                        <table id="datatablesSimple">
                                            <thead>
                                                <tr>
                                                
                                                <th scope="col">Sr No.</th>
                                                <th scope="col">Customer Name</th>
                                                <th scope="col">Phone No.</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Address</th>
                                                <!-- <th scope="col">Status</th>
                                                <th scope="col">Action</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)

                                                <tr>
                                               
                                                <td>{{ $user->user_id}}</td>
                                                <td>{{ $user->billing->fullname ?? '-' }}</td>
                                                <td>+91 {{ $user->phone }} </td>
                                                <td>{{ $user->billing->email ?? '-' }}</td>
                                                <td>{{ $user->billing->address ?? '-' }}</td>
                                                <!-- <td>
                                                    <span class="badge text-bg-success">Unblock</span>

                                                </td> -->
                                            
                                                
                                                <!-- <td>
                                                   
                                                    <a href="#" class="btn btn-success btn-sm">
                                                    <i class="fa-solid fa-shield"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm">
                                                   <i class="fa-solid fa-ban"></i>
                                                    </a>
                                                </td> -->
                                                </tr>
                                                @endforeach


                                                 
                                            </tbody>
                                            </table>                                   
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                    </div>
                </main>



                     
        
@endsection