<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NBPController extends Controller
{



    public function getCurrencies(){

        $courses =new Client();

        $response = $courses->request('GET','http://api.nbp.pl/api/exchangerates/tables/c/?format=json');
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        $arr = json_decode($body, true);
//        return $body;
//
        return $arr;
    }
    public function getCurrency($code){

        $courses =new Client();
            $link = "http://api.nbp.pl/api/exchangerates/rates/a/$code/?format=json";
        $response = $courses->request('GET',$link);
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $arr = json_decode($body, true);
//        return $body;
//
        return $arr;
    }

    public function  currencyList(){

        $arr =  $this->getCurrencies();

        return view('courses')->with('array',$arr[0]);
    }

    public  function getCalculate(){

        $arr = $this->getCurrencies();
        return view('calculate')->with('rates',$arr[0]['rates']);
    }

    public function  getFormattedRate($value,$rate){
        return  round(floatval($value)/ floatval($rate),0);
    }
    public function  calculate(): View {
        $value = request()->input('value');
        $code = request()->input('currency');

        $codeObject = $this->getCurrency($code);
        $rate = $codeObject["rates"][0];
//        $rates = $rates[0]['rates'];

        $arr = $this->getCurrencies();

            return view('calculate')
                ->with('convert',$this->getFormattedRate($value,$rate['mid']))
                ->with('rates',$arr[0]['rates'])
                ->with('inputval',$value)
                ->with('currentRate',$codeObject);
    }
}
