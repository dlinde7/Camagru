<?php include "sever.php" ?>
<?php if (!isset($_SESSION['username'])) {?> <!--To check if there is ?user=""&psw=""-->
    <form id="login" class="modal-window" method="post" action="">
            <?php include('errors.php'); ?>
            <div>
                <a href="#" class="close">&times;</a>
                <label for="user"><b>Username</b></label><br>
                <input type="text" name="user" placeholder="Enter Username" required>
                <br><br>
                <label for="pws"><b>Password</b></label><br>
                <input type="password" name="pws" placeholder="Enter Password" required>
                <br><br>
                <button type="submit" name="login_user">Login</button>
                <button type="button" class="c" onclick="window.location.href = '#';">Cancel</button>
                <span class="psw">Forgot <a href="#">password?</a></span>
            </div>
    </form>
<?php }?>