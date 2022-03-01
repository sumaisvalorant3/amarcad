<?php
require_once(__DIR__ . "/../config.php");
?>
<div class="col-lg-6">
    <div class="card">
        <div class="card-body">
            <form action="actions/player-actions-handler.php" method="POST" onsubmit="this.broadcast_btn.hidden = true;">
                <div class="form-group">
                    <label for="broadcast">Broadcast Message</label>
                    <input required type="text" placeholder="Enter message..." id="broadcast" name="broadcast" class="form-control">
                </div>
                <div class="form-group">
                    <select class="form-control" id="servername" name="servername">
                        <?php
                        foreach ($SERVERS as $server)
                        {
                            $server_name = $server['server_name'];
                        ?>
                        <option value="<?php echo $server_name; ?>"><?php echo $server_name; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="broadcast_btn" class="btn btn-outline-info waves-effect waves-light">Send</button>
            </form>

        </div>
    </div>
</div>