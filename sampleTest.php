<?php

class MySeleniumSuite extends PHPUnit_Extensions_Selenium2TestCase {

    public function setUp(){


        $this->setHost("jromana-lion.perfectfitgroup.local");
        $this->setBrowser("chrome");
        $this->setBrowserUrl("http://localhost:4000/");
        // $this->setSeleniumServerRequestsTimeout(60);
        $this->assertTrue(true);
        $chromeOptionsArr = array(

            "args" => array(
                //'--headless',
                '--window-size=1300,1300',
                '--user-agent=Mozilla/5.0 (X11; Fedora; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3071.115 Safari/537.36 usap_selenium',
            ),
        );

        $param = array(
            "acceptInsecureCerts" => true,
            "chromeOptions" => $chromeOptionsArr,
            "goog:chromeOptions" => $chromeOptionsArr,
        );

        $this->setDesiredCapabilities($param);
    }

    public function testCart()
   {
       $this->cookie()->clear();
       $this->url("/");
       sleep(10);
      //  $this->homepage();
      //  $this->serp();
      //  $this->cart();
      //  $this->firstname();
      //  $this->lastname();
      //  $this->address();
      // $this->city();
      // $this->province();
      // $this->zipcode();
      // $this->phone();
      // $this->email();



   }
public function homepage(){
 $expected = "present";
 $popularParts = "#Ntt";
 $popularParts = $this->byCssSelector($popularParts);
 sleep(5);
 if ($popularParts->displayed() == true) {
     $actual = "present";
     echo "\nSearch Textbox is present: PASSED \n";
     // $this->createScreenshot("search.png");
     $popularParts->clear();
     $popularParts->value("GM1321198");
     // $popularParts->value("discountautomirrors");

     sleep(5);
 } else {
     $actual = "not present";
     echo "\nSearch Textbox is missing: FAILED \n";
  //   $this->createScreenshot("Error.png");
  }
//search button
  $goButton = "#nttsubmit > span";
  $elementButton = $this->byCssSelector($goButton);
  if ($elementButton->displayed() == true) {
     echo "\nGO button is present";
  } else {
     echo "\nGO button is missing";
 }
 $elementButton->click();
    sleep(5);
}
  public function serp(){
//PAYPAL - click add to paypal button
  $addButton = "div.formInputsHolder:nth-child(6) > div:nth-child(1) > input:nth-child(1)";
  $elementButton = $this->byCssSelector($addButton);
  if ($elementButton->displayed() == true) {
    echo "\nItem added to cart";
  } else {
    echo "\nError adding item";
  }
    $elementButton->click();
    sleep(5);
   }

   public function cart(){
     $checkoutButton = ".btn-large";
     $elementButton = $this->byCssSelector($checkoutButton);
     if ($elementButton->displayed() == true) {
       echo "\nItem added to cart";
     } else {
       echo "\nError adding item";
     }
       $elementButton->click();
       sleep(5);
 }

 public function firstname(){
  $expected = "present";
  $firstName = "#customer_first_name";
  $firstName = $this->byCssSelector($firstName);
  if ($firstName->displayed() == true) {
      $actual = "present";
      echo "\nFirst Name:PASSED \n";
      // $this->createScreenshot("search.png");
      $firstName->clear();
      $firstName->value("Tester");
      // $popularParts->value("discountautomirrors");
      sleep(5);
  } else {
      $actual = "not present";
      echo "\nFirst Name:FAILED \n";
   //   $this->createScreenshot("Error.png");
  }
    //
    //

    }

    public function lastname(){
        $expected = "present";
        $lastName = "#customer_last_name";
        $lastName = $this->byCssSelector($lastName);
        if ($lastName->displayed() == true) {
            $actual = "present";
            echo "\nLast Name:PASSED \n";
            // $this->createScreenshot("search.png");
            $lastName->clear();
            $lastName->value("Order");
            // $popularParts->value("discountautomirrors");
            sleep(1);
        } else {
            $actual = "not present";
            echo "\nLast Name:FAILED \n";
           $this->createScreenshot("Error.png");
        }
      }
      public function address(){
          $expected = "present";
          $address = "#customer_street_address";
          $address = $this->byCssSelector($address);
          if ($address->displayed() == true) {
              $actual = "present";
              echo "\nStreet Address:PASSED \n";
              // $this->createScreenshot("search.png");
              $address->clear();
              $address->value("17150 S Margay Ave");
              // $popularParts->value("discountautomirrors");
              sleep(1);
          } else {
              $actual = "not present";
              echo "\nStreet Address:FAILED \n";
           //   $this->createScreenshot("Error.png");
              }
            }
            public function city(){
                $expected = "present";
                $city = "#customer_city";
                $city = $this->byCssSelector($city);
                if ($city->displayed() == true) {
                    $actual = "present";
                    echo "\nCity:PASSED \n";
                    // $this->createScreenshot("search.png");
                    $city->clear();
                    $city->value("Carson");
                    // $popularParts->value("discountautomirrors");
                    sleep(1);
                } else {
                    $actual = "not present";
                    echo "\nCity:FAILED \n";
                 //   $this->createScreenshot("Error.png");
                    }

                  }
                  public function province(){
                      $province = $this->byCssSelector("#customer_state");
                      $this->select($province)->selectOptionByValue("CA");

                      sleep(2);
                }

              public function zipcode(){
                  $expected = "present";
                  $zipcode = "#customer_postcode";
                  $zipcode = $this->byCssSelector($zipcode);
                  if ($zipcode->displayed() == true) {
                      $actual = "present";
                      echo "\nZip Code:PASSED \n";
                      // $this->createScreenshot("search.png");
                      $zipcode->value("90746");
                      // $popularParts->value("discountautomirrors");
                      sleep(1);
                  } else {
                      $actual = "not present";
                      echo "\nZip Code:FAILED \n";
                   //   $this->createScreenshot("Error.png");
                      }
                    }


              public function phone(){
                        $expected = "present";
                        //$this->moveto($phone1);
                        $phone1 = "#customer_phone_parts_1";
                        $phone1 = $this->byCssSelector($phone1);

                        $phone2 = "#customer_phone_parts_2";
                        $phone2 = $this->byCssSelector($phone2);

                        $phone3 = "#customer_phone_parts_3";
                        $phone3 = $this->byCssSelector($phone3);

                        if ($phone1->displayed() == true) {
                            $actual = "present";
                            echo "\nPhone Number:PASSED \n";
                            // $this->createScreenshot("search.png");

                                // $phone1->clear();
                                      sleep(1);
                                $phone1->value("111");
                                      sleep(1);
                                $phone2->value("111");
                                      sleep(1);
                                $phone3->value("1111");
                                      sleep(1);


                        } else {
                                $actual = "not present";
                                echo "\nPhone Number:FAILED \n";


                             //   $this->createScreenshot("Error.png");
                        }
                      }
                      public function email(){
                          $email = "present";
                          $email = "#customer_email_address";
                          $email = $this->byCssSelector($email);
                          if ($email->displayed() == true) {
                              $actual = "present";
                              echo "\nE-mail:PASSED \n";
                              // $this->createScreenshot("search.png");
                              sleep(1);
                              $email->value("testerorder@yahoo.com");
                              sleep(1);
                              // $popularParts->value("discountautomirrors");
                          } else {
                              $actual = "not present";
                              echo "\nE-mail:FAILED \n";
                           //   $this->createScreenshot("Error.png");
                      }

                          $continuepaymentButton = "#ctl00_Body_btnContinueCheckout";
                          $ctpayment = $this->byCssSelector($continuepaymentButton);
                          if ($ctpayment->displayed() == true) {
                             echo "\nContinue to Payment button: Present";
                          } else {
                             echo "\nContinue to Payment button: Missing";
                         }
                         $ctpayment->click();
                            sleep(3);

                        $paypalButton = "#payment_method_paypalLB";
                        $paypalradiobutton = $this->byCssSelector($paypalButton);
                        if ($paypalradiobutton->displayed() == true) {
                           echo "\nPaypal Radio Button: Present";
                        } else {
                           echo "\nPaypal Radio Button: Missing";
                       }
                       $paypalradiobutton->click();
                          sleep(3);

                        $placeorderButton = "#paypalPlaceMyOrder";


                        $placeOrder = $this->byCssSelector($placeorderButton);
                          if ($placeOrder->displayed() == true) {
                             echo "\nPaypal Radio Button: Present \n";
                          } else {
                             echo "\nPaypal Radio Button: Missing \n";
                         }

                         try {
                            $placeOrder->click();
                         } catch(Exception $e) {

                         }
                         sleep(20);

                         // $activeWindow = $this->windowHandle();

                         $this->windowArr = $this->windowHandles();

                             $this->window($this->windowArr[1]);



                             echo $this->title() . "\n\n";


                         // echo "\n\n";
                         //    sleep(5);
                         //
                         //    sleep(3);
                         //    $activeWindow = $this->windowHandle();
                         //    echo "active window count\n"; print_r($activeWindow); echo "\n";
                         //    sleep(3);
                            }



}
