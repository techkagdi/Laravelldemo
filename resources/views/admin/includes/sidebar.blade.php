 <div id="layoutSidenav">
     <div id="layoutSidenav_nav">
         <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
             <div class="sb-sidenav-menu">
                 <div class="nav">

                     <a class="nav-link" href="{{url('admin')}}">
                         <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                         Dashboard
                     </a>

                     <li class="nav-item dropdown">
                         <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                             <div class="sb-nav-link-icon"><i class="fa-brands fa-shopify"></i></div>Manage Category
                         </a>
                         <ul class="dropdown-menu">
                             <li><a class="dropdown-item" href="{{url('admin/add-category')}}">Add</a></li>
                             <li><a class="dropdown-item" href="{{url('admin/view-category')}}">View</a></li>

                         </ul>
                     </li>
                     <li class="nav-item dropdown">
                         <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                             <div class="sb-nav-link-icon"><i class="fa-brands fa-shopify"></i></div>Products
                         </a>
                         <ul class="dropdown-menu">
                             <li><a class="dropdown-item" href="{{url('admin/add-product')}}">Add</a></li>
                             <li><a class="dropdown-item" href="{{url('admin/view-product')}}">View</a></li>

                         </ul>
                     </li>
                     <a class="nav-link" href="{{url('admin/users')}}">
                         <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                         Manage Users
                     </a>

                     <!-- <a class="nav-link" href="{{url('admin/vendors')}}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-users-rectangle"></i></div>
                            Manage Vendors
                            </a> -->

                     <a class="nav-link" href="{{url('admin/orders')}}">
                         <div class="sb-nav-link-icon"><i class="fa-solid fa-arrow-down-short-wide"></i></div>
                         Manage Orders
                     </a>
                     <hr>
                     <a class="nav-link" href="{{url('/')}}" target="_blank">
                         <div class="sb-nav-link-icon"><i class="fa-solid fa-globe"></i></div>
                         Website
                     </a>
                     <li class="nav-item dropdown">
                         <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                             <div class="sb-nav-link-icon"><i class="fa-brands fa-shopify"></i></div>Reports
                         </a>
                         <ul class="dropdown-menu">
                             <li><a class="dropdown-item" href="{{url('admin/product-report')}}">Product Report</a></li>
                             <li><a class="dropdown-item" href="{{url('admin/order-report')}}">Order Report</a></li>
                         </ul>
                     </li>
                     <li class="nav-item dropdown">
                         <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                             <div class="sb-nav-link-icon"><i class="fa-solid fa-image"></i></div>Website Banner
                         </a>
                         <ul class="dropdown-menu">
                             <li><a class="dropdown-item" href="{{url('admin/add-banner')}}">Add</a></li>
                             <li><a class="dropdown-item" href="{{url('admin/view-banner')}}">View</a></li>

                         </ul>
                     </li>






                 </div>
             </div>
             <div class="sb-sidenav-footer">
                 <div class="small">Logged in as:</div>
                 Admin
             </div>
         </nav>
     </div>