<?php
session_start();
if (empty($_SESSION['logged_in']))
{
	header('Location: login.php');
}
?>
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    Made with ❤ by <a id="accentcolor" href="https://discord.gg/3DDWp6w">Hamz#0001</a> 
                </div>
            </div>
        </div>
    </footer>