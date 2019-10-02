<?php
/**
 * Wdarking Hooks Propagation Model
 *
 * @category   Wdarking
 * @package    Wdarking_Hooks
 * @author     Wdarking <wdarking@gmail.com>
 */
class Wdarking_Hooks_Model_Propagation extends Mage_Core_Model_Abstract
{
    /**
     * Initialize Resource
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('hooks/propagation');
    }
}
