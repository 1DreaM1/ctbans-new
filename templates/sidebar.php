<li class="nav-item" id="index">
    <a class="nav-link" href="index">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
</li>

<li class="nav-item" id="bans">
    <a class="nav-link" href="bans">
        <i class="fas fa-fw fa-user-clock"></i>
        <span>Bans</span></a>
</li>

<?php if($auth->IsUserLoggedIn()): ?>
<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Users
</div>

<li class="nav-item" id="profile">
    <a class="nav-link" href="profile">
        <i class="fas fa-fw fa-user-alt"></i>
        <span>My Profile</span></a>
</li>

<li class="nav-item" id="users">
    <a class="nav-link" href="users">
        <i class="fas fa-fw fa-users-cog"></i>
        <span>Users Management</span>
    </a>
</li>
<?php endif; ?>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Other
</div>

<!-- Nav Item - Tables -->
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="fas fa-fw fa-file-invoice-dollar"></i>
        <span>Main Page</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="fas fa-fw fa-boxes"></i>
        <span>EVIP</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>