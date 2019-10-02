<?php
/**
 * Wdarking Hooks Model Hook Resource Collection
 *
 * @category   Wdarking
 * @package    Wdarking_Hooks
 * @author     Wdarking <wdarking@gmail.com>
 */
class Wdarking_Hooks_Model_Mysql4_Hook_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Initialize Resource Collection
     *
     * @return void
     */
	public function _construct()
    {
		$this->_init('hooks/hook');
	}
}
