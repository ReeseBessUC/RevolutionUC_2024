<?php
wp_enqueue_style('bootstrap');
wp_enqueue_script('bootstrap');
?>
<br>

<form action="" method="post">

<div class="card">
    <div class="card-header">
        Signup
    </div>
    <div class="card-body">
        <?php


        if (@$_GET['signup'] === "1")
        {
            ?>
            <div class="alert alert-success">
                <?php echo __("User Signup successful.", "dev-i18n"); ?>
            </div>
            <?php
        }

        ?>




        <label for="business">
            <?php echo __("Company:", "dev-i18n"); ?>
        </label>
        <input type="text" name="userData_Name_Business" class="form-control " />
        
        <br>

        <label for="fname">
            <?php echo __("First Name:", "dev-i18n"); ?> 
        </label>
        <input type="text" name="userData_Name_First" class="form-control " onblur="javascript: setPreferredName(this.value);" />
        
        <br>

        <label for="lname">
            <?php echo __("Last Name:", "dev-i18n"); ?>
        </label>
        <input type="text" name="userData_Name_Last" class="form-control " />
        
        <br>

        
        <label for="email">
            <?php echo __("Email Address:", "dev-i18n"); ?>
        </label>
        <input type="email" name="email" class="form-control " />

        <br>

        <label for="password">
            <?php echo __("Password:", "dev-i18n"); ?>
        </label>
        <input type="password" name="password" class="form-control " />
        

        <br>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="optTerms" id="flexCheckChecked" required >
            <label class="form-check-label" for="flexCheckChecked">
                Liability Disclaimer
            </label>
        </div>

    

    </div>
    <div class="card-footer">
        <input type="submit" name="sbtUserSignup" value="<?php echo __("Signup", "dev-i18n"); ?>" class="btn btn-primary" />
    </div>

</div>        

</form>

<br>

<script>
function setPreferredName(firstname) {
    jQuery('input[name=userData_Name_Preferred]').val( firstname );
}    
</script>