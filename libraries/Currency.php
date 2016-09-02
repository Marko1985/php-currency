<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Currency{

    protected $CI;
    protected $base_webservice = 'http://api.fixer.io/latest?';

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->library('session');
    }

    public function updateRates(){
    	$query = $this->getQuery();
    	$rates = json_decode(file_get_contents($this->base_webservice.$query));
    	$codeigniter = $this->CI;
    	foreach($rates->rates as $key=>$value){
    		$update = array(
    			'value' => $value
    		);
    		$codeigniter->db->where('international', $key);
    		$codeigniter->db->update('currency', $update);
    	}
    	echo 'Todas las monedas están actualizadas.';
    }

    private function getQuery(){
    	$codeigniter = $this->CI;
    	$available_coins = $codeigniter->db->get('currency')->result();
    	$symbols = '&symbols=';
    	foreach($available_coins as $coin){
    		if((boolean)$coin->master){
    			$base = 'base='.$coin->international;
    		}
    		else{
    			$symbols .= $coin->international.',';	
    		}    		
    	}
    	$symbols = substr($symbols, 0, -1);
    	$query = $base.$symbols;
    	return $query;
    }

    public function getMaster(){
    	$codeigniter = $this->CI;
    	$codeigniter->db->where('master', 1);
    	$master = $codeigniter->db->get('currency')->row();
    	return $master;
    }

    public function getCoins(){
        $codeigniter = $this->CI;
        $coins = $codeigniter->db->get('currency')->result();
        return $coins;
    }

    public function getCoinById($id, $international = FALSE){
        $codeigniter = $this->CI;
        $codeigniter->db->where('id', $id);
        if($international){
            $coin = $codeigniter->db->get('currency')->row();    
            return $coin->international;
        }
        return $codeigniter->db->get('currency')->row();
    }

     public function getCoinByISO($iso_code){
        $codeigniter = $this->CI;
        $codeigniter->db->where('international', $iso_code);
        return $codeigniter->db->get('currency')->row();
    }

    /**
     * [getActiveCurrency description]
     * @return [string] [shows ISO Code current currency on system or user config]
     */
    
    public function getActiveCurrency($object = FALSE){
        $codeigniter = $this->CI;
        if($codeigniter->session->userdata('currency')){
            if($object){
                return $this->getCoinByISO($codeigniter->session->userdata('currency'));
            }
            return $codeigniter->session->userdata('currency');
        }
        else{
            if($object){
                 return $this->getCoinByISO(SYSTEM_CURRENCY);
            }
            return SYSTEM_CURRENCY;    
        }        
    }

    /**
     * [switchCurrency description]
     * @param  [string] $currency_international_iso [uppercase international iso convention for currency example:EUR]
     * @return [boolean]                             [true or false]
     */
    
    public function switchCurrency($currency_international_iso, $callback_url = FALSE){
        $this->CI->session->set_userdata('currency', $currency_international_iso);
        if($callback_url){
            redirect($callback_url, 'refresh');    
        }
    }

    public function convert($value, $currency, $symbol = FALSE){
    	$codeigniter = $this->CI;
    	$codeigniter->db->where('international', $currency);
    	$currency = $codeigniter->db->get('currency')->row();
    	$output = '';
    	if($symbol && $currency->position){
    		$output .= $currency->currency_symbol;
    	}
    	$output .= round($value * $currency->value, 2);
    	if($symbol && !$currency->position){
    		$output .= $currency->currency_symbol;
    	}
    	return $output;
    }

    public function reverseConvert($value, $fromCurrency, $symbol = FALSE){
        $codeigniter = $this->CI;
        $codeigniter->db->where('international', $fromCurrency);
        $currency = $codeigniter->db->get('currency')->row();
        $masterCurrency = $this->getMaster();
        $output = '';
        if($symbol && $currency->position){
            $output .= $currency->currency_symbol;
        }
        $output .= number_format((float)round($value / $currency->value,  2, PHP_ROUND_HALF_UP), 2, '.', '');
        if($symbol && !$currency->position){
            $output .= $masterCurrency->currency_symbol;
        }
        return $output;
    }

}