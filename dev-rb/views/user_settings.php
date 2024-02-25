<?php
wp_enqueue_style('bootstrap');
wp_enqueue_script('bootstrap');

global $clsCompliance;
$arrCompanies = $clsCompliance->getCompanies();


if (isset($_GET['success']))
{
    ?>
    <br>
    <div class="alert alert-success" role="alert">
        Settings Saved.
    </div>
    <?php
}

$company_id = get_user_meta(get_current_user_id(), "company_id", true);

?>
<form action="" method="post">

<br>

<div class="card">
    <div class="card-header">
        Company
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Select Company</label>
            <select class="form-select" aria-label="Default select example" name="company_id">
                <option>Select Company</option>
                <?php foreach ($arrCompanies as $objCompany) { ?>
                    <option 
                        value="<?php echo $objCompany->ID;  ?>"
                        <?php if ($objCompany->ID == trim($company_id)) { ?>selected<?php } ?>
                        ><?php echo $objCompany->post_title; ?> </option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="card-footer">
        <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>" />
        <button type="submit" class="btn btn-primary" name="company_submit">Submit</button>
    </div>
</div>





</form>