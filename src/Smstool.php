<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 10/17/2018
 * Time: 2:02 AM
 */

namespace Isaacghorbani\Smstool;


use Isaacghorbani\Smstool\Constructs\SmstoolInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Isaacghorbani\Smstool\Models\Sms;
use phpDocumentor\Reflection\DocBlock\Tags\See;

class Smstool implements  SmstoolInterface
{


    public static function send_sms($body, $to, $from='0098sim',$type=1)
    {
        $client=new Client();
        $response = $client->request('Post', static::SMS_HOST, [
            'form_params' => [
                'uname'=>config('smstool.config.sms_username'),
                'pass'=>config('smstool.config.sms_password'),
                'from'=>$from,
                'message'=>$body,
                'to'=>json_encode([$to]),
                'op'=>'send']
        ]);
        if($response->getStatusCode()==200)
                $sms=new Sms();
                $sms->to=$to;
                $sms->body=$body;
                $sms->type=$type;
                $sms->save();
                return true;
        return false;
    }

    public static function generate_code()
    {

        return rand(1000,9999);
    }


}