<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 10/17/2018
 * Time: 3:19 AM
 */

namespace Isaacghorbani\Smstool;


use Carbon\Carbon;
use Isaacghorbani\Smstool\Models\Sms;

trait HasSmstool
{


    public function sms()
    {
        return Sms::where('to',$this->phone_number);
    }

    public function verify_sms()
    {
        return Sms::where([['to',$this->phone_number],['type','2']]);
    }
    public function reset_password_sms()
    {
        return Sms::where([['to',$this->phone_number],['type','3']]);
    }


    public  function sendPhoneVerificationNotification()
    {

        $code=config('smstool.config.registered_msg');

        //limit send sms 5 in 10 min
        if($pcode=$this->verify_sms()->where('updated_at','>=',Carbon::now()->subMinutes(10)->toDateTimeString())->count()>5)
            return false;

        //check if early send sms (3 min before it ) resend it else create new code and send it.
        if($pcode=$this->verify_sms()->where('updated_at','>=',Carbon::now()->subMinutes(3)->toDateTimeString())->first())
        {

            $code=$pcode->body;
            $pcode->updated_at=Carbon::now()->toDateTimeString();
            $pcode->save;

        }else
        {

            $code=str_replace('NUM',(string)Smstool::generate_code(),$code);

        }
        //send sms
        return Smstool::send_sms($code,$this->phone_number,config('smstool.config.numbers.main'),2);
    }

    public  function sendRestPasswordCode()
    {

        $code=config('smstool.config.reset_password_request');

        //limit send sms 5 in 10 min
        if($pcode=$this->reset_password_sms()->where('updated_at','>=',Carbon::now()->subMinutes(10)->toDateTimeString())->count()>5)
            return false;

        //check if early send sms (3 min before it ) resend it else create new code and send it.
        if($pcode=$this->reset_password_sms()->where('updated_at','>=',Carbon::now()->subMinutes(3)->toDateTimeString())->first())
        {

            $code=$pcode->body;
            $pcode->updated_at=Carbon::now()->toDateTimeString();
            $pcode->save;

        }else
        {

            $code=str_replace('NUM',(string)Smstool::generate_code(),$code);

        }
        //send sms
        return Smstool::send_sms($code,$this->phone_number,config('smstool.config.numbers.main'),3);
    }

    public function cheek_verify_code($code)
    {
        //query to db for check verify code if find any code match with $code and sended before+
        // 5 min mark Phone verify filed and return true
        if($this->verify_sms()->where([['body',$code],
            ['updated_at','>=',Carbon::now()->subMinutes(5)->toDateTimeString()]])->first())
            $this->markPhoneAsVerified();
            return true;
        return false;
    }

    public function cheek_reset_password_code($code)
    {
        //query to db for check verify code if find any code match with $code and sended before+
        // 5 min mark Phone verify filed and return true
        if($this->reset_password_sms()->where([['body','LIKE','%'.$code.'%'],
            ['updated_at','>=',Carbon::now()->subMinutes(5)->toDateTimeString()]])->first())
        return true;
        return false;
    }

    // if phone_verified_at user.table field this phone number early verified.
    public function hasVerifiedPhone()
    {
        return ! is_null($this->phone_verified_at);
    }

    //mark phone_verified_at as fresh time stamp
    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

}