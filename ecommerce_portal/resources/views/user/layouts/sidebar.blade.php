
@php
    $user = auth()->user();
    $billing = null;
    if($user && $user->is_login){
    $billing = \App\Models\Billing::where('user_id', $user->user_id)->first();
    }
@endphp

<div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                           
                            <a class="nav-link" href="{{url('user/' .$user->user_id)}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>

                            <a class="nav-link" href="{{url('user/order-history/' .$user->user_id)}}">
                                <div class="sb-nav-link-icon"><i class="fa-regular fa-clock"></i></div>
                                Order History
                            </a>
                            <a class="nav-link" href="{{url('user/settings/' .$user->user_id)}}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                                Settings
                            </a>
                            
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                    {{$billing->fullname}}
                    </div>
                </nav>
            </div>