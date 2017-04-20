<?php

class ConverterCurrency {

 public function currencyConverter($from, $to, $amount)
	
		{
		
		   $content = file_get_contents('https://www.google.com/finance/converter?a='.$amount.'&from='.$from.'&to='.$to);
		
		
		
		   $doc = new DOMDocument;
		
		   @$doc->loadHTML($content);
		
		   $xpath = new DOMXpath($doc);
		
		
		
		   $result = $xpath->query('//*[@id="currency_converter_result"]/span')->item(0)->nodeValue;
		
		
		
		   return number_format(str_replace(' '.$to, '', $result), 2, ',', '');
		
		}
	
		public function GetParite($from, $to, $amount)
		
		{
		   $content = file_get_contents('https://www.google.com/finance/converter?a='.$amount.'&from='.$from.'&to='.$to);
		
		
		
		   $doc = new DOMDocument;
		
		   @$doc->loadHTML($content);
		
		   $xpath = new DOMXpath($doc);
		
		
		   $result = $xpath->query('//*[@id="currency_converter_result"]/span')->item(0)->nodeValue;
		
		   $taux = $result / $amount;
		
		   return $taux;
		
		}
}
