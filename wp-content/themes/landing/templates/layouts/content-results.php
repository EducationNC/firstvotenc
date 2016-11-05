<script src="http://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/modules/data.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script src="http://code.highcharts.com/modules/offline-exporting.js"></script>


<script type="text/javascript">
  Highcharts.setOptions({
    lang: {
      thousandsSep: ","
    }
  });
</script>

<?php
$results = json_decode(get_option('election_results'), true);
$contests = json_decode(get_option('election_contests'), true);

// echo '<pre>';
// print_r($contests);
// echo '</pre>';

$table = $results;
array_shift($table);

foreach ($results[0] as $race) {
  if (substr($race, 0, 11) == '_cmb_ballot') {
    // echo '<h2>' . $contests[$race]['title'] . '</h2>';
    $data = array_column($table, $race);
    // print_r($race);
    $all = count($data);
    $none = count(array_keys($data, 'none'));
    $null = count(array_keys($data, NULL));
    $total = $all-$none-$null;

    $counts = array();

    // Count number of votes per contestant
    if (isset($contests[$race]['candidates'])) {
      foreach ($contests[$race]['candidates'] as $candidate) {
        $tally = count(array_keys($data, $candidate['name']));
        $counts[] = array(
          'name' => $candidate['name'],
          'party' => $candidate['party'],
          'count' => $tally,
          'percent' => round(($tally / $total) * 100, 1)
        );
      }
    } else {
      foreach ($contests[$race]['options'] as $option) {
        $tally = count(array_keys($data, $option));
        $counts[] = array(
          'name' => $option,
          'count' => $tally,
          'percent' => round(($tally / $total) * 100, 1)
        );
      }
    }
    ?>

    <div class="entry-content-asset">
      <div id="<?php echo $race; ?>" class="result-chart"></div>
    </div>
    <script type="text/javascript">
      var options = {
        chart: {
          renderTo: '<?php echo $race; ?>',
          defaultSeriesType: 'bar'
        },
        credits: {enabled: false},
        title: {
          text: "<?php echo $contests[$race]['title']; ?>",
          useHTML: true
        },
        <?php if (isset($contests[$race]['question'])) { ?>
          subtitle: {
            text: "<?php echo $contests[$race]['question']; ?>",
            useHTML: true
          },
        <?php } ?>
        xAxis: {
          type: 'category',
          tickWidth: 0,
          maxPadding: 0,
          minPadding: 0,
          labels: {
            useHTML: true
          }
        },
        yAxis: {
          title: {enabled: false},
          gridLineWidth: 0,
          labels: {enabled: false}
        },
        plotOptions: {
          bar: {
            dataLabels: {
              enabled: true,
              format: '{point.y:,.0f} votes',
              inside: true,
              align: 'right'
            }
          }
        },
        legend: {
            enabled: false
        },
        tooltip: {
          pointFormat: '{point.percent}% of votes',
          useHTML: true,
          followPointer: true,
          split: true
        },
        series: [{
          data: [<?php foreach ($counts as $count) { ?>
            {
              name: '<?php echo str_replace(' & ', '<br />', $count['name']); ?><?php if (!empty($count['party'])) { echo '<br />(' . $count['party'] . ')'; } ?>',
              y: <?php echo $count['count']; ?>,
              className: '<?php echo sanitize_title($count['party']); ?>',
              percent: <?php echo $count['percent']; ?>
              // animation: false
            },
          <?php } ?>]
        }]
      };

      var chart = new Highcharts.Chart(options);
    </script>
    <?php
  }
}
?>

<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th>Count</th>
        <?php /*foreach ($results[0] as $header) { ?>
          <th><?php echo $header; ?></th>
        <?php }*/ ?>
      </tr>
    </thead>

    <tbody>
      <?php /*for ($i = 1, $size = count($results); $i < $size; ++$i) { ?>
        <tr>
          <td><?php echo $i; ?></td>
          <?php foreach ($results[$i] as $vote) { ?>
            <td>
              <?php
              if (is_array($vote)) {
                echo str_replace(['&lt;', '&gt;'], ['<', '>'], implode(', ', $vote));
              } else {
                echo $vote;
              }
              ?>
            </td>
          <?php } ?>
        </tr>
      <?php }*/ ?>
    </tbody>
  </table>
</div>
