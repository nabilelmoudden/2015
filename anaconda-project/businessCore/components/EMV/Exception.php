<?php

/**
 * Exception
 *
 * @package EMV
 */
class EMV_Exception extends Exception {

    /**
     * Creates an exception from a SoapFault object
     *
     * @param SoapFault $soapFault
     * @return EMV_Exception
     */
    public static function createFromSoapFault(SoapFault $soapFault) {
        if (isset($soapFault->detail->ExportServiceException)) {
            $detail = $soapFault->detail->ExportServiceException;
            $message = $detail->status . ': ' . $detail->description;
            return new EMV_Exception($message);
        }

        if (isset($soapFault->detail->BatchMemberServiceException)) {
            $detail = $soapFault->detail->BatchMemberServiceException;
            $fields = isset($detail->fields) ? ' [fields: ' . $detail->fields . ']' : '';
            $message = $detail->status . $fields . ': ' . $detail->description;
            return new EMV_Exception($message);
        }

        return new EMV_Exception($soapFault->getMessage());
    }

    /**
     * Adds class/method info to the exception message
     *
     * @return string
     */
    public function getCustomMessage() {
        $trace = $this->getTrace();
        $message = 'Exception in ';
        if (!empty($trace[0]['class'])) {
            if ($trace[0]['class'] != get_class()) {
                $idx = 0;
            } else if (isset($trace[1]['class'])) {
                $idx = 1;
            }

            if (isset($idx)) {
                $message .= $trace[$idx]['class'] . '->';
            }
        }
        $message .= $trace[$idx]['function'] . '(): ' . $this->getMessage();
        return $message;
    }

}
