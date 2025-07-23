 <!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">

    <a class="sidebar-brand brand-logo" href="{{route('admin.dashboard')}}">
      {{-- <img src="{{asset('admin/images/logo.svg')}}" alt="logo" /> --}}
      <h3 style="color:white">LAUNDRY</h3>
    </a>

    <a class="sidebar-brand brand-logo-mini" href="{{route('admin.dashboard')}}">
      <img src="{{asset('admin/images/logo-mini.svg')}}" alt="logo" />
    </a>

  </div>
  <ul class="nav">
    <li class="nav-item profile">
      <div class="profile-desc">
        <div class="profile-pic">
          <div class="count-indicator">
            <img class="img-xs rounded-circle" src = {{userImageById(authId())}} alt="User profile picture">
        
            <span class="count bg-success"></span>
          </div>
          <div class="profile-name">
            <h5 class="mb-0 font-weight-normal">{{UserNameById(authId())}}</h5>
          </div>
        </div>
      </div>
    </li>

    <!-- Dashboard Link -->
    @canany(['dashboard-view', 'dashboard-total-order-cards','dashboard-monthly-order-cards','dashboard-revenue-cards','dashboard-graph','dashboard-latest-orders','dashboard-latest-requested-orders'])
      <li class="nav-item menu-items {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
            </span>
            <span class="menu-title">Dashboard</span>
        </a>
      </li>
    @endcanany

    <!-- Role Management Link -->
    <li class="nav-item menu-items {{ request()->routeIs('admin.role.*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('admin.role.list') }}">
          <span class="menu-icon">
              <i class="mdi mdi-contacts"></i>
          </span>
          <span class="menu-title">Role Management</span>
      </a>
    </li>

    {{-- User Management Links --}}
    @canany(['customer-list', 'staff-list','driver-list','trashed-user-list'])
      <li class="nav-item menu-items {{ request()->routeIs('admin.customer.*','admin.driver.*','admin.staff.*','admin.trashed.list') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('admin.customer.*','admin.driver.*','admin.staff.*','admin.trashed.list') ? '' : 'collapsed' }}" data-toggle="collapse" href="#service1" aria-expanded="{{ request()->routeIs('admin.customer.*','admin.driver.*','admin.staff.*','admin.trashed.list') ? 'true' : 'false' }}" aria-controls="service1">
            <span class="menu-icon">
                <i class="mdi mdi-settings"></i>
            </span>
            <span class="menu-title">User Management</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ request()->routeIs('admin.customer.*','admin.driver.*','admin.staff.*','admin.trashed.list') ? 'show' : '' }}" id="service1">
            <ul class="nav flex-column sub-menu">
              @can('customer-list')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.customer.*') ? 'active' : '' }}" href="{{ route('admin.customer.list') }}">Customers</a>
                </li>
              @endcan
              @can('driver-list')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.driver.*') ? 'active' : '' }}" href="{{ route('admin.driver.list') }}">Drivers</a>
                </li>
              @endcan
              @can('staff-list')
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}" href="{{ route('admin.staff.list') }}">Staff</a>
                </li>
              @endcan
              @can('trashed-user-list')
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('admin.trashed.list') ? 'active' : '' }}" href="{{ route('admin.trashed.list') }}">Trashed Users</a>
                </li>
              @endcan
            </ul>
        </div>
      </li>
    @endcanany

    <!-- Vehicle Link -->
    @can('vehicle-list')
      <li class="nav-item menu-items {{ request()->routeIs('admin.vehicle.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.vehicle.list') }}">
            <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
            </span>
            <span class="menu-title">Vehicle Management</span>
        </a>
      </li>
    @endcan

    <!-- Product Management Link -->
    @canany(['service-list', 'variant-list'])
      <li class="nav-item menu-items {{ request()->routeIs('admin.service.*','admin.variant.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('admin.service.*','admin.variant.*') ? '' : 'collapsed' }}" data-toggle="collapse" href="#service" aria-expanded="{{ request()->routeIs('admin.service.*','admin.variant.*') ? 'true' : 'false' }}" aria-controls="service">
            <span class="menu-icon">
                <i class="mdi mdi-settings"></i>
            </span>
            <span class="menu-title">Product Management</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ request()->routeIs('admin.service.*','admin.variant.*') ? 'show' : '' }}" id="service">
            <ul class="nav flex-column sub-menu">
              @can('service-list')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.service.*') ? 'active' : '' }}" href="{{ route('admin.service.list') }}">Services</a>
                </li>
              @endcan
              @can('variant-list')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.variant.*') ? 'active' : '' }}" href="{{ route('admin.variant.list') }}">Variants</a>
                </li>
              @endcan
            </ul>
        </div>
      </li>
    @endcanany

    <!-- Order Management Link -->
    @canany(['InStore-order-list', 'order-list','order-requested-list','order-cancelled-list','transaction-list'])
      <li class="nav-item menu-items {{ request()->routeIs('admin.order.*','admin.transaction.*','admin.storeOrder.*')  ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('admin.order.*','admin.transaction.*','admin.storeOrder.*') ? '' : 'collapsed' }}" data-toggle="collapse" href="#order" aria-expanded="{{ request()->routeIs('admin.order.*','admin.transaction.*','admin.storeOrder.*') ? 'true' : 'false' }}" aria-controls="order">
            <span class="menu-icon">
                <i class="mdi mdi-settings"></i>
            </span>
            <span class="menu-title">Order Management</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ request()->routeIs('admin.order.*','admin.transaction.*','admin.storeOrder.*') ? 'show' : '' }}" id="order">
            <ul class="nav flex-column sub-menu">
              @can('order-requested-list')
                <li class="nav-item">
                    <a class="nav-link {{ request()->type == 'requested' ? 'active' : '' }}" href="{{ route('admin.order.list',['type'=>'requested']) }}">Requested Orders</a>
                </li>
              @endcan
              @can('order-list')
                <li class="nav-item">
                  <a class="nav-link {{ request()->type == 'all' ? 'active' : '' }}" href="{{ route('admin.order.list',['type'=>'all']) }}">Online</a>
                </li>
              @endcan
              @can('InStore-order-list')
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('admin.storeOrder.*') ? 'active' : '' }}" href="{{route('admin.storeOrder.list')}}">In Store</a>
                </li>
              @endcan
              @can('order-cancelled-list')
                <li class="nav-item">
                  <a class="nav-link {{ request()->type == 'cancelled' ? 'active' : '' }}" href="{{ route('admin.order.list',['type'=>'cancelled']) }}">Cancelled Orders</a>
                </li>
              @endcan
              @can('transaction-list')
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('admin.transaction.*') ? 'active' : '' }}" href="{{ route('admin.transaction.list') }}">Transactions</a>
                </li>
              @endcan
            </ul>
        </div>
      </li>
    @endcanany

    <!-- Promotion Link -->
    @can('promotion-list')
      <li class="nav-item menu-items {{ request()->routeIs('admin.promotion.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.promotion.list') }}">
            <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
            </span>
            <span class="menu-title">Promotion Management</span>
        </a>
      </li>
    @endcan

    <!-- Points Management Link -->
    @can('point-list')
      <li class="nav-item menu-items {{ request()->routeIs('admin.points.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.points.list') }}">
            <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
            </span>
            <span class="menu-title">Points Management</span>
        </a>
      </li>
    @endcan

    <!-- Wallets Link -->
    @can('wallet-list')
      <li class="nav-item menu-items {{ request()->routeIs('admin.wallet.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('admin.wallet.list')}}">
            <span class="menu-icon">
                <i class="mdi mdi-bank"></i>
            </span>
            <span class="menu-title">Wallet</span>
        </a>
      </li>
    @endcan

    @canany(['contentPage-about-us','contentPage-privacy-and-policy','contentPage-terms-and-conditions','contentPage-FAQ'])
      <!-- Content Pages Link -->
      <li class="nav-item menu-items {{ request()->routeIs('admin.contentPages.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('admin.contentPages.*') ? '' : 'collapsed' }}" data-toggle="collapse" href="#auth3" aria-expanded="{{ request()->routeIs('admin.card.*', 'admin.category.*') ? 'true' : 'false' }}" aria-controls="auth3">
            <span class="menu-icon">
                <i class="mdi mdi-laptop"></i>
            </span>
            <span class="menu-title">Content Pages</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ request()->routeIs('admin.contentPages.*','admin.f-a-q.*') ? 'show' : '' }}" id="auth3">
            <ul class="nav flex-column sub-menu">
              @can('contentPage-about-us')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.contentPages.detail',['slug' => 'about-us']) }}">About Us</a>
                </li>
              @endcan
              @can('contentPage-privacy-and-policy')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.contentPages.detail',['slug' => 'privacy-and-policy']) }}">Privacy And Policy</a>
                </li>
              @endcan
              @can('contentPage-terms-and-conditions')
                <li class="nav-item">
                  <a class="nav-link " href="{{ route('admin.contentPages.detail',['slug' => 'terms-and-conditions']) }}">Terms And Conditions</a>
                </li>
              @endcan
              @can('contentPage-FAQ')
                <li class="nav-item">
                  <a class="nav-link {{request()->routeIs('admin.f-a-q.*') ? 'active' : ''}}" href="{{ route('admin.f-a-q.list')}}">FAQ</a>
                </li>
              @endcan
            </ul>
        </div>
      </li>
    @endcanany

    <!-- Notification Management Link -->
    @can('notification-list')
      <li class="nav-item menu-items {{ request()->routeIs('admin.notification.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('admin.notification.list')}}">
            <span class="menu-icon">
                <i class="mdi mdi-bell"></i>
            </span>
            <span class="menu-title">Notifications</span>
        </a>
      </li>
    @endcan

    <!-- Tax Link -->
    @can('tax-list')
      <li class="nav-item menu-items {{ request()->routeIs('admin.tax.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.tax.list') }}">
            <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
            </span>
            <span class="menu-title">Tax Management</span>
        </a>
      </li>
    @endcan

    <!-- Income Report Management Link -->
    @can('income-report')
      <li class="nav-item menu-items {{ request()->routeIs('admin.income.list') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.income.list') }}">
            <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
            </span>
            <span class="menu-title">Income Report</span>
        </a>
      </li>
    @endcan

    <!-- Config setting Link -->
    @canany(['config-smtp','config-stripe','config-delivery','config-time-shedule','general-setting'])
      <li class="nav-item menu-items {{ request()->routeIs('admin.config-setting.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('admin.config-setting.*') ? '' : 'collapsed' }}" data-toggle="collapse" href="#auth1" aria-expanded="{{ request()->routeIs('admin.config-setting.*') ? 'true' : 'false' }}" aria-controls="auth1">
            <span class="menu-icon">
                <i class="mdi mdi-settings"></i>
            </span>
            <span class="menu-title">Settings</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ request()->routeIs('admin.config-setting.*') ? 'show' : '' }}" id="auth1">
            <ul class="nav flex-column sub-menu">
              @can('config-smtp')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.config-setting.smtp') ? 'active' : '' }}" href="{{ route('admin.config-setting.smtp') }}">SMTP Information</a>
                </li>
              @endcan
              @can('config-stripe')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.config-setting.stripe') ? 'active' : '' }}" href="{{ route('admin.config-setting.stripe') }}">Stripe Payment</a>
                </li>
              @endcan
              @can('config-delivery')
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('admin.config-setting.delivery-cost') ? 'active' : '' }}" href="{{ route('admin.config-setting.delivery-cost') }}">Delivery Cost</a>
                </li>
              @endcan
              @can('config-time-shedule')
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('admin.config-setting.timeShedule.*') ? 'active' : '' }}" href="{{ route('admin.config-setting.timeShedule.list') }}">Time Shedule</a>
                </li>
              @endcan
              @can('config-general-setting')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.config-setting.general-setting') ? 'active' : '' }}" href="{{ route('admin.config-setting.general-setting') }}">General Setting</a>
                </li>
              @endcan
            </ul>
        </div>
      </li>
    @endcanany

    <!-- Helpdesk Link -->
    @can('helpdesk-list')
      <li class="nav-item menu-items {{ request()->routeIs('admin.helpDesk.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.helpDesk.list',['type' => 'open']) }}">
            <span class="menu-icon">
                <i class="mdi mdi-desktop-mac"></i>
            </span>
            <span class="menu-title">Helpdesk</span>
        </a>
      </li>
    @endcan

    <!-- Log Out Link -->
    <li class="nav-item menu-items">
      <a class="nav-link" href="{{route('admin.logout')}}">
        <span class="menu-icon">
          <i class="mdi mdi-logout"></i>
        </span>
        <span class="menu-title">Log Out</span>
      </a>
    </li>

  </ul>
</nav>
<!-- partial -->