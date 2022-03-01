<?php
session_start();
if (empty($_SESSION['logged_in']))
{
  header('Location: login.php');
}

require_once __DIR__ . '/../actions/discord_functions.php';
require_once __DIR__ . '/../config.php';

$name = $_SESSION['staffname'];
$staffid = $_SESSION['staffid'];
$avatar = $_SESSION['staffavatar'];
$staffRank = $_SESSION['staffrank'];
?>
<style>
.left-side-menu-dark .navbar-custom {
    background: <?php echo ACCENT_COLOR; ?> !important;
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
}

.left-side-menu-dark .left-side-menu .nav-second-level li a, .left-side-menu-dark .left-side-menu .nav-third-level li a {
  color: white !important;
}

.left-side-menu-dark .left-side-menu {
  background: <?php echo ACCENT_COLOR; ?> !important;
  border-right: none !important;
  transition: none !important;
  padding-top: 0 !important;
}

.left-side-menu-dark .left-side-menu #sidebar-menu>ul>li>a.active {
  background: #1e272e !important;
}

.left-side-menu-dark .left-side-menu #sidebar-menu>ul>li>a.active span {
  color: white !important;
}

#sidebar-menu>ul>li>a>span {
    vertical-align: middle !important;
    color: black !important;
    font-weight: 400 !important; /* OPTIONAL BUT LOOKS BETTER WITH */
}

#sidebar-menu>ul>li>a i {
    vertical-align: middle !important;
    color: black !important;
    font-weight: 400 !important; /* OPTIONAL */
}

.left-side-menu-dark .left-side-menu #sidebar-menu .menu-title {
  color: white !important;
}

.text-muted {
  color: white !important;
}

.left-side-menu-dark .left-side-menu .nav-second-level li a, .left-side-menu-dark .left-side-menu .nav-thrid-level li:hover a {
  color: black !important; /* IDK, u can add a background on hover instead */
}

.left-side-menu-dark .left-side-menu #sidebar-menu>ul>li>a.active i {
  color: white !important; /* icon color on active */
}

.p-2 p {
   color: black !important;
}

.row .text-right p {
  color: black !important;
}

.pt-2 p {
  color: black !important;
}

.page-item.active .page-link {
      background: <?php echo ACCENT_COLOR; ?> !important;
      border-color: <?php echo ACCENT_COLOR; ?> !important;
}

.row p {
  color: black !important;
}

#accentcolor {
  color: <?php echo ACCENT_COLOR; ?> !important;
}

.bg-info {
  background-color: <?php echo ACCENT_COLOR; ?> !important;
}

.btn-outline-info {
  color: <?php echo ACCENT_COLOR; ?> !important;
  border-color: <?php echo ACCENT_COLOR; ?> !important;
}

.btn-outline-info:hover {
  background-color: <?php echo ACCENT_COLOR; ?> !important;
  color: white !important;
}

html {
  --scrollbarBG: <?php echo "transparent"; ?>;
  --thumbBG: <?php echo ACCENT_COLOR; ?>;
}

body::-webkit-scrollbar {
  width: 10px;
}
body {
  scrollbar-width: thin;
  scrollbar-color: var(--thumbBG) var(--scrollbarBG);
}
body::-webkit-scrollbar-track {
  background: var(--scrollbarBG);
}
body::-webkit-scrollbar-thumb {
  background-color: var(--thumbBG) ;
  border: 3px solid var(--scrollbarBG);
}

/* DARK MODE */
body.dark-mode {
      background-color: #333333 !important;
}

body.dark-mode.left-side-menu-dark .navbar-custom {
    background: #212121 !important;
}

body.dark-mode.left-side-menu-dark .left-side-menu .nav-second-level li a, .left-side-menu-dark .left-side-menu .nav-third-level li a {
  color: black !important;
}

body.dark-mode.left-side-menu-dark .left-side-menu {
  background: #212121 !important;
  padding-top: 0 !important;
}

body.dark-mode.left-side-menu-dark .left-side-menu #sidebar-menu>ul>li>a span {
    color: white !important;
}

body.dark-mode.left-side-menu-dark .left-side-menu #sidebar-menu>ul>li>a i {
    color: white !important;
}

body.dark-mode.left-side-menu-dark .left-side-menu #sidebar-menu>ul>li>a.active {
  background: #121212 !important;
}

body.dark-mode.left-side-menu-dark .left-side-menu #sidebar-menu>ul>li>a.active span {
  color: white !important;
}

body.dark-mode#sidebar-menu>ul>li>a>span {
    vertical-align: middle !important;
    color: white !important;
    font-weight: 400 !important; /* OPTIONAL BUT LOOKS BETTER WITH */
}

body.dark-mode#sidebar-menu>ul>li>a i {
    vertical-align: middle !important;
    color: white !important;
    font-weight: 400 !important; /* OPTIONAL */
}

body.dark-mode.left-side-menu-dark .left-side-menu #sidebar-menu .menu-title {
  color: white !important;
}

body.dark-mode.text-muted {
  color: white !important;
}

body.dark-mode.left-side-menu-dark .left-side-menu .nav-second-level li a, .left-side-menu-dark .left-side-menu .nav-thrid-level li:hover a {
  color: white !important; /* IDK, u can add a background on hover instead */
}

body.dark-mode.left-side-menu-dark .left-side-menu #sidebar-menu>ul>li>a.active i {
  color: white !important; /* icon color on active */
}

body.dark-mode.p-2 p {
   color: white !important;
}

body.dark-mode.row .text-right p {
  color: white !important;
}

body.dark-mode.pt-2 p {
  color: white !important;
}

body.dark-mode.page-item.active .page-link {
      background: #212121 !important;
      border-color: #212121 !important;
}

body.dark-mode .card-box {
      background: #474747 !important;
}

body.dark-mode .footer {
      background: #212121 !important;
      color: white !important;
}

body.dark-mode .footer a  {
      color: <?php echo ACCENT_COLOR; ?> !important;
}

body.dark-mode .page-title-box .page-title {
  color: white !important;
}

body.dark-mode .text-dark {
  color: white !important;
}

body.dark-mode .row .text-right p {
  color: white !important;
}

body.dark-mode .card-box h3 {
  color: white !important;
}

body.dark-mode .row p {
  color: white !important;
}

body.dark-mode .mt-2 {
  color: white !important;
}

body.dark-mode .row .card {
  background: #474747 !important;
}

body.dark-mode .table-striped tbody tr:nth-of-type(odd) {
  background: #212121 !important;
}

body.dark-mode .header-title {
  color: white !important;
}

body.dark-mode .mb-3 {
  color: white !important;
}

body.dark-mode .form-control {
  background: #212121 !important;
  border: none !important;
  color: white !important;
}

body.dark-mode .table {
  color: white !important;
}

body.dark-mode .mb-4 {
  color: white !important;
}

body.dark-mode .table a {
  color: <?php echo ACCENT_COLOR; ?> !important;
}

body.dark-mode .table input {
  color: <?php echo ACCENT_COLOR; ?> !important;
}

body.dark-mode .btn-outline-dark {
  color: white !important;
  border-color: white !important;
  background: transparent !important;
}

body.dark-mode .btn-outline-dark:hover {
  color: black !important;
  border-color: white !important;
  background: white !important;
}

body.dark-mode .page-item.active .page-link {
  background: #212121 !important;
  border-color: #212121 !important;
}

body.dark-mode .row label {
  color: white !important;
}

body.dark-mode .dataTables_info{
  color: white !important;
}

body.dark-mode .toast.show {
  border: none !important;
  background-color: transparent;
  -webkit-box-shadow: 10px 5px 0.5rem 0.9rem rgba(0,0,0,.1) !important;
  -moz-box-shadow: 10px 5px 0.5rem 0.9rem rgba(0,0,0,.1) !important;
  box-shadow: 10px 5px 0.5rem 0.9rem rgba(0,0,0,.1) !important;
}

body.dark-mode .toast.show .toast-header {
  background: #212121 !important;
  color: white !important;
  border-bottom: 2px solid white !important;
}
body.dark-mode .toast.show .toast-header .close {
  color: white;
}

body.dark-mode .toast.show .toast-body {
  background: #212121 !important;
  color: white !important;
}

body.dark-mode #sidebar-menu>ul>li>a:hover {
  background: #1b1b1b !important;
}

body.dark-mode #sidebar-menu>ul>li>ul>li>a:hover {
  background: #1b1b1b !important;
}

body.dark-mode .modal-content {
  background: #333333 !important;
  color: white !important;
}

body.dark-mode .modal-content .modal-header { 
  color: white !important;
}

body.dark-mode .mt-2 h6 {
  color: white !important;
}

body.dark-mode .card .card-body h4 {
  color: white !important;
}

body.dark-mode .modal-content .modal-header h4 {
  color: white !important;
}

body.dark-mode .modal-content .modal-body h6 {
  color: white !important;
}

body.dark-mode strong {
    color: white !important;
}

body.dark-mode .fas {
    color: white !important;
}

body.dark-mode {
  --scrollbarBG: <?php echo "transparent"; ?>;
  --thumbBG: #474747 !important;
}

</style>
<div class="left-side-menu">

                <div class="slimscroll-menu">

                    <!-- User box -->
                    <div class="user-box text-center">
                        <br>
                        <img src="<?php echo $avatar;?>" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
                        <div class="dropdown">
                            <h5 class="text-muted"><?php echo $name;?></h5>
                            <br>
                        </div>
                    </div>

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">

                        <ul class="metismenu" id="side-menu">

                            <li class="menu-title">Navigation</li>

                            <li>
                                <a href="index.php">
                                    <i class="fe-airplay"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript: void(0);">
                                    <i class="fe-server"></i>
                                    <span> Servers </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                  <?php
                                  foreach ($SERVERS as $server)
                                  {
                                  ?>
                                  <li>
                                      <a href="server.php?servername=<?php echo $server['server_name']; ?>"><?php echo $server['server_name']; ?></a>
                                  </li>
                                  <?php
                                  }
                                  ?>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);">
                                    <i class="fe-activity"></i>
                                    <span> Actions </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li>
                                        <a href="commends.php">Commends</a>
                                    </li>
                                    <li>
                                        <a href="warnings.php">Warnings</a>
                                    </li>
                                    <li>
                                        <a href="kicks.php">Kicks</a>
                                    </li>
                                    <li>
                                        <a href="bans.php">Bans</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="recentPlayers.php">
                                    <i class="fe-users"></i>
                                    <span> Recent Players </span>
                                </a>
                            </li>
                            <li>
                                <a href="allPlayers.php">
                                    <i class="fe-users"></i>
                                    <span> All Players </span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript: void(0);">
                                    <i class="fe-book"></i>
                                    <span> Documents </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <?php
                                  foreach ($DOCS as $name => $redirect) {
                                ?>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li>
                                        <a href="<?php echo $redirect; ?>" target="_blank"><?php echo $name; ?></a>
                                    </li>
                                </ul>
                                <?php
                                  }
                                ?>
                            </li>
<?php                            
if(in_array('ViewStaffSection', $_SESSION['permissionranks']))
{
?>
                            <li>
                                <a href="staffProfile.php">
                                    <i class="fe-user-check"></i>
                                    <span> Staff Profiles </span>
                                </a>
                            </li>                      

                            <li>
                                <a href="staffStats.php">
                                    <i class="fe-user-check"></i>
                                    <span> Staff Stats </span>
                                </a>
                            </li>

<?php
}
                        
if(in_array('ManageServerResources', $_SESSION['permissionranks']))
{

                            if (ENABLE_RESOURCES == true)
                            {
                            ?>
                            <li>
                                <a href="javascript: void(0);">
                                    <i class="fe-server"></i>
                                    <span> Server Resources </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                  <?php
                                  foreach ($SERVERS as $server)
                                  {
                                  ?>
                                  <li>
                                      <a href="resources.php?servername=<?php echo $server['server_name']; ?>"><?php echo $server['server_name']; ?></a>
                                  </li>
                                  <?php
                                  }
                                  ?>
                                </ul>
                            </li>

                            <?php
                            }
}
?>  
                        </ul>

                    </div>

                    <div class="clearfix"></div>
                    <div style="padding-top: 35px;" class="text-center">
                       <button onClick="setTheme()" type="button" class="btn btn-outline-dark btn-rounded waves-effect waves-light">Dark Mode</button>
                    </div>
                </div>

            </div>
            
<script>
  let element = document.body;
  let theme = localStorage.getItem("theme");

  if(theme === "dark") {
    element.classList.add("dark-mode");
  } else if(theme === "light") {
    element.classList.remove("dark-mode");
  }

  function setTheme() {
      let element = document.body;
      element.classList.toggle("dark-mode");

      if(localStorage.getItem("theme") === "light") {
        localStorage.setItem("theme", "dark");
      } else if(localStorage.getItem("theme") === "dark") {
        localStorage.setItem("theme", "light");
      } else {
        localStorage.setItem("theme", "dark");
      }
  }
</script>