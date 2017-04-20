<?

\Yii::import( 'ext.CurlHelper' );

class W3CHelper extends CurlHelper
{
    const BASE_URL	= 'http://validator.w3.org/check';
	
	private $url	= false;

    function __construct( $url )
	{
		parent::__construct();

		$this->url	= self::BASE_URL.'?url='.urlencode( $url ).'&output=soap12';
    }

	public function validate()
	{
		if( !($res = $this->sendRequest($this->url)) )
			return false;

		return $this->xml_parsing( $res );
	}

	private function xml_parsing( $string )
	{
		$result_arr = array();
		$xml = new DomDocument();
		@$xml->loadXML($string);
		$xpath = new DOMXpath($xml);
		$xpath->registerNamespace("m", "http://www.w3.org/2005/10/markup-validator");

		$elements = $xpath->query("//m:validity");
		if($elements->item(0)->nodeValue == 'true') {
			$result_arr['status'] = 'valid';
		}else {
			$result_arr['status'] = 'invalid';
		}

		$elements = $xpath->query("//m:errorcount");
		$result_arr['err_num'] = intval($elements->item(0)->nodeValue);

		$result_arr['errors'] = array();
		$result_arr['warnings'] = array();

		if($elements->item(0) && $elements->item(0)->nodeValue > 0) {

		  $node_arr = $xpath->query("//m:errors/m:errorlist/m:error/m:line");
		  $i = 0;
		  foreach ($node_arr as $node) {
			  $result_arr['errors'][$i]['line'] = intval($node->nodeValue);
			  $i++;
		  }

		  $node_arr = $xpath->query("//m:errors/m:errorlist/m:error/m:col");
		  $i = 0;
		  foreach ($node_arr as $node) {
			  $result_arr['errors'][$i]['col'] = intval($node->nodeValue);
			  $i++;
		  }

		  $node_arr = $xpath->query("//m:errors/m:errorlist/m:error/m:message");
		  $i = 0;
		  foreach ($node_arr as $node) {
			  $result_arr['errors'][$i]['message'] = $node->nodeValue;
			  $i++;
		  }
		  $node_arr = $xpath->query("//m:errors/m:errorlist/m:error/m:messageid");
		   $i = 0;
		  foreach ($node_arr as $node) {
			  $result_arr['errors'][$i]['messageid'] = $node->nodeValue;
			  $i++;
		  }
		  $node_arr = $xpath->query("//m:errors/m:errorlist/m:error/m:explanation");
		   $i = 0;
		  foreach ($node_arr as $node) {
			  $result_arr['errors'][$i]['explanation'] = trim($node->nodeValue);
			  $i++;
		  }
		}

		$elements = $xpath->query("//m:warningcount");
		$result_arr['warn_num'] = intval($elements->item(0)->nodeValue);

		if($elements->item(0) && $elements->item(0)->nodeValue > 0)
		{
			$node_arr = $xpath->query("//m:warnings/m:warninglist/m:warning/m:messageid");
			$i = 0;
			foreach ($node_arr as $node) {
				$result_arr['warnings'][$i]['messageid'] = trim($node->nodeValue);
				$i++;
			}
			$node_arr = $xpath->query("//m:warnings/m:warninglist/m:warning/m:message");
			$i = 0;
			foreach ($node_arr as $node) {
				$result_arr['warnings'][$i]['message'] = trim($node->nodeValue);
				$i++;
			}
		}

		return $result_arr;
    }
}
?>