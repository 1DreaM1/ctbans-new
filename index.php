<?php
require_once "includes/Loader.php";
require_once "includes/Server.php";
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
$server = new Server();
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

            <!-- Modal Ban Info -->
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

            <!-- Modal Server Info -->
            <div class="modal fade bd-example-modal-lg" id="serverModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Server Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body table-responsive">
                            <table class="table table-sm table-hover table-borderless">
                                <thead class="table-light">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Server</th>
                                    <th>Players</th>
                                    <th>Map</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="display:none;" id="serverID"></td>
                                        <td id="serverName"></td>
                                        <td id="serverPlayers"></td>
                                        <td id="serverMap"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                            <table class="table table-sm table-hover table-borderless">
                                <thead class="table-dark">
                                <tr>
                                    <th>Player <span class="badge badge-info">6</span></th>
                                    <th class="frags-column">Frags</th>
                                    <th class="frags-column">Time</th>
                                    <?php if($auth->IsUserLoggedIn()): ?><th class="text-center">Actions</th><?php endif; ?>
                                </tr>
                                </thead>
                                <tbody id="appendServerPlayersInfo"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Ban Info -->
            <div class="modal fade" id="addBanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">CTBan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text pr-4">Name</div>
                                </div>
                                <input type="text" class="form-control bg-white text-danger font-weight-bold" id="staticName" readonly>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text" style="padding-right:1.8rem">Time</div>
                                </div>
                                <input type="number" min="0" class="form-control" id="inputTime" value="30" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Reason</div>
                                </div>
                                <input type="text" class="form-control text-primary" id="inputReason" value="Breaking Rules" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="confirmBan">Ban</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Add Server -->
            <div class="modal fade" id="addServerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Server</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">IP</div>
                                </div>
                                <input type="text" class="form-control" id="inputIp" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text pr-3">Port</div>
                                </div>
                                <input type="number" min="0" class="form-control" id="inputPort" value="27030" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Rcon</div>
                                </div>
                                <input type="text" class="form-control" id="inputRcon">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="confirmServer">Add Server</button>
                        </div>
                    </div>
                </div>
            </div>

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <?php if(!$auth->IsUserLoggedIn()): ?><a href="login" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-sign-in-alt fa-sm text-white-50"></i> Sign In</a><?php endif; ?>
          </div>

          <!-- Content Row -->
          <div class="row">

            <!-- Obrat (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4 onload-animate-zoom">
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
              <div class="col-xl-3 col-md-6 mb-4 onload-animate-zoom">
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
            <div class="col-xl-3 col-md-6 mb-4 onload-animate-zoom">
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
            <div class="col-xl-3 col-md-6 mb-4 onload-animate-zoom">
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

            <div class="card shadow mb-4 onload-animate-left">
                <div class="card-header py-2 justify-content-between">
                    <h6 class="font-weight-bold pt-1 text-primary mb-0 float-left">Servers</h6>
                    <!--<a href="#" class="btn btn-primary btn-circle btn-sm float-right addServer"><i class="fas fa-plus"></i></a>-->
                    <?php if($auth->IsUserLoggedIn()): ?><button type="button" class="btn btn-sm btn-outline-success float-right addServer">Add Server</button><?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" width="100%" cellspacing="0">
                            <thead class="thead-light small">
                            <tr>
                                <th style="display: none;">ID</th>
                                <th>MOD</th>
                                <th>OS</th>
                                <th>VAC</th>
                                <th>Hostname</th>
                                <th>Players</th>
                                <th>Map</th>
                                <?php if($auth->IsUserLoggedIn()): ?><th class="text-center">Actions</th><?php endif; ?>
                            </tr>
                            </thead>
                            <tbody style="font-size:small;cursor:pointer;">
                            <?php
                                print $server->createServerTable($server->getServers(), $auth->IsUserLoggedIn());
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4 onload-animate-bottom">
                <div class="card-header py-3">
                    <h6 class="font-weight-bold text-primary mb-0">Latest Bans</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th style="display: none;">ID</th>
                                <th>Name</th>
                                <th>Created</th>
                                <th>Length</th>
                                <th>Reason</th>
                                <th>Time Left</th>
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
                            </tr>
                            </tfoot>
                            <tbody style="font-size: small; cursor: pointer;">
                            <?php
                                print Loader::createTable($loader->getBans(null, "LIMIT 10"));
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
