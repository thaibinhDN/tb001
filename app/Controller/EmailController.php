<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor. 
 */
include 'ChromePhp.php';
class EmailController extends AppController{
    public $uses = array("Company","User");
    public function checkFinancialYear(){
        #check current data, get month
        #extract from db companies having FY of which the month is same or one month after
        # for each company, perform checking whether the difference in days is wihtin 7  starting from now
        #if yes, send email to notify
        #no, don't do anything
        date_default_timezone_set('UTC');
//        $current_month = date('n');
//        $current_day = date('j');
//        $current_year = date('Y');
        
        $current_month = 1;
        $current_day = 4;
        $current_year = 2014;
        //Select email,FinancialYear from companies as Company where extract(month from FinancialYear) 
        //= 12 or extract(month from DATE_SUB(FinancialYear,INTERVAL 1 MONTH)) = 12;
        $companies = $this->Company->query("Select email,FinancialYear from companies as Company where extract(month from FinancialYear) = ".$current_month.
                 " or "."extract(month from DATE_SUB(FinancialYear,INTERVAL 1 MONTH)) = ".$current_month.";");        
//        ChromePhp::log($companies);
        foreach($companies as $company){
            $financialMonth = date('n',strtotime($company['Company']['FinancialYear']));
            $financtialDay = date('j',strtotime($company['Company']['FinancialYear']));
            if($current_month == $financialMonth){
                $this->checkSameMonth($company,$current_day,$financtialDay);
            }else{
                $this->checkDifferentMonth($company,$current_day,$current_month,$current_year,$financtialDay);
            }
            //ChromePhp::log($financialMonth);
        }
    }
    public function checkSameMonth($company,$current_day,$financial_day){
        $difference = $financial_day - $current_day;
        if(($difference <= 7)&&($difference >= 0)){
            //$this->sendEmail($company);
            ChromePhp::log("ALR send email");
        }else{
            return false;
        }
    }
    
    public function checkDifferentMonth($company,$current_day,$current_month,$current_year,$financial_day){
        $currentNoDaysPerMonth = $this->days_in_month($current_month,$current_year);
        $difference = $currentNoDaysPerMonth - $current_day;
        if($difference < 7){
            if($difference + $financial_day <= 7){
                 //$this->sendEmail($company);
                ChromePhp::log("ALR send email");
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
//        <?php
    function days_in_month($month, $year) { 
        if(checkdate($month, 31, $year)) return 31; 
        if(checkdate($month, 30, $year)) return 30; 
        if(checkdate($month, 29, $year)) return 29; 
        if(checkdate($month, 28, $year)) return 28; 
        return 0; // error 
    }
    public function sendEmail($company){
//        $email = 
    }

//    date_default_timezone_set('UTC');
// $time = '16/10/2003';
// $date = str_replace('/', '-', $time);
// //$time2 = strtotime('');
//
//$newformat = date('d/m/Y',strtotime($date));
//
//
//echo $newformat;
//$num1 = days_in_month(2,2014);
//$num2 = days_in_month(2,2014);
//echo " ".$num;

        
    
        
}

