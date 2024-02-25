<?php
wp_enqueue_style('bootstrap');
wp_enqueue_script('bootstrap');

if (@$_GET['loginerror'] === "1")
{
    ?>
    <div class="alert alert-danger">
        <?php echo __("Invalid Login details.", "dev-i18n"); ?>
    </div>
    <?php
}
?>

<br>

<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <form action="" method="post">

            <div class="card">
                <div class="card-header">
                    Login
                </div>
                <div class="card-body">

                <label for="email">
                    <?php echo __("Username:", "dev-i18n"); ?> <br>
                    <input type="text" name="email" class="form-field w100" />
                </label>

                <br><br>

                <label for="password">
                    <?php echo __("Password:", "dev-i18n"); ?> <br>
                    <input type="password" name="password" class="form-field w100" />
                </label>

                <br><br>

                </div>
                <div class="card-footer">
                    <input type="submit" name="sbtUserLogin" value="<?php echo __("Login", "dev-i18n"); ?>" class="btn btn-primary" />
                </div>
            </div>

                

                

        </form>
    </div>
    <div class="col-md-4"></div>
</div>