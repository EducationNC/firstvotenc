<?php

use Roots\Sage\Extras;
use Roots\Sage\Titles;

$type = $_GET['results'];

if (isset($_GET['contest'])) {
  get_template_part('templates/layouts/results', 'contest');
} elseif ($_GET['results'] == 'precincts') {
  get_template_part('templates/layouts/results', 'precincts');
} elseif ($type == 'participation') {
  get_template_part('templates/layouts/results', 'participation');
} else {
  ?>

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
  $uploads = network_site_url('wp-content/uploads');
  $results = json_decode(get_option('precinct_votes'), true);
  $contests = json_decode(get_option('precinct_contests'), true);
  $statewide = json_decode(file_get_contents($uploads . '/election_results.json'), true);

// echo '<pre>';
// print_r($contests);
// // print_r(array_keys($results[150]));
// echo '</pre>';

  $races = array_keys($results[0]);
  $races_statewide = array_keys($statewide[0]);

  foreach ($races as $race) {
    if (substr($race, 0, 11) == '_cmb_ballot') {

      $match = Extras\array_find_deep($contests, $race);

      // Only show type of results for the tab we're on
      if ($type == 'general') {
        if (!in_array($race, $races_statewide) || isset($contests[$match[0][0][0]][$race]['question'])) {
          continue;
        }
      } elseif ($type == 'local') {
        if (in_array($race, $races_statewide) || isset($contests[$match[0][0][0]][$race]['question'])) {
          continue;
        }
      } elseif ($type == 'issues') {
        if (!isset($contests[$match[0][0][0]][$race]['question'])) {
          continue;
        }
      }

      $data = array_column($results, $race);
      $data_state = array_column($statewide, $race);

      // If data is JSON string, unserialize it
      if (FALSE !== unserialize($data[0])) {
        $flat_data = array();
        foreach ($data as $multiple) {
          $encoded = unserialize($multiple);
          $array = unserialize(html_entity_decode($encoded));
          $flat_data = array_merge(array_values($flat_data), array_values($array));
        }
        $data = $flat_data;
      }

      // If data is JSON string, unserialize it
      if (FALSE !== unserialize($data_state[0])) {
        $flat_data = array();
        foreach ($data_state as $multiple) {
          $encoded = unserialize($multiple);
          $array = unserialize(html_entity_decode($encoded));
          $flat_data = array_merge(array_values($flat_data), array_values($array));
        }
        $data_state = $flat_data;
      }

      // Total number of ballots cast
      $total = count($data) - count(array_keys($data, NULL));
      $total_state = count($data_state) - count(array_keys($data_state, NULL));

      // Set up arrays
      $count = array();
      $count_state = array();

      // Count number of votes per contestant
      if (isset($contests[$match[0][0][0]][$race]['candidates'])) {
        foreach ($contests[$match[0][0][0]][$race]['candidates'] as $candidate) {
          $tally = count(array_keys($data, $candidate['name']));
          $tally_state = count(array_keys($data_state, $candidate['name']));

          // Precinct count
          $count[] = array(
            'name' => $candidate['name'],
            'party' => $candidate['party'],
            'count' => $tally,
            'percent' => round(($tally / $total) * 100, 2)
          );

          // Statewide count
          $count_state[] = array(
            'name' => $candidate['name'],
            'party' => $candidate['party'],
            'count' => $tally_state,
            'percent' => round(($tally_state / $total_state) * 100, 2)
          );
        }
      } else {
        foreach ($contests[$match[0][0][0]][$race]['options'] as $option) {
          $tally = count(array_keys($data, $option));
          $tally_state = count(array_keys($data_state, $option));

          // Precinct count
          $count[] = array(
            'name' => $option,
            'count' => $tally,
            'percent' => round(($tally / $total) * 100, 2)
          );

          // Statewide count
          $count_state[] = array(
            'name' => $option,
            'count' => $tally_state,
            'percent' => round(($tally_state / $total_state) * 100, 2)
          );
        }
      }
      ?>

      <div class="row">
        <div class="<?php if ($type == 'local' || !in_array($race, $races_statewide)) { echo 'col-sm-4'; } else { echo 'col-sm-12'; } ?>">
          <h2 class="h3">
            <?php echo $contests[$match[0][0][0]][$race]['title']; ?>
            <?php echo $contests[$match[0][0][0]][$race]['district']; ?>
            <?php if (isset($contests[$match[0][0][0]][$race]['question'])) { ?>
              <small><?php echo $contests[$match[0][0][0]][$race]['question']; ?></small>
            <?php } ?>
            <?php if ($contests[$match[0][0][0]][$race]['number'] > 1) { ?>
              <small><?php echo $contests[$match[0][0][0]][$race]['number']; ?> Winners</small>
            <?php } ?>
          </h2>
          <a class="btn btn-gray" href="<?php echo add_query_arg('contest', $race); ?>">Explore these results by exit poll</a>
        </div>

        <div class="<?php if ($type == 'local' || !in_array($race, $races_statewide)) { echo 'col-sm-8'; } else { echo 'col-sm-6 extra-bottom-margin'; } ?>">
          <div class="entry-content-asset">
            <div id="<?php echo $race; ?>" class="result-chart"></div>
          </div>
        </div>

        <?php if ($type !== 'local' && in_array($race, $races_statewide)) { ?>
          <div class="col-sm-6 extra-bottom-margin">
            <div class="entry-content-asset">
              <div id="state<?php echo $race; ?>" class="result-chart statewide"></div>
            </div>
          </div>
        <?php } ?>
      </div>

      <script type="text/javascript">
        new Highcharts.Chart({
          chart: { renderTo: '<?php echo $race; ?>', defaultSeriesType: 'bar' },
          credits: {enabled: false},
          title: { text: "<?php echo $contests[$match[0][0][0]][$race]['title'] . ' ' . $contests[$match[0][0][0]][$race]['district']; ?><br />(Precinct Results)", useHTML: true },
          <?php if ($contests[$match[0][0][0]][$race]['number'] > 1) { ?>
            subtitle: { text: "<?php echo $contests[$match[0][0][0]][$race]['number']; ?> Winners", useHTML: true },
          <?php } ?>
          <?php if (isset($contests[$match[0][0][0]][$race]['question'])) { ?>
            subtitle: { text: "<?php echo $contests[$match[0][0][0]][$race]['question']; ?>", useHTML: true },
          <?php } ?>
          xAxis: { type: 'category', tickWidth: 0, labels: { useHTML: true } },
          yAxis: { title: {enabled: false}, gridLineWidth: 0, labels: {enabled: false} },
          plotOptions: { bar: { dataLabels: { enabled: true, format: '{point.y:,.0f} votes ({point.percent:.2f}%)', inside: true, align: 'left', useHTML: true } } },
          legend: { enabled: false },
          tooltip: { enabled: false },
          series: [{ data: [<?php foreach ($count as $c) { ?>
              {
                name: '<?php echo str_replace(' & ', '<br />', $c['name']); ?><?php if (!empty($c['party'])) { echo '<br />(' . $c['party'] . ')'; } ?>',
                y: <?php echo $c['count']; ?>,
                className: '<?php if (isset($c['party'])) echo sanitize_title($c['party']); ?>',
                percent: <?php echo $c['percent']; ?>
                // animation: false
              },
            <?php } ?>]
          }]
        });

        <?php if ($type !== 'local' && in_array($race, $races_statewide)) { ?>
          new Highcharts.Chart({
            chart: { renderTo: 'state<?php echo $race; ?>', defaultSeriesType: 'bar' },
            credits: {enabled: false},
            title: { text: "<?php echo $contests[$match[0][0][0]][$race]['title'] . ' ' . $contests[$match[0][0][0]][$race]['district']; ?><br />(Statewide Results)", useHTML: true },
            <?php if ($contests[$match[0][0][0]][$race]['number'] > 1) { ?>
              subtitle: { text: "<?php echo $contests[$match[0][0][0]][$race]['number']; ?> Winners", useHTML: true },
            <?php } ?>
            <?php if (isset($contests[$match[0][0][0]][$race]['question'])) { ?>
              subtitle: { text: "<?php echo $contests[$match[0][0][0]][$race]['question']; ?>", useHTML: true },
            <?php } ?>
            xAxis: { type: 'category', tickWidth: 0, labels: { useHTML: true } },
            yAxis: { title: {enabled: false}, gridLineWidth: 0, labels: {enabled: false} },
            plotOptions: { bar: { dataLabels: { enabled: true, format: '{point.y:,.0f} votes ({point.percent:.2f}%)', inside: true, align: 'left', useHTML: true } } },
            legend: { enabled: false },
            tooltip: { enabled: false },
            series: [{ data: [<?php foreach ($count_state as $cs) { ?>
                {
                  name: '<?php echo str_replace(' & ', '<br />', $cs['name']); ?><?php if (!empty($cs['party'])) { echo '<br />(' . $cs['party'] . ')'; } ?>',
                  y: <?php echo $cs['count']; ?>,
                  className: '<?php if (isset($cs['party'])) echo sanitize_title($cs['party']); ?>',
                  percent: <?php echo $cs['percent']; ?>
                  // animation: false
                },
              <?php } ?>]
            }]
          });
        <?php } ?>
      </script>
      <?php
    }
  }
}
