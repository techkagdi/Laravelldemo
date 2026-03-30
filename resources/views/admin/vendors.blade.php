@extends('admin.includes.main')
@push('title')
    <title>Vendors</title>
@endpush
@php
    $vendors = App\Models\Vendor::all();
@endphp

@section('content')
       
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <div class="card p-4 mt-4">
                            <div class="row">
                                <div class="col-xl-12 col-md-12">
                                    <div class="d-flex">
                                            <h4>Vendors</h4>
                                    </div>
                                    <div class="mt-3">
                                        <table id="datatablesSimple">
                                            <thead>
                                                <tr>
                                                
                                                <th scope="col">Sr No.</th>
                                                <th scope="col">Vendor Name</th>
                                                <th scope="col">Phone No.</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Address</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($vendors as $vendor)
                                                <tr>
                                               
                                                <td>{{$vendor->v_id}}</td>
                                                <td>{{$vendor->full_name}}</td>
                                                <td>+91 {{$vendor->phone}}</td>
                                                <td>{{$vendor->email}}</td>
                                                <td>{{$vendor->address}}</td>

                                                <td>
                                                    @php
                                                        $statusClass = match($vendor->status){
                                                        'unverified' => 'text-bg-danger',
                                                        'verified' => 'text-bg-success',
                                                    }
                                                    @endphp

                                                    
                                                    <span class="badge {{ $statusClass }}">{{$vendor->status}}</span>

                                                </td>
                                            
                                                
                                                <td>

                                                   
                                                    <a href="{{ route('vendor.verify', $vendor->v_id) }}" class="btn btn-success btn-sm" title="Verify">
                                                    <i class="fa-solid fa-check"></i>
                                                    </a>
                                                    <a href="{{ route('vendor.unverify', $vendor->v_id) }}" class="btn btn-danger btn-sm" title="Unverify">
                                                    <i class="fa-solid fa-xmark"></i>                                                  
                                                    </a>
                                                </td>
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