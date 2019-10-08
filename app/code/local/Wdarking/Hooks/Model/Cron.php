<?php
/**
 * Wdarking Hooks Model Cron
 *
 * @category   Wdarking
 * @package    Wdarking_Hooks
 * @author     Wdarking <wdarking@gmail.com>
 */
class Wdarking_Hooks_Model_Cron
{
    public function createCustomerPropagation()
    {
        $customers = Mage::getModel('customer/customer')->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter([
                ['attribute' => 'wdk_hooks_propagation_status', 'null' => true],
                ['attribute' => 'wdk_hooks_propagation_status', 'eq' => ''],
            ], '', 'left');

        $customers->getSelect()->limit(1);

        foreach ($customers as $customer) {

            $phone = null;

            $addresses = [];

            if ($billingAddress = $customer->getPrimaryBillingAddress()) {
                array_push($addresses, [
                    'label' => 'billing',
                    'street' => $billingAddress->getStreet(1),
                    'number' => $billingAddress->getStreet(2),
                    'district' => $billingAddress->getStreet(3),
                    'complement' => $billingAddress->getStreet(4),
                    'city' => $billingAddress->getCity(),
                    'postcode' => $billingAddress->getPostcode(),
                    'state' => $billingAddress->getRegionId(),
                    'country' => $billingAddress->getCountryId(),
                    'additional_info' => [
                        'phone' => $billingAddress->getTelephone(),
                    ]
                ]);

                $phone = $billingAddress->getTelephone();
            }

            if ($shippingAddress = $customer->getPrimaryShippingAddress()) {
                array_push($addresses, [
                    'label' => 'shipping',
                    'street' => $shippingAddress->getStreet(1),
                    'number' => $shippingAddress->getStreet(2),
                    'district' => $shippingAddress->getStreet(3),
                    'complement' => $shippingAddress->getStreet(4),
                    'city' => $shippingAddress->getCity(),
                    'postcode' => $shippingAddress->getPostcode(),
                    'state' => $shippingAddress->getRegionId(),
                    'country' => $shippingAddress->getCountryId(),
                    'additional_info' => [
                        'phone' => $shippingAddress->getTelephone(),
                    ]
                ]);
            }

            $data = [
                'name' => $customer->getName(),
                'document' => $customer->getTaxvat(),
                'phone' => $phone,
                'email' => $customer->getEmail(),
                'password' => $customer->getPasswordHash(),
                'additional_info' => [
                    'source' => 'magento',
                    'magento_id' => $customer->getId(),
                    'magento_created_at' => $customer->getCreatedAt(),
                ],
                'created_at' => $customer->getCreatedAt(),
            ];

            if (count($addresses)) {
                $data['addresses'] = $addresses;
            }

            $data = ['customers' => [$data]];

            $propagation = Mage::getModel('hooks/propagation');
            $propagation->event = 'unpropagated_customer';
            $propagation->data = json_encode($data);
            $propagation->save();

            $customer->setWdkHooksPropagationStatus(time());
            $customer->save();
        }
    }

    /**
     * Propagate all pending propagations to
     * hooks with same event attribute value.
     *
     * @return void
     */
    public function hookPropagator()
    {
        $propagations = Mage::getModel('hooks/propagation')->getCollection()
            ->addFieldToFilter('propagated_at', ['null' => true])
            ->addFieldToFilter('backoff_at', ['null' => true]);

        $propagations->getSelect()->limit(1);

        foreach ($propagations as $propagation) {

            $hooks = Mage::getModel('hooks/hook')->getCollection()
                ->addFieldToFilter('event', ['eq' => $propagation->event])
                ->addFieldToFilter('enabled', ['eq' => 1]);

            foreach ($hooks as $hook) {

                $response = $this->post($hook->getHookUrl(), json_decode($propagation->getData('data'), true));

                $propagation->setResponseStatus($response->getStatus()." - ".$response->getMessage());
                $propagation->setResponseData($response->getBody());
                $propagation->setTries(is_numeric($propagation->getTries()) ? ($propagation->getTries() + 1) : 1);

                if ($response->isError()) {

                    Mage::log('Wdarking_Hooks_Model_Cron::hookPropagator response status: '.$response->getStatus());
                    Mage::log('Wdarking_Hooks_Model_Cron::hookPropagator response message: '.$response->getMessage());
                    Mage::log('Wdarking_Hooks_Model_Cron::hookPropagator response body: '.$response->getBody());

                    if ($propagation->getTries() >= 3) {
                        $propagation->setBackoffAt(time());
                    }
                }

                if ($response->isSuccessful()) {
                    $propagation->setPropagatedAt(time());
                }

                $propagation->save();
            }
        }
    }

    /**
     * Post to the hook url
     *
     * @param  string $url
     * @param  array  $data
     * @return Zend_Http_Response
     */
    public function post($url, array $data)
    {
        $client = new Varien_Http_Client($url);
        $client->setHeaders('Content-type', 'application/json');
        $client->setHeaders('Accept', 'application/json');
        $client->setMethod(Varien_Http_Client::POST);
        $client->setRawData(json_encode($data), 'application/json');

        $response = $client->request();

        return $response;
    }
}
