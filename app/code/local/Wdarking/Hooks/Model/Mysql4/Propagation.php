<?php
/**
 * Wdarking Hooks Model Propagation Resource
 *
 * @category   Wdarking
 * @package    Wdarking_Hooks
 * @author     Wdarking <wdarking@gmail.com>
 */
class Wdarking_Hooks_Model_Mysql4_Propagation extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Initialize Resource
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('hooks/propagation', 'id');
    }
}
