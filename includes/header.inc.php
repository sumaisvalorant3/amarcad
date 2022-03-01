<?php
session_start();
if (empty($_SESSION['logged_in']))
{
    header('Location: login.php');
}

require_once __DIR__ . '/../config.php';
?>
<style>
    .navbar-custom {
        background-color: #38414a !important;
    }
</style>
            <div class="navbar-custom">
                <ul class="list-unstyled topnav-menu float-right mb-0">
                   <li>
                        <button class="button-menu-mobile waves-effect waves-light">
                            <i class="fe-menu"></i>
                        </button>
                    </li>
                    <li class="dropdown notification-list">
                        <a href="actions/logout.php" class="nav-link waves-effect waves-light">
                            <i class="fe-log-out noti-icon"></i>
                        </a>
                    </li>
                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="index.php" class="logo text-center">
                        <span class="logo-lg">
                             <span class="logo-lg-text-light"><?php echo SERVER_NAME;?></span>
                        </span>
                        <span class="logo-sm">
                            <img src="<?php echo LOGS_IMAGE; ?>" alt="" height="24">
                        </span>
                    </a>
                </div>
            </div>