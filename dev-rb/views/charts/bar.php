<?php
wp_enqueue_script('chartjs');

global $clsCompliance;
$arrAnswers = $clsCompliance->getAnswersOfCompany( @$_GET['cid'] );
?>
<canvas id="barChart" height="300"></canvas>
<script>
    let answersArray = [];
    <?php
    foreach ($arrAnswers as $intAnswer)
    {
        ?>
        answersArray.push(<?php echo $intAnswer; ?>)
        <?php
    }
    ?>
var data = {
            labels: ['NOT RESPONDED', 'YES', 'NO', 'NOT SURE', 'IN PROGRESS'],
            datasets: [{
                label: 'Percentage',
                backgroundColor: 'rgba(54, 162, 235, 0.8)', // Background color for the bars
                borderColor: 'rgba(54, 162, 235, 1)', // Border color for the bars
                borderWidth: 1,
                data: answersArray
            }]
        };

const config = {
  type: 'bar',
  data: data,
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
    const ctx = document.getElementById('barChart');
    new Chart(ctx, config);
});



</script>
