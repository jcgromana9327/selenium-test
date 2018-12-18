
<?php

// class MyApiTest extends PHPUnit_Framework_TestCase {
// use PHPUnit\Framework\TestCase;
//
// class MyApiTest extends TestCase {

class MySeleniumSuite extends PHPUnit_Extensions_Selenium2TestCase {

    public function setUp() {

        $this -> configHost = require __DIR__ . "/config/host.php";
        $this -> configEnvironment = require __DIR__ . "/config/environment.php";
        $this -> configUserAgent = require __DIR__ . "/config/userAgent.php";
        $this -> configWindowSize = require __DIR__ . "/config/windowSize.php";
        $this -> setHost($this -> configHost["host"]);
        $this -> setBrowser("chrome");
        $this -> setPort($this -> configHost["port"]);
        $this -> setBrowserUrl($this -> configEnvironment["CPOstaging"]);
        $this -> assertTrue(true);
        $windowSize = $this -> configWindowSize["Desktop"];
        $userAgent = $this -> configUserAgent["Desktop"];
        $chromeOptionsArr = array(
            "args" => array(
                //'--headless',
                "--window-size=$windowSize",
                "--user-agent=$userAgent",
            ),
        );
        $param = array(
            "acceptInsecureCerts" => true,
            "chromeOptions" => $chromeOptionsArr,
            "goog:chromeOptions" => $chromeOptionsArr,
        );
        $this -> setDesiredCapabilities($param);
        $this -> filename = __DIR__ . "/reports/paypal-test-result.html";
        $this -> fp = fopen($this -> filename, 'w');
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
                    <i class="fa fa-automobile"></i>PayPal Payment Test Results</div>
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

        $this -> validateCatalogService();
        $data = '</tbody></table>
                    </div>
                </div>
                <div class="card-footer small text-muted"></div>
            </div>
        </div>
        <!-- Bootstrap core JavaScript-->
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

        $this -> createScreenshot("thereIsError.png");
        echo $e -> getMessage() . "\n\n";
        echo $e -> getTraceAsString();
    }

    /* ##################################################################################### */

    public function createScreenshot($fileName = "fileNameNotSet.png") {
        $screenshotDir = __DIR__ . "/screenshots/";
        $base64 = base64_decode($this -> screenshot());
        file_put_contents($screenshotDir . $fileName, $base64);
    }

    public function homepage() {
        sleep(3);
        $scenario = "Check Popular Parts: Mirror";
        $expected = "present";
        $screenshot = "-";
        $popularParts = $this -> byCssSelector("div.mostPopularBox:nth-child(1) > div:nth-child(1)");
        $this -> moveto($popularParts);
        if ($popularParts -> displayed() == true) {
            $actual = "present";
            echo "\nShop for Mirrors is present: PASSED \n";
            $this -> createScreenshot("homepage.png");
            $popularParts -> click();
            // echo "Redirected To SERP \n";
        } else {
            $actual = "not present";
            echo "\nShop for Mirrors is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
    }

    public function serp() {
        // moveto 2nd SKU
        $moveto = "div.result:nth-child(5) > div:nth-child(2) > div:nth-child(1) > div:nth-child(3) > div:nth-child(1) > div:nth-child(2) > div:nth-child(1) > ul:nth-child(1) > li:nth-child(6)";
        $moveto = $this -> byCssSelector($moveto);
        $this -> moveto($moveto);
        sleep(3);
        // Add to Cart
        $scenario = "Check Add to Cart Button in SERP";
        $expected = "present";
        $addToCart = $this -> byCssSelector("div.result:nth-child(5) > div:nth-child(2) > div:nth-child(1) > div:nth-child(3) > div:nth-child(2) > div:nth-child(1) > form:nth-child(1) > div:nth-child(2) > div:nth-child(1) > input:nth-child(1)");
        if ($addToCart -> displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            echo "Add to cart button is present: PASSED \n";
            $this -> createScreenshot("addToCartBtn.png");
            $addToCart -> click();
            // echo "Redirected to Basket Page \n";
        } else {
            $actual = "not present";
            echo "Add to cart button is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
    }

    public function basket() {
        sleep(3);
        $scenario = "Check Secured Button in Cart Page";
        $expected = "present";
        $checkout = $this -> byCssSelector(".checkOutHolder");
        if ($checkout -> displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            echo "Secured Button is present: PASSED \n";
            $this -> createScreenshot("securedBtn.png");
            $checkout -> click();
        } else {
            $actual = "not present";
            echo "Secured button is missing: Failed \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
    }

    public function checkout() {
        $scenario = "Check first name textbox";
        $expected = "present";
        $fnameInput = $this -> byCssSelector("#customer_first_name");
        if ($fnameInput -> displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            $fnameInput -> value("Tester");
            echo "Firstname textbox is present: PASSED \n";
            $this -> createScreenshot("checkoutFname.png");
            sleep(1);
        } else {
            $actual = "not present";
            echo "Firstname textbox is missing: FAILED \n ";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
        $scenario = "Check last name textbox";
        $expected = "present";
        $lnameInput = $this->byCssSelector("#customer_last_name");
        if ($lnameInput->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            $lnameInput -> value("Order");
            echo "Lastname textbox is present: PASSED \n";
            $this -> createScreenshot("checkoutLname.png");
            sleep(1);
        } else {
            $actual = "not present";
            echo "Lastname textbox is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check stress address";
        $expected = "present";
        $addInput = $this->byCssSelector("#customer_street_address");
        if ($addInput->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            $addInput -> value("2740 32 Ave NE");
            echo "Address textbox is present: PASSED \n";
            $this -> createScreenshot("checkoutAddress.png");
            sleep(1);
        } else {
            $actual = "not present";
            echo "Address textbox is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check Customer city";
        $expected = "present";
        $cityInput = $this->byCssSelector("#customer_city");
        if ($cityInput->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            $cityInput -> value("Calgary");
            echo "City textbox is present: PASSED \n";
            $this -> createScreenshot("checkoutCity.png");
            sleep(1);
        } else {
            $actual = "not present";
            echo "City textbox is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check Customer State";
        $expected = "present";
        $stateDropdown = $this->byCssSelector("#customer_state");
        if ($stateDropdown->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            $stateDropdown -> value("A");
            echo "State textbox is present: PASSED \n";
            $this -> createScreenshot("checkoutState.png");
            sleep(1);
        } else {
            $actual = "not present";
            echo "State textbox is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check Customer Postal Code";
        $expected = "present";
        $zipcodeInput = $this->byCssSelector("#customer_postcode");
        if ($zipcodeInput->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            $zipcodeInput -> value("T1Y2S2");
            echo "Postal code textbox is present: PASSED \n";
            $this -> createScreenshot("checkoutPostalcode.png");
            sleep(1);
        } else {
            $actual = "not present";
            echo "Postal Code textbox is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $footer = ".lockp";
        $footer = $this->byCssSelector($footer);

        $scenario = "Check Customer phone number";
        $expected = "present";
        $phoneNumber = $this->byCssSelector("#customer_phone_parts_1");

        if ($phoneNumber->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            $phoneNumber -> value("1233123221");
            $this -> moveto($footer);
            echo "Phone number textbox is present: PASSED \n";
            $this -> createScreenshot("checkoutPhoneNumber.png");
            sleep(1);
        } else {
            $actual = "not present";
            echo "Phone number textbox is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
        $scenario = "Check email address";
        $expected = "present";
        $emailAdd = $this->byCssSelector("#customer_email_address");
        if ($emailAdd->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            $emailAdd -> value("tester_order@yahoo.com");
            echo "Email address textbox is present: PASSED \n";
            $this -> createScreenshot("checkoutPhoneNumber.png");
            sleep(1);
        } else {
            $actual = "not present";
            echo "Email address textbox is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check Continue button in checkout page";
        $expected = "present";
        $checkout = $this->byCssSelector("#ctl00_Body_btnContinueCheckout");
        if ($checkout->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            $checkout -> click();
            echo "Continue button is clicked: PASSED \n";
            // echo "Redirected to Checkout Payment Page \n";
            $this -> createScreenshot("checkoutBtn.png");
            sleep(10);
        } else {
            $actual = "not present";
            echo "Continue button is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
    }

    public function checkoutPayment() {
        $scenario = "Check Paypal radio button";
        $expected = "present";
        $footer = $this -> byCssSelector(".lockp");
        $paypal = $this -> byCssSelector("#payment_method_paypal");
        if ($paypal -> displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            $this -> moveto($footer);
            echo "Paypal radio button is present: PASSED \n";
            $this -> createScreenshot("checkoutBtn.png");
            $paypal -> click();
        } else {
            $actual = "not present";
            echo "Paypal radio button is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
        $scenario = "Check Place my Order in Checkout Page";
        $expected = "present";
        $placeorder = $this->byCssSelector("li.placeMyOrder");
        if ($placeorder->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            echo "Place my Order button is present: PASSED \n";
            $this -> createScreenshot("checkoutBtn.png");
            $placeorder -> click();
            sleep(10);
        } else {
            $actual = "not present";
            echo "Place my Order is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
    }

    public function paypalLogin() {
        $scenario = "Check Paypal login in button";
        $expected = "present";
        $login = $this -> byCssSelector("a.btn");
        if ($login -> displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            echo "Paypal Login button is present: PASSED \n";
            $this -> createScreenshot("checkoutPaypalBtn.png");
            $login -> click();
            sleep(3);
        } else {
            $actual = "not present";
            echo "Paypal Login button is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check paypal email field";
        $expected = "present";
        $email = $this->byCssSelector("#email");
        if ($email->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            $email -> value("tester_order@yahoo.com");
            echo "Paypal email field is present: PASSED \n";
            $this -> createScreenshot("checkoutPaypalEmail.png");
            sleep(3);
        } else {
            $actual = "not present";
            echo "Paypal email field is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check Next Button in Paypal";
        $expected = "present";
        $nextButton = $this->byCssSelector("#btnNext");
        if ($nextButton->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            echo "Paypal next button is present: PASSED \n";
            $this -> createScreenshot("checkoutPaypalNextBtn.png");
            $nextButton -> click();
            sleep(3);
        } else {
            $actual = "not present";
            echo "Paypal next button is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
        $scenario = "Check Paypal Password";
        $expected = "present";
        $password = $this->byCssSelector("#password");
        if ($password->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            $password -> value("usap1q2w");
            echo "Paypal Password is present: PASSED \n";
            $this -> createScreenshot("checkoutPaypalPwd.png");
            sleep(3);
        } else {
            $actual = "not present";
            echo "Paypal password is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
        $scenario = "Check Paypal Login";
        $loginPaypal = $this->byCssSelector("#btnLogin");
        if ($loginPaypal->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            echo "Paypal Login button is present: PASSED \n";
            $this -> createScreenshot("checkoutPaypalLoginBtn.png");
            $loginPaypal -> click();
            sleep(15);
        } else {
            $actual = "not present";
            echo "Paypal login button is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
        $scenario = "Check continue to payment if present";
        $expected = "present";
        $continuePayment = $this->byCssSelector("#confirmButtonTop");
        if ($continuePayment->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            echo "Paypal Confirm button is present: PASSED \n";
            $this -> createScreenshot("checkoutPaypalConfirmBtn.png");
            $continuePayment -> click();
            sleep(15);
        } else {
            $actual = "not present";
            echo "Paypal confirm button is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
    }

    public function reviewpage() {
        //   $scenario = "Check Shipping Value";
        //   $expected = "$31.95";
        //   $shippingValue = ".subTotal > ul:nth-child(1) > li:nth-child(2) > span:nth-child(2)";
        //   $shippingValue = $this->byCssSelector($shippingValue);
        //   if($shippingValue->displayed() == true){
        //     $actual = "present";
        //     echo "Shipping is present: PASSED \n";
        //     $this->createScreenshot("shipping.png");
        //     $shippingValue = $shippingValue->text();
        //     echo "Shipping Value: " . $shippingValue . "\n";
        //
                //   sleep(15);
        // }else {
        //   $actual = "not present";
        //   echo "Shipping is missing: FAILED \n";
        //   $this->createScreenshot("shippingMissing.png");
        // }

        $scenario = "Check Review Page Place order";
        $expected = "present";
        $placeorderBtn = $this -> byCssSelector(".placeMyOrder");
        if ($placeorderBtn -> displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            echo "Review page place order is present: PASSED \n";
            $this -> moveto($placeorderBtn);
            $this -> createScreenshot("placeorder.png");
            $placeorderBtn -> click();
            sleep(10);
        } else {
            $actual = "not present";
            echo "Place order is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
Order ID: CPO1213212
        $scenario = "Check CPO Order Number";
        $expected = "present";
        $orderno = $this->byCssSelector(".orderid");
        if ($orderno->displayed() == true) {
            $actual = "present";
            $screenshot = "-";
            $ordernotxt = $orderno -> text();  Order ID:
            $orderNumVal = str_replace("Order ID: ", "", $ordernotxt); CPO1213212
            echo "Order number is present: PASSED \n";
            // echo "Order number is: " .$orderNumVal . "\n";
            $this -> createScreenshot("orderNumber.png");
            sleep(3);
        } else {
            $actual = "not present";
            echo "Order Number is missing: FAILED \n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check Payment Method";
        $expected = "paypal";
        $paymentMethod = $this->byCssSelector("div.orderDetailsHldr:nth-child(4) > span:nth-child(2)")->text();
        $payMethodVal = strtolower(str_replace("\ntest order\n9WZP2NZYYCVPE\nTester Order\nverified", "", $paymentMethod));
        if ($payMethodVal == $expected) {
            echo "Payment Method: " . $payMethodVal . "\n";
            $this -> createScreenshot("paymentMethod.png");
            sleep(3);
        } else {
            echo "Incorrect Payment Method: " . $payMethodVal . "\n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
        }

        $scenario = "Check Shipping Method";
        $expected = "Ground";
        $shipMethod = $this->byCssSelector("div.orderDetailsHldr:nth-child(5) > span:nth-child(2)")->text();
        if ($shipMethod == "UPSGND") {
            $shipMethod = "Ground";
        }
        if ($shipMethod == $expected) {
            echo "Shipping Method: " . $shipMethod . "\n";
            $this -> createScreenshot("shippingMethod.png");
        } else {
            echo "Shipping Method: " . $shipMethod . "\n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
        }

        $scenario = "Check SKU Number";
        $expected = "GM24ER";
        $sku = $this->byCssSelector(".productNumber")->text();
        $sku = str_replace("Item Number:", "", $sku);
        if ($sku == $expected) {
            echo "SKU Item: " . $sku . "\n";
            $this -> createScreenshot("partNumber.png");
        } else {
            echo "SKU Item: " . $sku . "\n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
        }
        $scenario = "Check Total Price";
        $expected = "";
        $price = ".productTotal > span:nth-child(2)";
        $totalPrice = $this->byCssSelector($price)->text();
        $totalPrice = str_replace("USD ", "", $totalPrice);

        $scenario = "Check Estimated Tax";
        $expected = "";
        $taxSelector = ".subTotal > ul:nth-child(1) > li:nth-child(4) > span:nth-child(2)";
        $tax = $this->byCssSelector($taxSelector)->text();
        $tax = str_replace("USD ", "", $tax);

        $scenario = "Check Handling";
        $expected = "";
        $handling = $this->byCssSelector("span.freehandlingtext")->text();
        if ($handling == "FREE") {
            $handlingVal = "0.00";
        }

        $scenario = "Check Shipping";
        $expected = "";
        $shipping = $this->byCssSelector(".subTotal > ul:nth-child(1) > li:nth-child(2) > span:nth-child(2)")->text();
        $shipping = str_replace("$", "", $shipping);

        $scenario = "Check Subtotal";
        $expected = "";
        $subtotal = $this->byCssSelector(".subTotal > ul:nth-child(1) > li:nth-child(1) > span:nth-child(2)")->text();
        $subtotal = str_replace("USD ", "", $subtotal);

        $this->manager($orderNumVal, $payMethodVal, $shipMethod, $sku, $totalPrice, $tax, $handlingVal, $shipping, $subtotal);
    }


    public function manager($orderNumVal, $payMethodVal, $shipMethod, $sku, $totalPrice, $tax, $handlingVal, $shipping, $subtotal) {
        $this -> url("http://manager.staging.usautoparts.com/login.php");
        sleep(5);
        $user = "#username";
        $user = $this -> byCssSelector($user) -> value("svcQATest");
        $pwd = "div.bgContentbox:nth-child(2) > table:nth-child(1) > tbody:nth-child(1) > tr:nth-child(1) > td:nth-child(2) > table:nth-child(1) > tbody:nth-child(1) > tr:nth-child(3) > td:nth-child(2) > input:nth-child(1)";
        $pwd = $this -> byCssSelector($pwd) -> value("58WHUqhM!");
        $this -> byCssSelector(".Button") -> click();
        sleep(5);
        $orderno = "#hdnOrderId";
        $orderno = $this->byCssSelector($orderno)->value("$orderNumVal");
        $search = "input.Button:nth-child(1)";
        $search = $this->byCssSelector($search);
        $search -> click();
        sleep(10);

        // $scenario = "Check SKU in Manager if match";
        // $skuSelector = "#row1500128100 > td:nth-child(2)";
        // $managerPartNo = $this->byCssSelector($skuSelector);
        // $this->moveto($managerPartNo);
        // sleep(5);
        // $mgrPartNoText = $managerPartNo->text();
        // $detailsArr = explode(" ", $mgrPartNoText);
        // // $sku = "GM24ER";
        // echo "Manager SKU Partnumber: " . $detailsArr[0] . "\n";
        // $detailsArr = strpos($detailsArr[0] , $sku);
        // $expected = $sku;
        // $actual = $detailsArr[0];
        // if($detailsArr !== false){
        //           $screenshot = "-";
        //           echo "Manager SKU partnumber Match: = " .$sku. "\n";
        //         }else {
        //           echo "Manager SKU partnumber Did not Match: = " .$sku. "\n";
        //           $timestamp = strtotime('now');
        //           $screenshotFile = $timestamp . ".png";
        //           $this -> createScreenshot($screenshotFile);
        //           $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        //       }
        // $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check Order number in Manager if match";
        $merchantno = "td.pad10px:nth-child(2) > table:nth-child(10) > tbody:nth-child(1) > tr:nth-child(1) > td:nth-child(1) > table:nth-child(1) > tbody:nth-child(1) > tr:nth-child(2) > td:nth-child(2)";
        $merchantno = $this -> byCssSelector($merchantno) -> text();
        $expected = $merchantno;
        $actual = $orderNumVal;
        if ($expected == $actual) {
            $screenshot = "-";
            $this -> createScreenshot("orderNoManager.png");
            echo "Manager Order Number Match: " . $expected . " = " . $actual . "\n";
        } else {
            echo "Order Number Did Not Match : " . $expected . " != " . $actual . "\n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check Payment Method in Manager if match";
        $mngrPaymentMethod = "td.pad10px:nth-child(2) > table:nth-child(10) > tbody:nth-child(1) > tr:nth-child(8) > td:nth-child(1) > table:nth-child(1) > tbody:nth-child(1) > tr:nth-child(1) > td:nth-child(1) > div:nth-child(1) > div:nth-child(2) > form:nth-child(1) > table:nth-child(1) > tbody:nth-child(1) > tr:nth-child(1) > td:nth-child(2)";
        $mngrPaymentMethod = strtolower($this->byCssSelector($mngrPaymentMethod)->text());
        $expected = $mngrPaymentMethod;
        $actual = $payMethodVal;

        if ($expected == $actual) {
            $screenshot = "-";
            $this -> createScreenshot("paymentMethodMnger.png");
            echo "Manager Payment Method Match: " . $expected . " = " . $actual . "\n";
        } else {
            echo "Manager Payment Method Did Not Match: " . $expected . "!= " . $actual . "\n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check Shipping Method in Manager if match";
        $mngrShipMethod = $this->byCssSelector("#shipping_method > option:nth-child(49)")->text();
        $mngrShipMethod = str_replace("UPS: ", "", $mngrShipMethod);
        $expected = $shipMethod;
        $actual = $mngrShipMethod;

        if ($expected == $actual) {
            $screenshot = "-";
            $this -> createScreenshot("shipMethodMnger.png");
            echo "Manager Shipping Method Match: " . $expected . " = " . $actual . "\n";
        } else {
            echo "Manager Shipping Method Did Not Match: " . $expected . "!= " . $actual . "\n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $paymentDetails = "#tableWidth > tbody:nth-child(1) > tr:nth-child(3) > td:nth-child(1)";
        $paymentDetailSelector = $this -> byCssSelector($paymentDetails);
        $this -> moveto($paymentDetailSelector);
        sleep(10);
        $paymentDetailsText = $paymentDetailSelector -> text();
        // echo $paymentDetailsText;
        // $tempArr = "Total Auth: $0.00 | Total Payment: $206.73 | Shipping: $0.00 | Handling: $0.00 | Discount: -$0.00 | Tax: $17.33 | Parts: $189.40 | Total: $206.73";
        $detailsArr = explode(" | ", $paymentDetailsText);
        $totalMngr = str_replace("Total: $", "", $detailsArr[7]);
        $taxMngr = str_replace("Tax: $", "", $detailsArr[5]);
        $handlingMngr = str_replace("Handling: $", "", $detailsArr[3]);
        $shippingMngr = str_replace("Shipping: $", "", $detailsArr[2]);
        $subtotalMngr = str_replace("Parts: $", "", $detailsArr[6]);
        $scenario = "Check Manager Total Price if match";
        $expected = $totalMngr;
        $actual = $totalPrice;
        if ($expected == $actual) {
            $screenshot = "-";
            $this -> createScreenshot("totalPriceMngr.png");
            echo "Manager Total Price Match: " . $expected . " = " . $actual . "\n";
        } else {
            echo "Manager Total Price Did Not Match: " . $expected . "!= " . $actual . "\n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check Manager Tax Price if match";
        $expected = $taxMngr;
        $actual = $tax;
        if ($expected == $actual) {
            $screenshot = "-";
            $this -> createScreenshot("taxPriceMngr.png");
            echo "Manager Tax Price Match: " . $expected . " = " . $actual . "\n";
        } else {
            echo "Manager Tax Price Did Not Match: " . $expected . "!= " . $actual . "\n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check Manager Handling Price if match";
        $expected = $handlingMngr;
        $actual = $handlingVal;

        if ($expected == $actual) {
            $screenshot = "-";
            $this -> createScreenshot("handlingMnger.png");
            echo "Manager Handling Price Match: " . $expected . " = " . $actual . "\n";
        } else {
            echo "Manager Handling Price Did Not Match: " . $expected . "!= " . $actual . "\n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);
        $scenario = "Check Manager Shipping Value if match";
        $expected = $shippingMngr;
        $actual = $shipping;

        if ($expected == $actual) {
            $screenshot = "-";
            $this -> createScreenshot("shippingMnger.png");
            echo "Manager Shipping value Match: " . $expected . " = " . $actual . "\n";
        } else {
            echo "Manager Shipping value Did Not Match: " . $expected . "!= " . $actual . "\n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        $scenario = "Check Manager Subtotal Price if match";
        $expected = $subtotalMngr;
        $actual = $subtotal;

        if ($expected == $actual) {
            $screenshot = "-";
            $this -> createScreenshot("subTotalMnger.png");
            echo "Manager Subtotal Price Match: " . $expected . " = " . $actual . "\n";
        } else {
            echo "Manager Subtotal Price Did Not Match: " . $expected . "!= " . $actual . "\n";
            $timestamp = strtotime('now');
            $screenshotFile = $timestamp . ".png";
            $this -> createScreenshot($screenshotFile);
            $screenshot = "<a href=\"javascript:void(window.open('../screenshots/" . $screenshotFile . "','name','scrollbars=1,height=600,width=800'));\"><img src=\"../screenshots/" . $screenshotFile . "\" width=\"50\" height=\"50\" border=\"1\"><br>Click Here to enlarge.</a>'";
        }
        $this->writeReport($scenario, $expected, $actual, $screenshot);

        sleep(5);
    }

    public function validateCatalogService() {

        $this -> cookie() -> clear();
        $this -> url("/");
        $this -> homepage();
        $this -> serp();
        $this -> basket();
        $this -> checkout();
        $this -> checkoutPayment();
        $this -> paypalLogin();
        $this -> reviewpage();
        // $this -> manager();
        // $this->manager($orderNumVal,$payMethodVal, $shipMethod,$sku,$totalPrice,$tax,$handlingVal,$shipping,$subtotal);
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
        } else {
            $status = "Failed";
            $color = "#FF0000";
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
