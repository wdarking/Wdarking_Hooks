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

                    if ($propagation->getTries() >= 10) {
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
        if (isset($data['password'])) {
            $data['password'] = Mage::helper('core')->decrypt($data['password']);
        }

        if (isset($data['password_confirmation'])) {
            $data['password_confirmation'] = Mage::helper('core')->decrypt($data['password_confirmation']);
        }

        $client = new Varien_Http_Client($url);
        $client->setHeaders('Content-type', 'application/json');
        $client->setHeaders('Accept', 'application/json');
        $client->setMethod(Varien_Http_Client::POST);
        $client->setRawData(json_encode($data), 'application/json');

        $response = $client->request();

        return $response;
    }
}
