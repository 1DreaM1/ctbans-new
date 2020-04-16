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

  <title>JailBreak CT Bans</title>

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
        <div class="sidebar-brand-text mx-3">CTBANS</div>
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

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <?php if(!$auth->IsUserLoggedIn()): ?><a href="login" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-sign-in-alt fa-sm text-white-50"></i> Log IN</a><?php endif; ?>
          </div>

          <!-- Content Row -->
          <div class="row">

            <!-- Obrat (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Bans</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $loader->getBansCount(); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-ban fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

              <!-- Obrat (Dayly) Card Example -->
              <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card border-left-warning shadow h-100 py-2">
                      <div class="card-body">
                          <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                  <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Expired</div>
                                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $loader->getBansCount("WHERE `removed`='E'"); ?></div>
                              </div>
                              <div class="col-auto">
                                  <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

            <!-- Receipts Count Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Unbanned</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $loader->getBansCount("WHERE `removed`='R'"); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-unlock-alt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Items Count Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Permanent</div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $loader->getBansCount("WHERE `removed`='N' AND `timeleft`='-1'"); ?></div>
                        </div>
                        <div class="col">
                          <div class="progress progress-sm mr-2">
                              <?php
                                $permanent = $loader->getBansCount("WHERE `removed`='N' AND `timeleft`='-1'");
                                $bans = $loader->getBansCount();
                              ?>
                            <div class="progress-bar bg-info" role="progressbar" style="width: <?=(100/$bans) * $permanent?>%" aria-valuenow="<?=(100/$bans) * $permanent?>" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user-lock fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

            <div id="tableDiv">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary" style="margin-bottom:0">Ban List</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th style="display: none;">ID</th>
                                    <th>Name</th>
                                    <th>Admin Name</th>
                                    <th>Created</th>
                                    <th>Length</th>
                                    <th>Time Left</th>
                                    <th>Reason</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th style="display: none;">ID</th>
                                    <th>Name</th>
                                    <th>Admin Name</th>
                                    <th>Created</th>
                                    <th>Length</th>
                                    <th>Time Left</th>
                                    <th>Reason</th>
                                </tr>
                                </tfoot>
                                <tbody style="font-size: small; cursor: pointer;">
                                <?php
                                    if (isset($_GET['filter'])) {
                                        $date = date('Y');
                                        print Loader::createTable($loader->getReceipts());
                                    } else
                                        print Loader::createTable($loader->getReceipts());
                                ?>
                                </tbody>
                            </table>
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
