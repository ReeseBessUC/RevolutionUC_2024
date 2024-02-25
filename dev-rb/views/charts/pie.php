<?php
wp_enqueue_script('chartjs');
global $clsCompliance;
$arrAnswers = $clsCompliance->getAnswersOfCompany( @$_GET['cid'] );
?>
<canvas id="pieChart"></canvas>
<script>
  let answersArrayPie = [];
    <?php
    foreach ($arrAnswers as $intAnswer)
    {
        ?>
        answersArrayPie.push(<?php echo $intAnswer; ?>)
        <?php
    }
    ?>
var datapie = {
            labels: ['NOT RESPONDED', 'YES', 'NO', 'NOT SURE', 'IN PROGRESS'],
            datasets: [{
                label: 'Percentage',
                //backgroundColor: 'rgba(154, 26, 9, 0.8)', // Background color for the bars
                //borderColor: 'rgba(154, 26, 9, 0.8)', // Border color for the bars
                //borderWidth: 1,
                data: answersArrayPie
            }]
        };

const configpie = {
  type: 'pie',
  data: datapie,
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
    const ctxpie = document.getElementById('pieChart');
    new Chart(ctxpie, configpie);
});



</script>
