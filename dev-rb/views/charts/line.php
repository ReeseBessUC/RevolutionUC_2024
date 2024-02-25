<?php
wp_enqueue_script('chartjs');
global $clsCompliance;
$arrAnswers = $clsCompliance->getAnswersOfCompany( @$_GET['cid'] );
?>
<canvas id="lineChart" height="300"></canvas>
<script>
  let answersArrayLine = [];
    <?php
    foreach ($arrAnswers as $intAnswer)
    {
        ?>
        answersArrayLine.push(<?php echo $intAnswer; ?>)
        <?php
    }
    ?>
var dataline = {
            labels: ['NOT RESPONDED', 'YES', 'NO', 'NOT SURE', 'IN PROGRESS'],
            datasets: [{
                label: 'Percentage',
                backgroundColor: 'rgba(154, 26, 9, 0.8)', // Background color for the bars
                borderColor: 'rgba(154, 26, 9, 0.8)', // Border color for the bars
                borderWidth: 1,
                data: answersArrayLine
            }]
        };

const configline = {
  type: 'line',
  data: dataline,
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
    const ctxline = document.getElementById('lineChart');
    new Chart(ctxline, configline);
});



</script>
