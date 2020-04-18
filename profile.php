<?php
require_once "includes/Loader.php";
require_once "includes/Session.php";
include("includes/SteamAuth/SteamAuth.class.php");

$auth = new SteamAuth();
$auth->Init();
if(isset($_GET['status']) && $_GET['status'] == "logout") {
    $auth->Logout();
}

if(isset($_POST['dropUser'])) {
    $session = new Session();
    $session->dropUser($auth->SteamID);
    $auth->Logout();
    header("Location: index");
}

if(!$auth->IsUserLoggedIn()) {
    header("Location: index");
}

$loader = new Loader();
$user = Loader::getUserInfo($auth->SteamID);
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="CtBans">
  <meta name="author" content="DreaM">

  <title>JailBreak CT Bans - Profile</title>

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Favicon and Apple Icons -->
  <link rel="icon" type="image/swg" sizes="96x96" href="img/favicon.svg">

  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">
  <div class="loader"></div>
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fab fa-vuejs"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Profile</div>
      </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <?php include "templates/sidebar.php"; ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-gradient-custom topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search ..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            <!-- Nav Item - Alerts -->
            <?php include "templates/navbar-alerts.php";?>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-white small"><b><?php echo Loader::getUserName($auth->SteamID); ?></b></span>
                <img class="img-profile rounded-circle" src="<?php echo Loader::getUserAvatar($auth->SteamID); ?>" alt="user img">
              </a>
                <!-- Dropdown - User Information -->
                <?php include "templates/navbar.php";?>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

          <!-- Begin Page Content -->
          <div class="container-fluid">

              <!-- Page Heading -->
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                  <h1 class="h3 mb-0 text-gray-800">Profile</h1>
              </div>

              <div class="row">

                  <!-- Earnings (Monthly) Card Example -->
                  <div class="col-xl-3 col-md-6 mb-4 onload-animate-zoom">
                      <div class="card border-left-primary shadow h-100 py-2">
                          <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Name</div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?=Loader::getUserName($auth->SteamID)?></div>
                                  </div>
                                  <div class="col-auto">
                                      <i class="fas fa-info fa-2x text-gray-300"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Earnings (Monthly) Card Example -->
                  <div class="col-xl-3 col-md-6 mb-4 onload-animate-zoom">
                      <div class="card border-left-success shadow h-100 py-2">
                          <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Status</div>
                                      <div class="h5 mb-0 font-weight-bold">Active</div>
                                  </div>
                                  <div class="col-auto">
                                      <i class="far fa-id-badge fa-2x text-gray-300"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Earnings (Monthly) Card Example -->
                  <div class="col-xl-3 col-md-6 mb-4 onload-animate-zoom">
                      <div class="card border-left-info shadow h-100 py-2">
                          <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Type</div>
                                      <div class="row no-gutters align-items-center">
                                          <div class="col-auto">
                                              <div class="h5 mb-0 mr-3 font-weight-bold">Admin</div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-auto">
                                      <i class="fas fa-user-tag fa-2x text-gray-300"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Pending Requests Card Example -->
                  <div class="col-xl-3 col-md-6 mb-4 onload-animate-zoom">
                      <div class="card border-left-warning shadow h-100 py-2">
                          <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Action</div>
                                  </div>
                                  <div class="col-auto">
                                      <div class="h5 mb-0 font-weight-bold text-gray-800 text-center"><form method="post"><button name="dropUser" id="dropUser" class="btn btn-danger">Delete User</button></form></div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="row">

                  <div class="col-lg-6">
                      <!-- Dropdown Card Example -->
                      <div class="card shadow mb-4 onload-animate-left">
                          <!-- Card Body -->
                          <div class="card-body text-gray-900">
                              Steam Profile Info:<br>
                              <hr>
                              <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1"><code>SteamID64:</code></span>
                                  </div>
                                  <input type="text" value="<?=$auth->SteamID?>" class="form-control" aria-label="SteamID64" aria-describedby="basic-addon1">
                              </div>
                              <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1"><code>SteamID:</code></span>
                                  </div>
                                  <input type="text" value="<?=Loader::toSteamID($auth->SteamID);?>" class="form-control" aria-label="SteamID64" aria-describedby="basic-addon1">
                              </div>
                              <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1"><code>Status:</code></span>
                                  </div>
                                  <input type="text" value="<?=$user[0]?>" class="form-control" aria-label="Status" aria-describedby="basic-addon1">
                              </div>
                              <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1"><code>Member since:</code></span>
                                  </div>
                                  <input type="text" value="<?=$user[1]?>" class="form-control" aria-label="Custom Name" aria-describedby="basic-addon1">
                              </div>
                              <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1"><code>Custom Name:</code></span>
                                  </div>
                                  <input type="text" value="<?=$user[2]?>" class="form-control" aria-label="Member since" aria-describedby="basic-addon1">
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-6">

                      <!-- Collapsable Card Example -->
                      <div class="card shadow mb-4 onload-animate-bottom">
                          <!-- Card Header - Accordion -->
                          <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                              <h6 class="m-0 font-weight-bold text-primary">Info</h6>
                          </a>
                          <!-- Card Content - Collapse -->
                          <div class="collapse show" id="collapseCardExample">
                              <div class="card-body">
                                  <b>Other profile info</b>
                              </div>
                          </div>
                      </div>

                  </div>

              </div>

          </div>
          <!-- /.container-fluid -->

      </div>
        <!-- End of Main Content -->

      <!-- Footer -->
      <?php include "templates/footer.php"; ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <?php include "templates/logout-modal.php"; ?>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/datatables.js"></script>

</body>

</html>
