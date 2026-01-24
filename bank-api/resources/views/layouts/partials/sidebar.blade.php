<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <div class="sidebar-brand">
          <a href="{{ url('admin/dashboard') }}" class="brand-link">
              <img src="{{ asset('backend/dist/img/AdminLTELogo.png') }}" alt="Logo"
                  class="brand-image opacity-75 shadow" />
              <span class="brand-text fw-light">Admin</span>
          </a>
      </div>
      <div class="sidebar-wrapper">
          <nav class="mt-2">
              <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                  aria-label="Main navigation" data-accordion="false" id="navigation">
                  <li class="nav-item">
                      <a href="{{ url('admin/dashboard') }}"
                          class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-tachometer-alt"></i>
                          <p> Dashboard </p>
                      </a>
                  </li>
 

                  
<li class="nav-item"> 
            <a href="{{ url("admin/accounts") }}" class="nav-link {{ request()->is("admin/accounts*") ? "active" : "" }}">
            <i class="nav-icon fas fa-angle-double-right"></i> <p>Accounts</p>
</a>
</li>
 
 


<li class="nav-item"> 
            <a href="{{ url("admin/companies") }}" class="nav-link {{ request()->is("admin/companies*") ? "active" : "" }}">
            <i class="nav-icon fas fa-angle-double-right"></i> <p>Companies</p>
</a>
</li>
 
 


<li class="nav-item"> 
            <a href="{{ url("admin/banks") }}" class="nav-link {{ request()->is("admin/banks*") ? "active" : "" }}">
            <i class="nav-icon fas fa-angle-double-right"></i> <p>Banks</p>
</a>
</li>
 
 


<li class="nav-item"> 
            <a href="{{ url("admin/branches") }}" class="nav-link {{ request()->is("admin/branches*") ? "active" : "" }}">
            <i class="nav-icon fas fa-angle-double-right"></i> <p>Branches</p>
</a>
</li>
 
 


<li class="nav-item"> 
            <a href="{{ url("admin/accounts") }}" class="nav-link {{ request()->is("admin/accounts*") ? "active" : "" }}">
            <i class="nav-icon fas fa-angle-double-right"></i> <p>Accounts</p>
</a>
</li>
 
 


<li class="nav-item"> 
            <a href="{{ url("admin/transactions") }}" class="nav-link {{ request()->is("admin/transactions*") ? "active" : "" }}">
            <i class="nav-icon fas fa-angle-double-right"></i> <p>Transactions</p>
</a>
</li>
 
 


<li class="nav-item"> 
            <a href="{{ url("admin/users") }}" class="nav-link {{ request()->is("admin/users*") ? "active" : "" }}">
            <i class="nav-icon fas fa-angle-double-right"></i> <p>Users</p>
</a>
</li>
 
 

{{-- URLPath --}}

                  {{-- reportURLPath --}}

              </ul>
          </nav>
      </div>
  </aside>