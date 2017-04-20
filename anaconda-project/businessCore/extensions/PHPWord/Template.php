<?php
/**
 * PHPWord
 *
 * Copyright (c) 2011 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 010 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    Beta 0.6.3, 08.07.2011
 */

/**
 * PHPWord_DocumentProperties
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2009 - 2011 PHPWord (http://www.codeplex.com/PHPWord)
 */
class PHPWord_Template {
    /**
     * ZipArchive
     * 
     * @var ZipArchive
     */
    private $_objZip;
    
    /**
     * Temporary Filename
     * 
     * @var string
     */
    private $_tempFileName;
    
    /**
     * Document XML
     * 
     * @var string
     */
    private $_documentXML; 

    
    public $chaines = array();    
    public $debut;    
    public $fin;
    
    public function d_text($chaine, $taille){
    	
    	$n = strlen($chaine); 
    	/*echo $n;
    	echo "*****"; 
    	echo $taille;
    	echo "*****<br><br>";*/
    	
    	//echo("test");    	
    	//echo substr($chaine, $taille);		
    	//echo(substr($chaine, $taille));    	    	
    	if($n > $taille){
    		$i = 0;  
    		//die($chaine - $i);  		    		
    		while($chaine[$taille - $i] != " "){   
    			if(isset($chaine[$taille - $i - 1])) 			
    				$i++;   
    			else 
    				break; 		
    		}     		
    		//echo $i;    		
    		//die($chaine);    		
    		$debut = substr($chaine, 0, $taille-$i);    		
    		$fin = substr($chaine, $taille+1-$i, $n);    		
    		array_push($this->chaines, $debut);    		
    		if(strlen($fin) > $taille)    			
    			$this->d_text($fin, $taille);
    		else 
    			array_push($this->chaines, $fin);
    	}
    	else
    		array_push($this->chaines, $chaine);
    		//array_push($this->chaines, substr($desc1, $u, $u+38));    		    		    		    		$desc1 = $subcs[0]->Product->titleStat;    		$n1 = strlen($desc1);    		$m1 = ceil($n1/38);    		$document->cloneRow('desc1', $m1);    		    		$u = 0;    		for($i=1; $i<=$m1; $i++){    			$document->setValue('desc1#'.$i, substr($desc1, $u, $u+38));    			$u = $u + 38;    		}*/    }
    } 
    /**
     * Create a new Template Object
     * 
     * @param string $strFilename
     */
    public function __construct($strFilename) {
        $path = dirname($strFilename);
        $this->_tempFileName = $path.DIRECTORY_SEPARATOR.time().'.docx';
        
        copy($strFilename, $this->_tempFileName); // Copy the source File to the temp File

        $this->_objZip = new ZipArchive();
        $this->_objZip->open($this->_tempFileName);
        
        $this->_documentXML = $this->_objZip->getFromName('word/document.xml');
    }
    
    /**
     * Set a Template value
     * 
     * @param mixed $search
     * @param mixed $replace
     */
    public function setValue($search, $replace) {
        if(substr($search, 0, 2) !== '${' && substr($search, -1) !== '}') {
            $search = '${'.$search.'}';
        }
        
        /*if(!is_array($replace)) {
            $replace = utf8_encode($replace);
        }*/
        
        if(!is_array($replace)) {
        	$leschars = array( '%0B' => '%0D%0A' );
	        $replace = urlEncode($replace);
	        $replace = strtr($replace, $leschars);
	        $replace = urldecode($replace);
        }
        
        $this->_documentXML = str_replace($search, $replace, $this->_documentXML);
    }            
    
    /**     * Clone a table row     *     * @param mixed $search     * @param mixed $numberOfClones     */    
    public function cloneRow($search, $numberOfClones) {    	
    	if(substr($search, 0, 2) !== '${' && substr($search, -1) !== '}') {    		
    		$search = '${'.$search.'}';    	
    	}        	
    	$tagPos 	 = strpos($this->_documentXML, $search);    	
    	$rowStartPos = strrpos($this->_documentXML, "<w:tr ", ((strlen($this->_documentXML) - $tagPos) * -1));    	
    	$rowEndPos   = strpos($this->_documentXML, "</w:tr>", $tagPos) + 7;        	
    	$result = substr($this->_documentXML, 0, $rowStartPos);    	
    	$xmlRow = substr($this->_documentXML, $rowStartPos, ($rowEndPos - $rowStartPos));    	
    	for ($i = 1; $i <= $numberOfClones; $i++) {    		
    		$result .= preg_replace('/\$\{(.*?)\}/','\${\\1#'.$i.'}', $xmlRow);    	
    	}    	$result .= substr($this->_documentXML, $rowEndPos);        	
    	$this->_documentXML = $result;    
    }     

    
    public function setbloc($search, $replace, $taille){
    	
    	/*$caracteres = array(
    			'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
    			'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', '€' => 'e',
    			'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
    			'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
    			'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
    			'Œ' => 'oe', 'œ' => 'oe',
    			'$' => 's');
    	
    	$replace = strtr($replace, $caracteres);
    	$replace = preg_replace('#[^A-Za-z0-9]+#', '-', $replace);
    	$replace = trim($replace, '-');
    	$replace = strtolower($replace);*/
    	
    	$caracteres = array(
    			'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
    			'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', '€' => 'e',
    			'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
    			'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
    			'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
    			'Œ' => 'oe', 'œ' => 'oe',
    			'$' => 's');
    	
		$leschars = array( '%0B' => '%0D%0A' );
		
		$replace = urlEncode($replace);
		$replace = strtr($replace, $leschars);
		$replace = urldecode($replace);

    	$replace = htmlspecialchars($replace);
    	//$replace = htmlspecialchars(urldecode(urlEncode($replace)));
    	$this->chaines = array();
    	$this->d_text($replace, $taille);
    	$n = sizeof($this->chaines);
    	$this->cloneRow($search, $n);
    	for($i = 1; $i <= $n; $i++){
    		$this->setValue($search.'#'.$i, $this->chaines[$i-1]);
    	}
    }
    
    /**
     * Save Template
     * 
     * @param string $strFilename
     */
    public function save($strFilename) {
        if(file_exists($strFilename)) {
            unlink($strFilename);
        }
        
        $this->_objZip->addFromString('word/document.xml', $this->_documentXML);
        
        // Close zip file
        if($this->_objZip->close() === false) {
            throw new Exception('Could not close zip file.');
        }
        
        rename($this->_tempFileName, $strFilename);
    }
}
?>