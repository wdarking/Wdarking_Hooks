<?php
/**
 * Wdarking Hooks Model Observer
 *
 * @category   Wdarking
 * @package    Wdarking_Hooks
 * @author     Wdarking <wdarking@gmail.com>
 */
class Wdarking_Hooks_Model_Observer
{
    /**
     * Observe and handle customer_save_before event
     *
     * @param  Varien_Event_Observer $observer
     * @return void
     */
    public function customerSaveBefore(Varien_Event_Observer $observer)
    {
        $customer = $observer->getCustomer();

        if ($customer->isObjectNew()) {

            $data = [
                'name' => $customer->getFirstname() . " " . $customer->getLastname(),
                'document' => $customer->getTaxvat(),
                'email' => $customer->getEmail(),
                'password' => $customer->getPasswordHash(),
                'attributes' => [
                    'name_prefix' => $customer->getPrefix(),
                ],
                'additional_info' => [
                    'source' => 'magento'
                ]
            ];

            if ($billing = $customer->getPrimaryBillingAddress()) {
                $data['phone'] = $billing->getTelephone();
            }

            $this->createPropagation('new_account', $data);
        }
    }

    /**
     * Create a propagation record to be processed later.
     *
     * @param  string $event
     * @param  array $data
     * @return void
     */
    public function createPropagation($event, $data)
    {
        $propagation = Mage::getModel('hooks/propagation');
        $propagation->event = $event;
        $propagation->data = json_encode($data);
        $propagation->save();
    }
}
