<?php

/**
 * Emailvision SOAP API
 *
 * @package EMV
 * @author vlad.tarca
 */
class EMV {

    const CLASS_PREFIX = 'EMV';

    // separator
    const SEPARATOR_COMMA = ',';
    const SEPARATOR_SEMICOLON = ';';
    const SEPARATOR_PIPE = '|';
    const SEPARATOR_TAB = 'tab';

    // fileFormat
    const FILE_FORMAT_PIPE = 'PIPE';
    const FILE_FORMAT_TAB = 'TAB';
    const FILE_FORMAT_CSV = 'CSV';
    const FILE_FORMAT_SEMICOLON = 'SEMICOLON';
    const FILE_FORMAT_XML = 'XML';

    // operationType
    const OPT_ACTIVE_MEMBERS = 'ACTIVE_MEMBERS';
    const OPT_QUARANTINED_OR_UNJOIN_MEMBERS = 'QUARANTINED_OR_UNJOIN_MEMBERS';
    const OPT_QUARANTINED_MEMBERS = 'QUARANTINED_MEMBERS';
    const OPT_UNJOIN_MEMBERS = 'UNJOIN_MEMBERS';
    const OPT_ALL_MEMBERS = 'ALL_MEMBERS';

    // download status
    const EXPORT_VALIDATED = 'VALIDATED';
    const EXPORT_RUNNING = 'RUNNING';
    const EXPORT_SUCCESS = 'SUCCESS';
    const EXPORT_ERROR = 'ERROR';
    const EXPORT_DELETED = 'DELETED';
    const EXPORT_UNKNOWN = 'UNKNOWN STATUS';

    // import status
    const IMPORT_STORAGE = 'STORAGE';
    const IMPORT_VALIDATED = 'VALIDATED';
    const IMPORT_QUEUED = 'QUEUED';
    const IMPORT_IMPORTING = 'IMPORTING';
    const IMPORT_ERROR = 'ERROR';
    const IMPORT_FAILURE = 'FAILURE';
    const IMPORT_DONE = 'DONE';
    const IMPORT_DONE_WITH_ERRORS = 'DONE WITH ERROR(S)';
    const IMPORT_UNKNOWN = 'UNKNOWN STATUS';

    // file status
    const FILE_STATUS_OK = 'OK';
    const FILE_STATUS_NO_DATA = 'NO_DATA';
    const FILE_STATUS_NOT_YET_READY = 'NOT_YET_READY';
    const FILE_STATUS_ERROR = 'ERROR';
    const FILE_STATUS_UKNOWN = 'FILE_STATUS_UNKNOWN';

    // order
    const ORDER_FIRST = 'first';
    const ORDER_LAST = 'last';

    /**
     * wsdl['BatchMember'] URL BatchMember WSDL
     * wsdl['Export'] URL Export WSDL
     *
     * @var array
     */
    private $wsdl;

    /**
     * API Login
     * @var string
     */
    private $login;

    /**
     * API Password
     * @var string
     */
    private $pwd;

    /**
     * API Key
     * @var string
     */
    private $key;

    /**
     * SoapClient Object
     * @var SoapClient
     */
    private $Soap;

    /**
     * API Connection Token
     * @var string
     */
    private $token;

    /**
     * Emailvision API constructor
     *
     * @param array $wsdl WSDL URL array
     * @param string $login API Login
     * @param string $pwd   API Password
     * @param string $key   API Key
     */
    public function __construct($wsdl, $login, $pwd, $key) {
        $this->wsdl = $wsdl;
        $this->login = $login;
        $this->pwd = $pwd;
        $this->key = $key;

        self::init();
    }

    /**
     * Initializes the autoloader
     */
    public static function init() {
        // autoloader
        spl_autoload_register(array('EMV', 'autoload'));
    }

    /**
     * Class autoloader
     *
     * @param string $className
     * @return boolean
     */
    public static function autoload($className) {
        $parts = explode('\\', $className);
        $class = end($parts);

        $classInfo = explode('_', $class);
        $count = count($classInfo);
        $cwd = realpath(dirname(__FILE__));

        if (empty($classInfo) || $count < 2) {
            return false;
        }

        if ($classInfo[0] != self::CLASS_PREFIX) {
            return false;
        }

        if ($count == 3) {
            $classFile = $cwd . DIRECTORY_SEPARATOR . $classInfo[1] . DIRECTORY_SEPARATOR . $classInfo[2] . '.php';
        } else {
            $classFile = $cwd . DIRECTORY_SEPARATOR . $classInfo[1] . '.php';
        }

        if (is_file($classFile)) {
            require_once($classFile);
            return true;
        }

        return false;
    }

    /**
     * Open connection
     *
     * @param string $wsdl WSDL URL
     * @param boolean $mtom Use MTOM
     * @throws EMV_Exception
     */
    protected function connect($wsdl, $mtom = false) {
		try {
            if ($mtom) {
                $this->Soap = new EMV_MTOMSoapClient($wsdl);
            } else {
                $this->Soap = new SoapClient($wsdl);
            }

            $res = $this->Soap->openApiConnection(array(
                'login' => $this->login,
                'pwd' => $this->pwd,
                'key' => $this->key));

            $this->token = $res->return;
        } catch (SoapFault $sf) {
            $e = EMV_Exception::createFromSoapFault($sf);
            throw $e;
        }
    }

    /**
     * Close connection
     *
     * @throws EMV_Exception
     */
    protected function close() {
        try {
            $this->Soap->closeApiConnection(array(
                'token' => $this->token
            ));

            $this->token = null;
            $this->Soap = null;
        } catch (SoapFault $sf) {
            $e = EMV_Exception::createFromSoapFault($sf);
            throw $e;
        }
    }

    /**
     * Requests a new export
     *
     * @param EMV_MemberExport_Download $download Download object
     * @return integer Export ID
     * @throws EMV_Exception
     */
    public function createDownload(EMV_MemberExport_Download $download) {
        try {
            $this->connect($this->wsdl['Export']);

            if (!$download instanceof EMV_MemberExport_DownloadByMailingList) {
                $this->close();
                throw new EMV_Exception('Not implemented');
            }

            $download->token = $this->token;

            $res = $this->Soap->createDownloadByMailinglist($download);
            $this->close();
            return $res->return;
        } catch (SoapFault $sf) {
            $e = EMV_Exception::createFromSoapFault($sf);
            throw $e;
        }
    }

    /**
     * Returns an array containing the status for the last 20 exports
     *
     * @return array
     * @throws EMV_Exception
     */
    public function getLastDownloads() {
        try {
            $this->connect($this->wsdl['Export']);
            $res = $this->Soap->getLastDownloads(array('token' => $this->token));
            $this->close();
            return $res->return;
        } catch (SoapFault $sf) {
            $e = EMV_Exception::createFromSoapFault($sf);
            throw $e;
        }
    }

    /**
     * Gets the status for a specified export
     *
     * @param int $id EMV export ID
     * @return string EMV export status
     * @throws EMV_Exception
     */
    public function getDownloadStatus($id) {
        try {
            $this->connect($this->wsdl['Export']);
            $res = $this->Soap->getDownloadStatus(array('token' => $this->token, 'id' => $id));
            $this->close();
            return $res->return;
        } catch (SoapFault $sf) {
            $e = EMV_Exception::createFromSoapFault($sf);
            throw $e;
        }
    }

    /**
     * Retrieves the content of an export file
     *
     * @param int $id EMV export ID
     * @param string $file If set will write the result to the specified file
     * @return true | string | array
     * @throws EMV_Exception
     */
    public function getDownloadFile($id, $file = null) {
        try {
            $this->connect($this->wsdl['Export']);
            $res = $this->Soap->getDownloadFile(array('token' => $this->token, 'id' => $id));
            $this->close();

            switch ($res->return->fileStatus) {
                case self::FILE_STATUS_OK :
                    if (empty($file)) {
                        return $res->return->fileContent;
                    } else {
                        if (!$fp = fopen($file, 'w')) {
                            throw new EMV_Exception('Could not write to ' . $file);
                        }
                        fwrite($fp, $res->return->fileContent);
                        fclose($fp);

                        return true;
                    }
                    break;
                case self::FILE_STATUS_NO_DATA : return array('error' => self::FILE_STATUS_NO_DATA);
                case self::FILE_STATUS_NOT_YET_READY : return array('error' => self::FILE_STATUS_NOT_YET_READY);
                case self::FILE_STATUS_ERROR : return array('error' => self::FILE_STATUS_ERROR);
                default: return array('error' => self::FILE_STATUS_UKNOWN);
            }
        } catch (SoapFault $sf) {
            $e = EMV_Exception::createFromSoapFault($sf);
            throw $e;
        }
    }

    /**
     * Requests an import.
     *
     * Based on the type of the $upload object it performs a FileInsert
     * or a FileMerge.
     *
     * @param EMV_DataMassUpdate_Upload $upload Upload object
     * @throws EMV_Exception
     */
    public function createUpload(EMV_DataMassUpdate_Upload $upload) {
        try {
            $this->connect($this->wsdl['BatchMember'], true);

            $upload->token = $this->token;

            if ($upload instanceof EMV_DataMassUpdate_UploadFileInsert) {
                // uploadFileInsert
                $res = $this->Soap->uploadFileInsert($upload);
            } else if ($upload instanceof EMV_DataMassUpdate_UploadFileMerge) {
                // uploadFileMerge
                $res = $this->Soap->uploadFileMerge($upload);
            } else {
                $this->close();
                throw new EMV_Exception('Not implemented');
            }

            $this->close();
            return $res->return;
        } catch (SoapFault $sf) {
            $e = EMV_Exception::createFromSoapFault($sf);
            throw $e;
        }
    }

    /**
     * Returns an array containing the status for the last imports
     *
     * @return array
     * @throws @static.mtd:EMV_Exception.createFromSoapFault
     */
    public function getLastUpload() {
        try {
            $this->connect($this->wsdl['BatchMember'], true);
            $res = $this->Soap->getLastUpload(array('token' => $this->token));
            $this->close();
            return $res->return;
        } catch (SoapFault $sf) {
            $e = EMV_Exception::createFromSoapFault($sf);
            throw $e;
        }
    }

    /**
     * Gets the status for a specified import
     *
     * @param int $id EMV import ID
     * @return string EMV import status
     * @throws EMV_Exception
     */
    public function getUploadStatus($id) {
        try {
            $this->connect($this->wsdl['BatchMember'], true);
            $res = $this->Soap->getUploadStatus(array('token' => $this->token, 'uploadId' => $id));
            $this->close();
            return $res->return;
        } catch (SoapFault $sf) {
            $e = EMV_Exception::createFromSoapFault($sf);
            throw $e;
        }
    }

    /**
     * Returns the log file associated with an upload
     *
     * @param int $id EMV import ID
     * @return string
     * @throws EMV_Exception
     */
    public function getLogFile($id) {
        try {
            $this->connect($this->wsdl['BatchMember'], true);
            $res = $this->Soap->getLogFile(array('token' => $this->token, 'uploadId' => $id));
            $this->close();
            return $res->return;
        } catch (SoapFault $sf) {
            $e = EMV_Exception::createFromSoapFault($sf);
            throw $e;
        }
    }

    /**
     * Returns the lines of an uploaded file that could not be uploaded due to errors
     *
     * @param int $id EMV import ID
     * @return array
     * @throws EMV_Exception
     */
    public function getBadFile($id) {
        try {
            $this->connect($this->wsdl['BatchMember'], true);
            $res = $this->Soap->getBadFile(array('token' => $this->token, 'uploadId' => $id));
            $this->close();
            return $res->return;
        } catch (SoapFault $sf) {
            $e = EMV_Exception::createFromSoapFault($sf);
            throw $e;
        }
    }

    /**
     * Gets the current connection token
     *
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

}
