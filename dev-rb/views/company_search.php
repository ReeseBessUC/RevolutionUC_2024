<?php
wp_enqueue_style('bootstrap');
wp_enqueue_script('bootstrap');

?>

<br>
<style>
    table {
        border: 1px !important;
    }
</style>
<form action="" method="get" id="frmSearch">
<div class="card">
    <div class="card-header">
        Search Company
    </div>
    <div class="card-body">
        <div class="mb-3">
            <input type="text" class="form-control" id="txtSearch" placeholder="Search..." name="srch" value="<?php echo @$_GET['srch']; ?>" />
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</div>
</form>

<?php

if (isset($_GET['srch']))
{
    $search_terms = $_GET['srch'];

    global $clsCompliance;
    $arrCompanies = $clsCompliance->search_companies( $search_terms );

    if (count($arrCompanies))
    {
        ?>
        <br>
        <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="10%" >#</th>
                    <th width="30%" >Name</th>
                    <th width="30%" >% Compliant</th>
                    <th width="30%" >Diagnostics</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($arrCompanies as $intIndex => $objCompany)
            {
                $arrAnswers = $clsCompliance->getAnswersOfCompany( $objCompany->ID );

                $strLink = add_query_arg([
                    'cid' => $objCompany->ID
                ], get_permalink(88));
                ?>
                <tr>
                    <td><?php echo ($intIndex + 1); ?></td>
                    <td><?php echo $objCompany->post_title; ?></td>
                    <td><?php echo $arrAnswers['Yes']  ?>%</td>
                    <td>
                        <a href="<?php echo $strLink; ?>" class="btn btn-outline-success">Diagnostic</a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        </div>
        <?php
    }
    else
    {
        ?>
        <br><br>
        <div class="alert alert-danger" role="alert">
        No data found for the search term...
        </div>
        <?php
    }
}
?>