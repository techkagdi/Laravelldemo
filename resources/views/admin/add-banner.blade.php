@extends('admin.includes.main')
@push('title')
    <title>Add Banner</title>
@endpush

@section('content')
      
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                    <div class="card p-4 mt-4">
                            <div class="row">

                            <div class="col-xl-8 col-md-8">
                                @session('msg')
                                    <div class="alert alert-success">{{ session('msg') }}</div>
                                @endsession
                               <h4>Add Website Banner</h4>

                               <div class="row mt-3">
                                    <form method="POST" action="{{url('admin/add-banner')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-lg-12 mb-3">
                                                <label class="form-label">Banner Image <div class="form-text ">* Required Size (1900 X 650) pixels</div></label>
                                                <input type="file" name="banner" class="form-control">
                                                
                                                @error("banner")
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                        </div>

                                        <div class="col-lg-12 mb-3">
                                                <label class="form-label">Alt Text</label>
                                                <input type="text" name="alt" class="form-control" placeholder="discount banner">
                                                @error("alt")
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                        

                                        <div class="col-lg-3">
                                        <button class="btn btn-primary" type="submit">Add Banner</button>
                                        </div>
                                    </form>
                            </div>
                        </div> 
                    </div>     
                </div>
            </main>



                     
        
@endsection