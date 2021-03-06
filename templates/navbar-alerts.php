<?php
require_once "includes/Loader.php";

$loader = new Loader();
$todayStartTimestamp = strtotime(date("d.m.Y 1:0:0"));
$todayEndTimestamp = strtotime(date("d.m.Y 23:59:59"));
?>
<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw text-white"></i>
        <!-- Counter - Alerts -->
        <span class="badge badge-danger badge-counter" id="alertsCount">1</span>
    </a>
    <!-- Dropdown - Alerts -->
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">
            Alerts
        </h6>
        <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="mr-3">
                <div class="icon-circle bg-danger">
                    <i class="fas fa-user-lock text-white"></i>
                </div>
            </div>
            <div>
                <div class="small text-gray-500"><?php echo date("M d Y "); ?></div>
                <span>Today <span class="text-primary"><?php echo $loader->getBansCount("WHERE `created` > {$todayStartTimestamp} AND `created` < {$todayEndTimestamp}")?></span> new bans !</span>
            </div>
        </a>
        <a class="dropdown-item text-center small text-gray-500" href="#">Show all alerts</a>
    </div>
</li>

<!-- Nav Item - Messages -->
<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-envelope fa-fw text-white"></i>
        <!-- Counter - Messages -->
        <!--<span class="badge badge-danger badge-counter">0</span>-->
    </a>
    <!-- Dropdown - Messages -->
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
        <h6 class="dropdown-header">
            Messages
        </h6>
        <a class="dropdown-item d-flex align-items-center" href="#">
            <!--<div class="dropdown-list-image mr-3">
                <img class="rounded-circle" src="https://source.unsplash.com/fn_BT9fwg_E/60x60" alt="">
                <div class="status-indicator bg-success"></div>
            </div>
            <div class="font-weight-bold">
                <div class="text-truncate">Text text text</div>
                <div class="small text-gray-500">Test · 58m</div>
            </div>-->
        </a>
        <a class="dropdown-item text-center small text-gray-500" href="#">Show all Messages</a>
    </div>
</li>