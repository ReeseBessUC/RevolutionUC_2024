<?php
wp_enqueue_script('chartjs');
global $clsCompliance;
$arrAnswers = $clsCompliance->getAnswersOfCompany( @$_GET['cid'] );
?>
<canvas id="donutChart"></canvas>
<script>
  let answersArrayDonut = [];
    <?php
    foreach ($arrAnswers as $intAnswer)
    {
        ?>
        answersArrayDonut.push(<?php echo $intAnswer; ?>)
        <?php
    }
    ?>
var datadonut = {
            labels: ['NOT RESPONDED', 'YES', 'NO', 'NOT SURE', 'IN PROGRESS'],
            datasets: [{
                label: 'Percentage',
                //backgroundColor: 'rgba(154, 26, 9, 0.8)', // Background color for the bars
                //borderColor: 'rgba(154, 26, 9, 0.8)', // Border color for the bars
                //borderWidth: 1,
                data: answersArrayDonut
            }]
        };

const configdonut = {
  type: 'doughnut',
  data: datadonut,
  options: {
    plugins: {
      title: {
        display: true,
        text: 'Ratio of all options'
      },
    },
    responsive: true,
    scales: {
      x: {
        stacked: true,
      },
      y: {
        stacked: true,
        min: 0,
        max: 100
      }
    }
  }
};    

jQuery(document).ready(function(){
    const ctxdonut = document.getElementById('donutChart');
    new Chart(ctxdonut, configdonut);
});



</script>
