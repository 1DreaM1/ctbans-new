<?php
require_once "includes/Loader.php";
include("includes/SteamAuth/SteamAuth.class.php");

$auth = new SteamAuth();
$auth->Init();
if(isset($_GET['status']) && $_GET['status'] == "logout") {
    $auth->Logout();
}

//$auth->SetOnLogoutCallback(function($steamid){
//    return true;
//});

$loader = new Loader();
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="CTBans">
  <meta name="author" content="DreaM">

  <title>JailBreak CT Bans - Bans</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Favicon and Apple Icons -->
  <link rel="icon" type="image/swg" sizes="96x96" href="img/favicon.svg">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
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
        <div class="sidebar-brand-text mx-3">Bans</div>
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

            <!-- button here !!! -->

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

              <!-- Nav Item - Alerts -->
              <?php include "templates/navbar-alerts.php";?>

              <?php if($auth->IsUserLoggedIn()): ?>

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
              <?php endif; ?>
          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Modal -->
            <div class="modal fade" id="banlistModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ban Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-sm table-hover table-borderless table-responsive-md">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row">Admin Name</th>
                                    <td id="admin"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Admin SteamID</th>
                                    <td id="admin_sid"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Admin Profile</th>
                                    <td id="admin_link"><a href="#" class="badge badge-primary">Profile Link</a></td>
                                </tr>
                                </tbody>
                            </table>
                            <hr>
                            <table class="table table-sm table-hover table-borderless table-responsive-md">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th class="pl-5" scope="col">Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row">Perp Name</th>
                                    <td class="pl-5" id="perp"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Perp SteamID</th>
                                    <td class="pl-5" id="perp_sid"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Perp Profile</th>
                                    <td class="pl-5" id="perp_link"><a href="#" class="badge badge-primary">Profile Link</a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Add Offline Ban -->
            <div class="modal fade" id="addOfflineBanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Offline Ban</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">SteamID</div>
                                </div>
                                <input type="text" class="form-control" id="inputSid" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text pr-4">Time</div>
                                </div>
                                <input type="number" min="0" class="form-control" id="inputTime" value="30" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Reason</div>
                                </div>
                                <input type="text" class="form-control" id="inputReason">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="confirmOfflineBan">Add Ban</button>
                        </div>
                    </div>
                </div>
            </div>

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Bans</h1>
          </div>

            <div class="card shadow mb-4 onload-animate-bottom">
                <div class="card-header py-3 justify-content-between">
                    <h6 class="font-weight-bold text-primary pt-1 mb-0 float-left">Ban List</h6>
                    <!--<a href="#" class="btn btn-info btn-circle btn-sm float-right offlineBan"><i class="fas fa-user-lock"></i></a>-->
                    <?php if($auth->IsUserLoggedIn()): ?><button type="button" class="btn btn-sm btn-outline-primary float-right addOfflineBan">Add Offline Ban</button><?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="display: none;">ID</th>
                                    <th>Name</th>
                                    <th>Created</th>
                                    <th>Length</th>
                                    <th>Reason</th>
                                    <th>Time Left</th>
                                    <?php if($auth->IsUserLoggedIn()): ?><th class="text-center">Actions</th><?php endif; ?>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th style="display: none;">ID</th>
                                    <th>Name</th>
                                    <th>Created</th>
                                    <th>Length</th>
                                    <th>Reason</th>
                                    <th>Time Left</th>
                                    <?php if($auth->IsUserLoggedIn()): ?><th class="text-center">Actions</th><?php endif; ?>
                                </tr>
                            </tfoot>
                            <tbody style="font-size: small; cursor: pointer;">
                            <?php
                                print Loader::createTable($loader->getBans(), $auth->IsUserLoggedIn());
                            ?>
                            </tbody>
                        </table>
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
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
  <script type="text/javascript" src="https://cdn.rawgit.com/mgalante/jquery.redirect/master/jquery.redirect.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/datatables.js"></script>

</body>

</html>