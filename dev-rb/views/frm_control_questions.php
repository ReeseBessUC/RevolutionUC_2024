<?php
wp_enqueue_style('bootstrap');
wp_enqueue_script('bootstrap');

global $clsCompliance;
$arrCompanies = $clsCompliance->getCompanies();
$arrControls = $clsCompliance->getControls();

$company_id = get_user_meta(get_current_user_id(), "company_id", true);

if (isset($_GET['success']))
{
    ?>
    <br>
    <div class="alert alert-success" role="alert">
        Answers Saved.
    </div>
    <?php
}

$arrAnswers = $clsCompliance->getAnswers();
$answers = get_field("answers", $arrAnswers[0]->ID);
$arrAnswers = explode("\n", $answers);
$arrUserAnswers = [];
foreach($arrAnswers as $ans)
{
    if (!empty($ans))
    {
        $tempArray = explode("=", $ans);
        $arrUserAnswers[$tempArray[1]] = $tempArray[0];
    }
    
}

?>
<form action="" method="post">

<div class="mb-3">
    <label for="exampleFormControlInput1" class="form-label">Select Company</label>
    <select class="form-select" aria-label="Default select example" name="company_id" 
    <?php
    if (!empty($company_id))
    {
        echo "disabled";
    }
    ?>
    >
        <option>Select Company</option>
        <?php foreach ($arrCompanies as $objCompany) { ?>
            <option 
                value="<?php echo $objCompany->ID;  ?>"
                <?php if ($objCompany->ID == trim($company_id)) { ?>selected<?php } ?>
                ><?php echo $objCompany->post_title; ?> </option>
        <?php } ?>
    </select>
</div>

<?php 
foreach ($arrControls as $objControl) 
{ 
    $control_text = get_field("control_text", $objControl->ID);

    ?>
<div class="mb-3">

    <div class="card">
        <div class="card-body">
            <label for="exampleFormControlInput1" class="form-label">
                <?php echo $control_text; ?>
            </label>
            <div class="form-check">
                <input 
                    <?php 
                        if (isset($arrUserAnswers[$objControl->post_title]) && $arrUserAnswers[$objControl->post_title] === "NOT RESPONDED") { echo "checked"; } 
                        if (!isset($arrUserAnswers[$objControl->post_title])) echo "checked";
                    ?>
                    class="form-check-input" type="radio" name="question[][<?php echo $objControl->ID; ?>]" value="NOT RESPONDED">
                <label class="form-check-label" for="flexCheckDefault">
                    NOT RESPONDED    
                </label>
            </div>

            <div class="form-check">
                <input 
                <?php if (isset($arrUserAnswers[$objControl->post_title]) && $arrUserAnswers[$objControl->post_title] === "YES") { echo "checked"; } ?>
                    class="form-check-input" type="radio" name="question[][<?php echo $objControl->ID; ?>]" value="YES">
                <label class="form-check-label" for="flexCheckDefault">
                    YES
                </label>
            </div>

            <div class="form-check">
                <input 
                <?php if (isset($arrUserAnswers[$objControl->post_title]) && $arrUserAnswers[$objControl->post_title] === "NO") { echo "checked"; } ?>
                    class="form-check-input" type="radio" name="question[][<?php echo $objControl->ID; ?>]" value="NO">
                <label class="form-check-label" for="flexCheckDefault">
                    NO
                </label>
            </div>

            <div class="form-check">
                <input 
                <?php if (isset($arrUserAnswers[$objControl->post_title]) && $arrUserAnswers[$objControl->post_title] === "NOT SURE") { echo "checked"; } ?>
                    class="form-check-input" type="radio" name="question[][<?php echo $objControl->ID; ?>]" value="NOT SURE">
                <label class="form-check-label" for="flexCheckDefault">
                    NOT SURE
                </label>
            </div>

            <div class="form-check">
                <input 
                <?php if (isset($arrUserAnswers[$objControl->post_title]) && $arrUserAnswers[$objControl->post_title] === "IN PROGRESS") { echo "checked"; } ?>
                    class="form-check-input" type="radio" name="question[][<?php echo $objControl->ID; ?>]" value="IN PROGRESS">
                <label class="form-check-label" for="flexCheckDefault">
                    IN PROGRESS
                </label>
            </div>
        </div>
    </div>

</div>
<?php } ?>

<input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>" />
<input type="submit" class="btn btn-primary" name="txtControlSubmit" value="Save" />


</form>