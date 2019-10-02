<?php
/**
 * Wdarking Hooks Hook Model
 *
 * @category   Wdarking
 * @package    Wdarking_Hooks
 * @author     Wdarking <wdarking@gmail.com>
 */
class Wdarking_Hooks_Model_Hook extends Mage_Core_Model_Abstract
{
    /**
     * Initialize Resource
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('hooks/hook');
    }

    /**
     * Get hook url by concatenating url and token.
     *
     * @return string
     */
    public function getHookUrl()
    {
        return $this->getUrl().'?api_token='.$this->getToken();
    }
}
