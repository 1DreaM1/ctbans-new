<?php
require_once "includes/Loader.php";
require_once "includes/Session.php";
include("includes/SteamAuth/SteamAuth.class.php");

$auth = new SteamAuth();
$auth->Init();
if(isset($_GET['status']) && $_GET['status'] == "logout") {
    $auth->Logout();
}

if(!$auth->IsUserLoggedIn()) {
    header("Location: index");
}

$loader = new Loader();
$user = Loader::getUserInfo($auth->SteamID);

$session = new Session();
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="CtBans">
  <meta name="author" content="DreaM">

  <title>JailBreak CT Bans - Users Management</title>

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
        <div class="sidebar-brand-text mx-3">Users Management</div>
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

          <!-- Modal -->
          <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModal" aria-hidden="true">
              <form class="modal-dialog form-inline" role="document" method="post">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <div class="col-auto">
                              <label class="sr-only" for="sid">SteamID64</label>
                              <div class="input-group mb-2">
                                  <div class="input-group-prepend">
                                      <div class="input-group-text">SteamID64</div>
                                  </div>
                                  <input type="text" class="form-control" id="sid" placeholder="STEAM_1:0:201071885">
                              </div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary actionBtn" data-action="add">Save</button>
                      </div>
                  </div>
              </form>
          </div>

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
                  <h1 class="h3 mb-0 text-gray-800">Users Management</h1>
              </div>

              <div class="row">

                  <div class="col-lg-8">
                      <!-- Dropdown Card Example -->
                      <div class="card shadow mb-4">
                          <!-- Card Header - Dropdown -->
                          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                              <h6 class="m-0 font-weight-bold text-primary">Users Table</h6>
                          </div>
                          <!-- Card Body -->
                          <div class="card-body text-gray-900 table-responsive">
                              <table class="table table-hover table-sm table-borderless" id="dataTableUsers">
                                  <thead class="thead-dark">
                                  <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Name</th>
                                      <th scope="col">SteamID64</th>
                                      <th scope="col">Date</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  <?php print $session->createUsersTable(); ?>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-4">

                      <!-- Collapsable Card Example -->
                      <div class="card shadow mb-4">
                          <!-- Card Header - Accordion -->
                          <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                              <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                          </a>
                          <!-- Card Content - Collapse -->
                          <div class="collapse show" id="collapseCardExample">
                              <div class="card-body">
                                  <div class="my-2"></div>
                                  <a href="#" class="btn btn-success btn-icon-split" data-toggle="modal" data-target="#addUserModal">
                                    <span class="icon text-white-50">
                                      <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Add User</span>
                                  </a>
                                  <a href="#" class="btn btn-danger btn-icon-split actionBtn" data-action="delete">
                                    <span class="icon text-white-50">
                                      <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">Delete User</span>
                                  </a>
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
  <script src="js/users-actions.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/datatables.js"></script>

</body>

</html>
