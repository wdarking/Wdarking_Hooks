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

            $phone = null;

            $addresses = [];
            foreach ($customer->getAddressesCollection() as $_address) {

                $address = [
                    'street' => $_address->getStreet(1),
                    'number' => $_address->getStreet(2),
                    'district' => $_address->getStreet(3),
                    'complement' => $_address->getStreet(4),
                    'city' => $_address->getCity(),
                    'postcode' => $_address->getPostcode(),
                    'state' => $_address->getRegionId(),
                    'country' => $_address->getCountryId(),
                    'additional_info' => [
                        'is_default_billing' => $_address->getIsDefaultBilling(),
                        'is_default_shipping' => $_address->getIsDefaultShipping(),
                        'phone' => $_address->getTelephone(),
                    ]
                ];

                if ($_address->getIsDefaultBilling()) {
                    $phone = $_address->getTelephone();
                }

                array_push($addresses, $address);
            }

            $data = [
                'name' => $customer->getName(),
                'document' => $customer->getTaxvat(),
                'phone' => $phone,
                'email' => $customer->getEmail(),
                // 'password' => Mage::helper('core')->encrypt($customer->getPassword()),
                'password' => $customer->getPasswordHash(),
                // 'password_confirmation' => Mage::helper('core')->encrypt($customer->getPassword()),
                'additional_info' => [
                    'source' => 'magento',
                    'magento_id' => $customer->getId(),
                ]
            ];

            if (count($addresses)) {
                $data['addresses'] = $addresses;
            }

            $data = ['customers' => [$data]];

            $this->createPropagation('customer_save', $data);
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
