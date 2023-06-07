 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Brand Logo -->
     <a href="index3.html" class="brand-link">
         <img src="{{ asset('admin/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
         <span class="brand-text font-weight-light">AdminLTE 3</span>
     </a>

     <!-- Sidebar -->
     <div class="sidebar">

         <!-- Sidebar Menu -->
         <nav class="mt-2">
             <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                 data-accordion="false">
                 <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                 @role('Super Admin')
                     <li class="nav-item">
                         <a href="#" class="nav-link">
                             <i class="nav-icon fas fa-user"></i>
                             <p>
                                 Users Management
                                 <i class="fas fa-angle-left right"></i>
                             </p>
                         </a>
                         <ul class="nav nav-treeview">
                             <li class="nav-item">
                                 <a href="{{ route('users.index') }}" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p>Users</p>
                                 </a>
                             </li>

                         </ul>
                     </li>
                 @endrole

                 <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="nav-icon fas fa-folder"></i>
                         <p>
                             Categories Management
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">

                         <li class="nav-item">
                             <a href="{{ route('categories.index') }}" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Categories</p>
                             </a>
                         </li>


                     </ul>
                 </li>

                 <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="nav-icon fas fa-box"></i>
                         <p>
                             Products Management
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">

                         <li class="nav-item">
                             <a href="{{ route('products.index') }}" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Products</p>
                             </a>
                         </li>


                     </ul>
                 </li>

                 <li class="nav-item">

                     <!-- Authentication -->
                     <form method="POST" action="{{ route('logout') }}">
                         @csrf
                         <a class="nav-link" href="route('logout')"
                             onclick="event.preventDefault();
                            this.closest('form').submit();">
                             <i class="nav-icon fas fa-sign-out-alt"></i>
                             {{ __('Log Out') }}
                         </a>
                     </form>

                 </li>


             </ul>
         </nav>
         <!-- /.sidebar-menu -->
     </div>
     <!-- /.sidebar -->
 </aside>
