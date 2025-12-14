<aside class="codex-sidebar">
    <div class="logo-gridwrap">
        <a class="codexbrand-logo" href="{{route('dashboard')}}">
           <h2>{{config('app.name')}} <sub class="text-danger font-weight-bold" style="font-size: 11px;">v1.0</sub></h2>
        </a>
        <!-- <a class="codexbrand-logo" href="{{route('dashboard')}}"><img class="img-fluid"
                src="{{asset('')}}assets/images/logo/logo.png" alt="theeme-logo"></a><a class="codex-darklogo"
            href="{{route('dashboard')}}"><img class="img-fluid" src="{{asset('')}}assets/images/logo/dark-logo.png"
                alt="theeme-logo">
        </a> -->
        <div class="sidebar-action"><i data-feather="menu"></i></div>
    </div>
    <div class="icon-logo">
        <a href="{{route('dashboard')}}">
            <!-- <img class="img-fluid" src="{{asset('')}}assets/images/logo/icon-logo.png" alt="theeme-logo"> -->
        </a>
    </div>
    <div class="codex-menuwrapper">
        <ul class="codex-menu custom-scroll" data-simplebar>
            <li class="cdxmenu-title">
                <h5>হোম</h5>
            </li>
            <li class="menu-item">
                <a href="{{route('dashboard')}}">
                    <div class="icon-item"><i data-feather="home"></i></div><span>ড্যাশবোর্ড</span>
                </a>
            </li>

            <li class="cdxmenu-title">
                <h5>অ্যাপ্লিকেশান</h5>
            </li>
            @can('view member')
            <li class="menu-item">
                <a href="{{route('members.index')}}">
                    <div class="icon-item">
                        <i data-feather="users"></i>
                    </div>
                    <span>জাকের</span>
                </a>
            </li>
            @endcan
            @canany(['view khedmot', 'view rent', 'view kollyan'])
            <li class="menu-item">
                <a href="javascript:void(0);">
                    <div class="icon-item"><i data-feather="message-square"></i></div><span>খেদমত সংগ্রহ</span>
                    <i class="fa fa-angle-down"></i>

                </a>
                <ul class="submenu-list">
                    @can('view khedmot')
                    <li><a href="{{route('khedmots.index')}}">খেদমত/মানত</a></li>
                    @endcan
                    @can('view rent')
                    <li><a href="javascript:void(0);">ভাড়া</a></li>
                    @endcan
                    @can('view kollyan')
                    <li><a href="javascript:void(0);">কল্যাণ</a></li>
                    @endcan
                </ul>
            </li>
            @endcan
            @can('view fund-collection')
            <li class="menu-item">
                <a href="javascript:void(0);">
                    <div class="icon-item"><i data-feather="dollar-sign"></i></div><span>খেদমত লেনদেন</span>
                    <i class="fa fa-angle-down"></i>
                </a>
                <ul class="submenu-list">
                    <li><a href="{{route('fund.receive.index')}}">খেদমত জমা </a></li>
                    @hasanyrole('Super Admin| Admin')
                    <li><a href="{{route('fund.pay.index')}}">খেদমত খরচ </a></li>
                    @endhasanyrole
                </ul>
            </li>
            @endcan
            @can('view user')
            <li class="menu-item">
                <a href="javascript:void(0);">
                    <div class="icon-item"> <i data-feather="user"></i></div><span>ম্যানেজ ইউজার </span><i
                        class="fa fa-angle-down"></i>
                </a>
                <ul class="submenu-list">
                    @can('view user')
                    <li><a href="{{route('users.index')}}">ইউজার'স</a></li>
                    @endcan
                    @can('view role')
                    <li><a href="{{route('roles.index')}}">রোল'স </a></li>
                    @endcan
                    @can('view permission')
                    <li><a href="{{route('permissions.index')}}">পারমিশন</a></li>
                    @endcan
                </ul>
            </li>
            @endcan

            @can('view setting')
            <li class="menu-item"><a href="javascript:void(0);">
                    <div class="icon-item"> <i data-feather="user"></i></div><span>সেটিং </span><i
                        class="fa fa-angle-down"></i>
                </a>
                <ul class="submenu-list">
                    @can('view program')
                    <li><a href="{{route('program-types.index')}}">অনুষ্ঠান ধরন</a></li>
                    @endcan
                </ul>
            </li>
            @endcan

            @can('view report')
            <li class="menu-item">
                <a href="javascript:void(0);">
                    <div class="icon-item">
                        <i data-feather="user"></i>
                    </div>
                    <span>রিপোর্টস </span>
                    <i class="fa fa-angle-down"></i>
                </a>
                <ul class="submenu-list">
                    <li><a href="{{route('reports.index')}}">কর্মী রিপোর্ট</a></li>

                </ul>
            </li>
            @endcan

        </ul>
    </div>
</aside>
