<?php

// class MyApiTest extends PHPUnit_Framework_TestCase {
// use PHPUnit\Framework\TestCase;
//
// class MyApiTest extends TestCase {

class MySeleniumSuite extends PHPUnit_Extensions_Selenium2TestCase
{

    public function setUp() {

        // $this->setHost("10.10.75.216");
        // $this->setBrowser("chrome");
        // $this->setPort(4445);
        // $this->setBrowserUrl("https://www.autopartswarehouse.com/");
        $this->configHost = require __DIR__ . "/config/host.php";
        $this->configEnvironment = require __DIR__ . "/config/environment.php";

        $this->setHost($this->configHost["host"]);
        $this->setBrowser("chrome");
        $this->setPort($this->configHost["port"]);
        $this->setBrowserUrl($this->configEnvironment["staging"]);

        $this->assertTrue(true);

        $chromeOptionsArr = array(
            "args" => array(
                //'--headless',
                '--window-size=1200,800',
                '--user-agent=Mozilla/5.0 (X11; Fedora; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3071.115 Safari/537.36 usap_selenium',
            ),
        );

        $param = array(
            "acceptInsecureCerts" => true,
            "chromeOptions" => $chromeOptionsArr,
            "goog:chromeOptions" => $chromeOptionsArr,
        );

        $this->setDesiredCapabilities($param);

        $this->filename = __DIR__ . "/reports/test-result.html";
        $this->fp = fopen($this->filename, 'w');

        $data = '<!DOCTYPE html>
<html>
    <head>
        <!-- Bootstrap core CSS-->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Page level plugin CSS-->
        <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="css/sb-admin.css" rel="stylesheet">
        <style>
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            td{
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }
            thead tr {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
                background-color: navy;
                color: white;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-automobile"></i>Vehicle Landing Page Simulator</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Scenario</th>
                                    <th>Expected Result</th>
                                    <th>Actual Result</th>
                                    <th>Status</th>
                                    <th>Screenshot</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Scenario</th>
                                    <th>Expected Result</th>
                                    <th>Actual Result</th>
                                    <th>Status</th>
                                    <th>Screenshot</th>
                                </tr>
                            </tfoot>
                            <tbody>';

        fwrite($this->fp, $data);
    }

    public function testApi() {
      $this->pass = 0;
      $this->fail = 0;
        $this->validateCatalogService();
      $this->matrix();
        $data = '</tbody></table>';
        fwrite($this->fp, $data);

        $data = 'Total number of PASSED: ' . $this->pass . '<br>' . 'Total number of FAILED: ' . $this->fail . '<br>';
        fwrite($this->fp, $data);

                  $data = '</div>
                </div>
                <div class="card-footer small text-muted"></div>
            </div>
        </div>';
        '<!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <!-- Page level plugin JavaScript-->
        <script src="vendor/datatables/jquery.dataTables.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin.min.js"></script>
        <!-- Custom scripts for this page-->
        <script src="js/sb-admin-datatables.min.js"></script>

    </body>
</html>';
        fwrite($this->fp, $data);
        fclose($this->fp);
    }

    public function onNotSuccessfulTest(Throwable $e) {

            $this->createScreenshot("thereIsError.png");

            echo $e->getMessage() . "\n\n";
            echo $e->getTraceAsString();
        }

    /* #####################################################################################*/
        public function createScreenshot($fileName = "fileNameNotSet.png") {
            $screenshotDir = __DIR__ . "/screenshots/";

            $base64 = base64_decode($this->screenshot());
            file_put_contents($screenshotDir . $fileName, $base64);
        }
        public function checkVehicleSelector()
        {
            sleep(3);
            $scenario = "Check Vehicle Selector is present";
            $expected = "present";
            $partfinderWidget = "#Partfinder";
            $partfinder = $this->byCssSelector($partfinderWidget);

            //element is displayed
            if ($partfinder->displayed() == true) {
              $actual = "present";
              $screenshot = "-";
                echo "\nVehicle Selector is present -PASSED";
                $this->createScreenshot("VehicleSelector.png");
            } else {
              $actual = "not present";
                // $this->createScreenshot("VehicleSelectorFailed.png");
                $timestamp = strtotime('now');
                $screenshotFile = $timestamp . ".png";
                $this->createScreenshot($screenshotFile);

                $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
                echo "\nVehicle Selector is missing -FAILED";
            }
            $this->writeReport($scenario, $expected, $actual, $screenshot);
        }

        public function selectYear()
        {
            $expected = "2009";
            $scenario = "Check year selector";
            $year = $this->byCssSelector("li.part-finder-vehicleyear.part-finder-vehicleenabled");
            $year->click();
            $year = $this->byCssSelector("ul.vehicleyear:nth-child(3) li:nth-child(2)");
            $actual = $year->text();
            $year->click();
            if ($expected == $actual) {
              sleep(2);

              echo "\nYear is present: " . $actual . " -PASSED";
              $screenshot = "-";
              $this->createScreenshot("Year.png");
              sleep(2);
            } else {
              echo "\nYear is not present: " . $actual . " -FAILED";
              $timestamp = strtotime('now');
              $screenshotFile = $timestamp . ".png";
              $this->createScreenshot($screenshotFile);

              $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>";
              // $this->createScreenshot("YearFailed.png");
              // echo "\nYear is missing";
            }
            $this->writeReport($scenario, $expected, $actual, $screenshot);
        }

        public function selectMake()
        {

            $expected = "Dodge";
            $scenario = "Check make selector";
            sleep(3);
            $make = $this->byCssSelector("ul.vehiclemake:nth-child(2) li:nth-child(11)");
            $actual = $make->text();
            if ($expected == $actual) {
              $make->click();
              $screenshot = "-";
              $this->createScreenshot("YearMake.png");
              sleep(2);
              echo "\nMake is present: " .$actual. " -PASSED";
            } else {

              // $this->createScreenshot("YearMakeFailed.png");
                echo "\nMake is missing" . $actual. " -FAILED";
                $timestamp = strtotime('now');
                $screenshotFile = $timestamp . ".png";
                $this->createScreenshot($screenshotFile);
                $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>";

            }
            $this->writeReport($scenario, $expected, $actual, $screenshot);

        }

        public function selectModel()
        {
            $scenario = "Check model selector";
            $expected = "Ram 1500";
            sleep(3);
            $model = $this->byCssSelector("ul.vehiclemodel:nth-child(4) li:nth-child(1)");
            $actual = $model->text();
            if ($expected == $actual) {
              $model->click();
              $screenshot = "-";
              $this->createScreenshot("YearMakeModel.png");
              sleep(2);
              echo "\nModel is present: " . $actual. " -PASSED";
            } else {
              // $this->createScreenshot("YearMakeModelFailed.png");
                echo "\nModel is missing: " .$actual. " -FAILED";
                $timestamp = strtotime('now');
                $screenshotFile = $timestamp . ".png";
                $this->createScreenshot($screenshotFile);

                $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>";

            }
            $this->writeReport($scenario, $expected, $actual, $screenshot);
        }

        public function selectSubmodel()
        {
            $scenario = "Check submodel selector";
            $expected = "SLT";
            sleep(3);
            $submodel = $this->byCssSelector(".single li:nth-child(2)");
            $actual = $submodel->text();
            if ($expected == $actual) {
              $submodel->click();
              $screenshot = "-";
              $this->createScreenshot("YearMakeModelSubmodel.png");
              sleep(2);
              echo "\nSubmodel is present: " .$actual. " -PASSED";
            } else {
              // $this->createScreenshot("YearMakeModelSubmodelFailed.png");
                echo "\nSubmodel is missing: " .$actual. " -FAILED";
                $timestamp = strtotime('now');
                $screenshotFile = $timestamp . ".png";
                $this->createScreenshot($screenshotFile);

                $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>";

            }
            $this->writeReport($scenario, $expected, $actual, $screenshot);
        }

        public function selectEngine()
        {
            $scenario = "Check engine selector";
            $expected = "8 Cyl 5.7L";
            sleep(3);
            $engine = $this->byCssSelector(".vehicleengine li:nth-child(2)");
            $actual = $engine->text();
            if ($expected == $actual) {
              $engine->click();
              $screenshot = "-";
              $this->createScreenshot("YearMakeModelSubmodelEngine.png");
              sleep(2);
              echo "\nEngine is present: " . $actual . " -PASSED";
              // echo "\nEngine is selected";
            } else {
              // $this->createScreenshot("YearMakeModelSubmodelEngineFailed.png");
                echo "\nEngine is missing: " .$actual. " -FAILED";
                $timestamp = strtotime('now');
                $screenshotFile = $timestamp . ".png";
                $this->createScreenshot($screenshotFile);

                $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>";

            }
            $this->writeReport($scenario, $expected, $actual, $screenshot);

            sleep(10);
        }
        public function checkMyVehicle(){
          $scenario = "Check MyVehicle YMMSE";
          $expected = "2008 Dodge Ram 1500 SLT 8 Cyl 5.7L";
          $ymm = $this->byCssSelector("#toAppendYMM");
          $se = $this->byCssSelector("#toAppendSE")->text();
          // $se_text = $se->text();
          $actualValue = str_replace("\n(Browse Parts)", "", $se);
          $actual = $ymm->text() . " " . $actualValue;
          if ($expected == $actual) {
            $screenshot = "-";
            $this->createScreenshot("Myvehicle.png");
            sleep(2);
            echo "\nMyVehicle is present = " . $actual . " -PASSED" . "\n";
          } else {
            $status = "FAILED";
            // $this->createScreenshot("MyvehicleFailed.png");
              echo "\nMyVehicle is missing: " . $status;
              $timestamp = strtotime('now');
              $screenshotFile = $timestamp . ".png";
              $this->createScreenshot($screenshotFile);

              $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>";

          }
          $this->writeReport($scenario, $expected, $actual, $screenshot);

        }

        public function checkYMMSEtext(){
          $scenario = "Check selected YMMSE Text";
          $expected = "2008 Dodge Ram 2500 SLT 8 Cyl 5.7L";
          $ymmse = $this->byCssSelector("h1.fl")->text();
          // $ymm_text = $ymmse->text();
          $actual = str_replace("Your selected vehicle:\n", "", $ymmse);
          if ($expected == $actual) {
            $this->createScreenshot("H1Text.png");
            $screenshot = "-";
            sleep(2);
            echo "Your selected vehicle: " .$actual. " -PASSED";

          } else {
            $status = "FAILED";
            // $this->createScreenshot("H1TextFailed.png");
              echo "\nYMSMSE selected text is not match!: " .$actual;
              $timestamp = strtotime('now');
              $screenshotFile = $timestamp . ".png";
              $this->createScreenshot($screenshotFile);

              $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>";

          }
          $this->writeReport($scenario, $expected, $actual, $screenshot);

          // $expectedValue = "2008 Dodge Ram 1500 SLT 8 Cyl 5.7L";
          // echo $actualValue ."\n";
          // echo $expectedValue . "\n";
          //
          // if ($expectedValue == $actualValue) {
          //     echo "\nExpected is equal to actual";
          // } else {
          //     echo "\nExpected is not equal to actual";
          // }
        }


    public function validateCatalogService() {

        $this->url("/");

        $this->checkVehicleSelector();
        $this->selectYear();
        $this->selectMake();
        $this->selectModel();
        $this->selectSubmodel();
        $this->selectEngine();
        $this->checkMyVehicle();
        $this->checkYMMSEtext();

    //     foreach ($dataArr as $data) {
    //         // echo $data['scenario'];
    //         // print_r($data);
    //         $scenario = $data['scenario'];
    //         $expected = $data['expected'];
    //         $actual = isset($data['actual']);
    //         $this->writeReport($scenario, $expected, $actual);
    //     }
    //     foreach ($performanceArr as $data) {
    //         // echo $data['scenario'];
    //         // print_r($data);
    //         $scenario = $data['scenario'];
    //         $expected = $data['expected'];
    //         $actual = $data['actual'];
    //         $this->writeReport($scenario, $expected, $actual); //display TD by rows
    //     }
    }

    public function writeReport($scenario, $expected, $actual, $screenshot) {
        if (is_bool($expected) and ( $expected == true)) {
            $expected_text = "true";
        } elseif (is_bool($expected) and ( $expected == false)) {
            $expected_text = "false";
        } else {
            $expected_text = "$expected";
        }

        if (is_bool($actual) && ($actual == true)) {
            $actual_text = "true";
        } elseif (is_bool($actual) && ($actual == false)) {
            $actual_text = "false";
        } else {
            $actual_text = $actual;
        }
        if ($expected == $actual) {
            $status = "Passed";
            $color = "#629632";
            $this->pass++;
        } else {
            $status = "Failed";
            $color = "#FF0000";
            $this->fail++;
        }
        $data = '<tr>
                               <td>' . $scenario . '</td>
                               <td>' . $expected_text . '</td>
                               <td>' . $actual_text . '</td>
                               <td><b><font color =' . $color . '>' . $status . '</font></b></td>
                               <td>' . $screenshot . '</td>
                             </tr>';

        fwrite($this->fp, $data);
    }

    public function matrix(){
          echo "\n\n";

          echo "Summary of Passed and Failed Scenarios: \n";
          echo "Total of Passed: " . $this->pass . "\n\n";
          echo "Total of Failed: " . $this->fail . "\n\n";
        }

    public function getContents($url) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1 usap_selenium');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);

        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }

    public function loadCurl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROXY, "10.10.70.150:8080");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1 usap_selenium');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);


        $content = curl_exec($ch);

        curl_close($ch);

        return $content;
    }

}

?>
