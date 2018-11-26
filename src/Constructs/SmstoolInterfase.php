<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 10/17/2018
 * Time: 1:51 AM
 */

namespace Isaac\Smstool\Constructs;


interface SmstoolInterface
{
    public Const SMS_HOST="37.130.202.188/services.jspd";

    // turn off the WSDL cache






    public static function send_sms($body,$to,$form=1);

    public static  function generate_code();





}