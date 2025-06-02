 <!-- Navbar -->
 {{-- main-header navbar navbar-expand navbar-dark navbar-gray --}}
 <nav class="main-header navbar navbar-expand navbar-dark navbar-navy">
     <!-- Left navbar links -->
     <ul class="navbar-nav">
         <li class="nav-item">
             <a class="nav-link" data-widget="pushmenu" href="javascript:void(0)" role="button"><i class="fas fa-bars"></i></a>
         </li>

         <li class="nav-item d-none d-sm-inline-block">
             <a href="/data-dashboard" class="nav-link">Dashboard</a>
         </li>

         {{-- <li class="nav-item d-none d-sm-inline-block">
             <a href="/pengaturan-password" class="nav-link">Password</a>
         </li> --}}

         {{-- <li class="nav-item d-none d-sm-inline-block">
             <a href="/git-log" class="nav-link">Git Log</a>
         </li> --}}

     </ul>

     <!-- Right navbar links -->
     <ul class="navbar-nav ml-auto">
         <li class="nav-item">
             <a class="nav-link" data-widget="fullscreen" href="javascript:void(0)" role="button">
                 <i class="fas fa-expand-arrows-alt"></i>
             </a>
         </li>

         {{-- <li class="nav-item">
             <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="javascript:void(0)" role="button">
                 <i class="fas fa-th-large"></i>
             </a>
         </li> --}}

         <li class="nav-item">
             <form action="/logout" method="post">
                 @csrf
                 <button type="submit" class="mt-1 btn btn-danger btn-block btn-sm">
                     Logout</button>
             </form>
         </li>
     </ul>
 </nav>
 <!-- /.navbar -->

 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Brand Logo -->
     <a href="javascript:void(0)" class="brand-link">
         <img src="/img/logo-pia.png" alt="Logo" class="brand-image img-circle" style="opacity: .8">
         <span class="brand-text font-weight-light">{{ session('nama_level') }}</span>
         {{-- <span class="brand-text font-weight-light">{{ Auth::user()->unit->alias_unit }} - {{ Auth::user()->jenjang->alias_jenjang }}</span> --}}
     </a>

     <!-- Sidebar -->
     <div class="sidebar">
         <!-- Sidebar user panel (optional) -->
         <div class="user-panel mt-3 pb-3 mb-3 d-flex">
             <div class="image">
                 @if (session('kode_unit') == '001')
                     <img src="/admin_template/dist/img/avatar4.png" class="img-circle elevation-2" alt="User Image">
                 @else
                     <img src="/admin_template/dist/img/avarat_pi.png" class="img-circle elevation-2" alt="User Image">
                 @endif
             </div>
             <div class="info">
                 <a href="javascript:void(0)" class="d-block">{{ session('nama_pegawai') }}</a>
             </div>
         </div>

         <!-- Sidebar Menu -->
         <nav class="mt-2">
             <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                 data-accordion="false">

                 @php
                     echo getMenu();
                 @endphp

                 @can('superadmin')
                     <li style="color: rgb(34, 197, 72)" class="nav-header">USER</li>

                     <li class="nav-item">
                         <a href="/admin/level" class="nav-link {{ $active === 'level' ? 'active' : '' }}">
                             <i class="nav-icon fas fa-trophy"></i>
                             <p>
                                 Level
                             </p>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a href="/admin/user" class="nav-link {{ $active === 'user' ? 'active' : '' }}">
                             <i class="nav-icon fas fa-user"></i>
                             <p>
                                 User
                             </p>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a href="/admin/menu" class="nav-link {{ $active === 'menu' ? 'active' : '' }}">
                             <i class="nav-icon fas fa-bars"></i>
                             <p>
                                 Menu
                             </p>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a href="/admin/akses" class="nav-link {{ $active === 'akses' ? 'active' : '' }}">
                             <i class="nav-icon fas fa-key"></i>
                             <p>
                                 Akses
                             </p>
                         </a>
                     </li>
                 @endcan


             </ul>
         </nav>
     </div>

 </aside>
