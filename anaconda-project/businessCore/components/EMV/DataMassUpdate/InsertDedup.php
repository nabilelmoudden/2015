<?php

/**
 * Dedup
 *
 * @package EMV\DataMassUpdate
 */
class EMV_DataMassUpdate_InsertDedup {

    /**
     * Criteria
     *
     * @var string
     */
    public $criteria;

    /**
     * Order
     *
     * @var string
     */
    public $order;

    /**
     * Skip unsubscribed and HBQ
     *
     * @var boolean
     */
    public $skipUnsubAndHBQ;

    /**
     * Constructor
     *
     * @param string $criteria
     * @param string $order
     * @param boolean $skipUnsubAndHBQ
     */
    function __construct($criteria = 'EMAIL', $order = EMV::ORDER_FIRST, $skipUnsubAndHBQ = true) {
        $this->criteria = $criteria;
        $this->order = $order;
        $this->skipUnsubAndHBQ = $skipUnsubAndHBQ;
    }

    /**
     * Creates object from array
     *
     * @param array $data
     * @return null|EMV_DataMassUpdate_InsertDedup
     */
    public static function createFromArray($data) {
        if (!is_array($data) || empty($data)) {
            return null;
        }

        $class = get_class();
        $vars = array_keys(get_class_vars($class));

        $dedup = new EMV_DataMassUpdate_InsertDedup();
        foreach ($vars as $var) {
            if (isset($data[$var])) {
                $dedup->$var = $data[$var];
            }
        }
        return $dedup;
    }

}
