<?php
require_once(__DIR__ . "/../config.php");
session_start();
$user_id = $_SESSION['id'];

try{
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
    echo "Could not connect -> ".$ex->getMessage();
    die();
}

$warns = $pdo->query("SELECT * FROM warnings ORDER BY id DESC");
$kicks = $pdo->query("SELECT * FROM kicks ORDER BY id DESC");
$bans = $pdo->query("SELECT * FROM bans ORDER BY id DESC");
$commends = $pdo->query("SELECT * FROM commend ORDER BY id DESC");

$dataBans = bansData();
$dataKicks = kicksData();
$dataWarnings = warningsData();
$dataCommends = commendsData();
$labels = array(
    0 => date("Y-m-d", strtotime("-6 day")), 
    1 => date("Y-m-d", strtotime("-5 day")), 
    2 => date("Y-m-d", strtotime("-4 day")), 
    3 => date("Y-m-d", strtotime("-3 day")),
    4 => date("Y-m-d", strtotime("-2 day")),
    5 => date("Y-m-d", strtotime("-1 day")),
    6 => date("Y-m-d")
    );
  


function bansData(){
  global $pdo;
  
  $day1data = 0;
  $day2data = 0;
  $day3data = 0;
  $day4data = 0;
  $day5data = 0;
  $day6data = 0;
  $todayData = 0;

  $today = date("Y-m-d");
  $startDate = date("Y-m-d", strtotime("-6 day"));
  $result = $pdo->query("SELECT * FROM bans WHERE stamp BETWEEN '$startDate 00:00:00' AND '$today 23:59:59'");

  foreach($result as $row)
  {
    $split = explode(" ", $row['stamp']);
    if($split[0] == date("Y-m-d", strtotime("-6 day"))){
      $day1data++;
    }else if($split[0] == date("Y-m-d", strtotime("-5 day"))){
      $day2data++;
    }else if($split[0] == date("Y-m-d", strtotime("-4 day"))){
      $day3data++;
    }else if($split[0] == date("Y-m-d", strtotime("-3 day"))){
      $day4data++;
    }else if($split[0] == date("Y-m-d", strtotime("-2 day"))){
      $day5data++;
    }else if($split[0] == date("Y-m-d", strtotime("-1 day"))){
      $day6data++;
    }else if($split[0] == date("Y-m-d")){
      $todayData++;
    }
  }

  $data = array(
      0 => $day1data, 
      1 => $day2data, 
      2 => $day3data, 
      3 => $day4data,
      4 => $day5data,
      5 => $day6data,
      6 => $todayData
    );
  
  return $data;
}

function kicksData(){
  global $pdo;
  
  $day1data = 0;
  $day2data = 0;
  $day3data = 0;
  $day4data = 0;
  $day5data = 0;
  $day6data = 0;
  $todayData = 0;

  $today = date("Y-m-d");
  $startDate = date("Y-m-d", strtotime("-6 day"));
  $result = $pdo->query("SELECT * FROM kicks WHERE stamp BETWEEN '$startDate 00:00:00' AND '$today 23:59:59'");

  foreach($result as $row)
  {
    $split = explode(" ", $row['stamp']);
    if($split[0] == date("Y-m-d", strtotime("-6 day"))){
      $day1data++;
    }else if($split[0] == date("Y-m-d", strtotime("-5 day"))){
      $day2data++;
    }else if($split[0] == date("Y-m-d", strtotime("-4 day"))){
      $day3data++;
    }else if($split[0] == date("Y-m-d", strtotime("-3 day"))){
      $day4data++;
    }else if($split[0] == date("Y-m-d", strtotime("-2 day"))){
      $day5data++;
    }else if($split[0] == date("Y-m-d", strtotime("-1 day"))){
      $day6data++;
    }else if($split[0] == date("Y-m-d")){
      $todayData++;
    }
  }

  $data = array(
      0 => $day1data, 
      1 => $day2data, 
      2 => $day3data, 
      3 => $day4data,
      4 => $day5data,
      5 => $day6data,
      6 => $todayData
    );
  
  return $data;
} 

function warningsData(){
  global $pdo;
  
  $day1data = 0;
  $day2data = 0;
  $day3data = 0;
  $day4data = 0;
  $day5data = 0;
  $day6data = 0;
  $todayData = 0;

  $today = date("Y-m-d");
  $startDate = date("Y-m-d", strtotime("-6 day"));
  $result = $pdo->query("SELECT * FROM warnings WHERE stamp BETWEEN '$startDate 00:00:00' AND '$today 23:59:59'");

  foreach($result as $row)
  {
    $split = explode(" ", $row['stamp']);
    if($split[0] == date("Y-m-d", strtotime("-6 day"))){
      $day1data++;
    }else if($split[0] == date("Y-m-d", strtotime("-5 day"))){
      $day2data++;
    }else if($split[0] == date("Y-m-d", strtotime("-4 day"))){
      $day3data++;
    }else if($split[0] == date("Y-m-d", strtotime("-3 day"))){
      $day4data++;
    }else if($split[0] == date("Y-m-d", strtotime("-2 day"))){
      $day5data++;
    }else if($split[0] == date("Y-m-d", strtotime("-1 day"))){
      $day6data++;
    }else if($split[0] == date("Y-m-d")){
      $todayData++;
    }
  }

  $data = array(
      0 => $day1data, 
      1 => $day2data, 
      2 => $day3data, 
      3 => $day4data,
      4 => $day5data,
      5 => $day6data,
      6 => $todayData
    );
  
  return $data;
}

function commendsData(){
  global $pdo;
  
  $day1data = 0;
  $day2data = 0;
  $day3data = 0;
  $day4data = 0;
  $day5data = 0;
  $day6data = 0;
  $todayData = 0;

  $today = date("Y-m-d");
  $startDate = date("Y-m-d", strtotime("-6 day"));
  $result = $pdo->query("SELECT * FROM commend WHERE stamp BETWEEN '$startDate 00:00:00' AND '$today 23:59:59'");

  foreach($result as $row)
  {
    $split = explode(" ", $row['stamp']);
    if($split[0] == date("Y-m-d", strtotime("-6 day"))){
      $day1data++;
    }else if($split[0] == date("Y-m-d", strtotime("-5 day"))){
      $day2data++;
    }else if($split[0] == date("Y-m-d", strtotime("-4 day"))){
      $day3data++;
    }else if($split[0] == date("Y-m-d", strtotime("-3 day"))){
      $day4data++;
    }else if($split[0] == date("Y-m-d", strtotime("-2 day"))){
      $day5data++;
    }else if($split[0] == date("Y-m-d", strtotime("-1 day"))){
      $day6data++;
    }else if($split[0] == date("Y-m-d")){
      $todayData++;
    }
  }

  $data = array(
      0 => $day1data, 
      1 => $day2data, 
      2 => $day3data, 
      3 => $day4data,
      4 => $day5data,
      5 => $day6data,
      6 => $todayData
    );
  
  return $data;
}

?>
<style>
.card-box {
  border-radius: 0px !important;
}
</style>
              <div class="row">
                <div class="card-box col-md-3 col-sm-6">
                  <i class="fas fa-exclamation-triangle"></i>
                      <div class="mt-2">
                          <h6 class="text-uppercase">Warnings <span class="float-right"><?php echo sizeof($warns->fetchAll()) ?></span></h6>
                          <div class="progress progress-sm m-0">
                              <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="card-box col-md-3 col-sm-6">
                    <i class="fas fa-ban"></i>
                      <div class="mt-2">
                          <h6 class="text-uppercase">Kicks <span class="float-right"><?php echo sizeof($kicks->fetchAll()) ?></span></h6>
                          <div class="progress progress-sm m-0">
                              <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="card-box col-md-3 col-sm-6">
                    <i class="fas fa-bomb"></i>
                      <div class="mt-2 ">
                          <h6 class="text-uppercase">Bans <span class="float-right"><?php echo sizeof($bans->fetchAll()) ?></span></h6>
                          <div class="progress progress-sm m-0">
                              <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="card-box col-md-3 col-sm-6">
                    <i class="fas fa-heart"></i>
                      <div class="mt-2">
                          <h6 class="text-uppercase">Commends <span class="float-right"><?php echo sizeof($commends->fetchAll()) ?></span></h6>
                          <div class="progress progress-sm m-0">
                              <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="row">
                <div class="card-box col-sm-12 col-md-6">
                  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
                      <div class="line-chart block chart">
                          <label>24HR Statistics</label>
                          <div class="mt-2"></div>
                          <canvas id="sanctionChart" width="100%"></canvas>
                      </div>
                      <script>
                          let data;
                          <?php
                          $now = time();
                          $lowerBound = $now - 86400;
                          $warns = $pdo->query("SELECT * FROM warnings WHERE time > {$lowerBound}");
                          $warns = $warns->rowCount();
                          $kicks = $pdo->query("SELECT * FROM kicks WHERE time > {$lowerBound}");
                          $kicks = $kicks->rowCount();
                          $bans = $pdo->query("SELECT * FROM bans WHERE ban_issued > {$lowerBound}");
                          $bans = $bans->rowCount();
                          $commends = $pdo->query("SELECT * FROM commend WHERE time > {$lowerBound}");
                          $commends = $commends->rowCount();

                          echo "data = [{$warns}, {$kicks}, {$bans}, {$commends}];";
                          ?>
                          new Chart(document.getElementById('sanctionChart'), {
                              type: "bar",
                              data: {
                                  labels: ["Warns", "Kicks", "Bans", "Commends"],
                                  datasets: [
                                      {
                                          label: "Count",
                                          data: data,
                                          fill: false,
                                          color: "#ff0000",
                                          backgroundColor: ["#f39c12", "#9b59b6", "#e95f71", "#2ecc71"]
                                      }
                                  ]
                              },
                              options: {
                                  legend: false,
                                  hover: {mode: false},
                                  scales: {
                                      yAxes: [
                                          {
                                              ticks: {
                                                  beginAtZero: true
                                              }
                                          }
                                      ]
                                  }
                              }
                          })
                      </script>
                </div>
                <div class="card-box col-sm-12 col-md-6">
                <div class="line-chart block chart">
                  <label>Daily Historic</label>
                  <div class="mt-2"></div>
                  <canvas id="canvas" width="100%"></canvas>
                </div>
                </div>
                <script>
                var config = {
                  type: 'line',
                  data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [
                      {
                        label: "Bans",
                        fill: false,
                        lineTension: 0.5,
                        backgroundColor: "#e95f71",
                        borderColor: "#e95f71",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        borderWidth: 2,
                        pointBorderColor: "#e95f71",
                        pointBackgroundColor: "#e95f71",
                        pointBorderWidth: 5,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "#e95f71",
                        pointHoverBorderColor: "#e95f71",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: <?php echo json_encode($dataBans); ?>,
                        spanGaps: false
                      },
                      {
                        label: "Kicks",
                        fill: false,
                                    lineTension: 0.5,
                                    backgroundColor: "#9b59b6",
                                    borderColor: "#9b59b6",
                                    borderCapStyle: 'butt',
                                    borderDash: [],
                                    borderDashOffset: 0.0,
                                    borderJoinStyle: 'miter',
                                    borderWidth: 2,
                                    pointBorderColor: "#9b59b6",
                                    pointBackgroundColor: "#9b59b6",
                                    pointBorderWidth: 5,
                                    pointHoverRadius: 5,
                                    pointHoverBackgroundColor: "#9b59b6",
                                    pointHoverBorderColor: "#9b59b6",
                                    pointHoverBorderWidth: 2,
                                    pointRadius: 1,
                                    pointHitRadius: 10,
                        data: <?php echo json_encode($dataKicks); ?>,
                        spanGaps: false
                      },
                      {
                        label: "Warnings",
                        fill: false,
                                    lineTension: 0.5,
                                    backgroundColor: "#f39c12",
                                    borderColor: "#f39c12",
                                    borderCapStyle: 'butt',
                                    borderDash: [],
                                    borderDashOffset: 0.0,
                                    borderJoinStyle: 'miter',
                                    borderWidth: 2,
                                    pointBorderColor: "#f39c12",
                                    pointBackgroundColor: "#f39c12",
                                    pointBorderWidth: 5,
                                    pointHoverRadius: 5,
                                    pointHoverBackgroundColor: "#f39c12",
                                    pointHoverBorderColor: "#f39c12",
                                    pointHoverBorderWidth: 2,
                                    pointRadius: 1,
                                    pointHitRadius: 10,
                        data: <?php echo json_encode($dataWarnings); ?>,
                        spanGaps: false
                      },
                      {
                        label: "Commends",
                        fill: false,
                                    lineTension: 0.5,
                                    backgroundColor: "#2ecc71",
                                    borderColor: "#2ecc71",
                                    borderCapStyle: 'butt',
                                    borderDash: [],
                                    borderDashOffset: 0.0,
                                    borderJoinStyle: 'miter',
                                    borderWidth: 2,
                                    pointBorderColor: "#2ecc71",
                                    pointBackgroundColor: "#2ecc71",
                                    pointBorderWidth: 5,
                                    pointHoverRadius: 5,
                                    pointHoverBackgroundColor: "#2ecc71",
                                    pointHoverBorderColor: "#2ecc71",
                                    pointHoverBorderWidth: 2,
                                    pointRadius: 1,
                                    pointHitRadius: 10,
                        data: <?php echo json_encode($dataCommends); ?>,
                        spanGaps: false
                      }
                    ]
                  },
                  options: {
                    legend: {labels:{fontColor:"#777", fontSize: 12}},
                    scales: {
                      xAxes: [{
                        display: true,
                        scaleLabel: {
                          display: true,
                          labelString: 'Dates'
                        }
                      }],
                      yAxes: [{
                        display: true,
                        scaleLabel: {
                          display: true,
                          labelString: 'Total'
                        }
                      }]
                    },
                  }
                };

                window.onload = function() {
                  var ctx = document.getElementById('canvas').getContext('2d');
                  window.myLine = new Chart(ctx, config);
                };

                document.getElementById('randomizeData').addEventListener('click', function() {
                  config.data.datasets.forEach(function(dataset) {
                    dataset.data = dataset.data.map(function() {
                      return randomScalingFactor();
                    });

                  });

                  window.myLine.update();
                });

                var colorNames = Object.keys(window.chartColors);
                document.getElementById('addDataset').addEventListener('click', function() {
                  var colorName = colorNames[config.data.datasets.length % colorNames.length];
                  var newColor = window.chartColors[colorName];
                  var newDataset = {
                    label: 'Dataset ' + config.data.datasets.length,
                    backgroundColor: newColor,
                    borderColor: newColor,
                    data: [],
                    fill: false
                  };

                  for (var index = 0; index < config.data.labels.length; ++index) {
                    newDataset.data.push(randomScalingFactor());
                  }

                  config.data.datasets.push(newDataset);
                  window.myLine.update();
                });

                document.getElementById('addData').addEventListener('click', function() {
                  if (config.data.datasets.length > 0) {
                    var month = MONTHS[config.data.labels.length % MONTHS.length];
                    config.data.labels.push(month);

                    config.data.datasets.forEach(function(dataset) {
                      dataset.data.push(randomScalingFactor());
                    });

                    window.myLine.update();
                  }
                });

                document.getElementById('removeDataset').addEventListener('click', function() {
                  config.data.datasets.splice(0, 1);
                  window.myLine.update();
                });

                document.getElementById('removeData').addEventListener('click', function() {
                  config.data.labels.splice(-1, 1); // remove the label first

                  config.data.datasets.forEach(function(dataset) {
                    dataset.data.pop();
                  });

                  window.myLine.update();
                });
                </script>
                </div>
              </div>

              <div class="row">
                <?php
                if (in_array('Broadcast', $_SESSION['permissionranks']) && ENABLE_BROADCAST == true) {
                ?>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="actions/player-actions-handler.php" method="POST" onsubmit="this.broadcast_btn.hidden = true;">
                                <div class="form-group">
                                    <label for="broadcast">Broadcast Message</label>
                                    <input required type="text" placeholder="Enter message..." id="broadcast" name="broadcast" class="form-control">
                                </div>
                                <div class="form-group">
                                    <select class="form-control" id="servername" name="servername">
                                        <?php
                                        foreach ($SERVERS as $server)
                                        {
                                            $server_name = $server['server_name'];
                                        ?>
                                        <option value="<?php echo $server_name; ?>"><?php echo $server_name; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" name="broadcast_btn" class="btn btn-outline-info waves-effect waves-light">Send</button>
                            </form>

                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
              </div>