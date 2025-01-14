<?php

namespace SendCloud\SendCloud\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Api\Data\OrderInterface;

class AddSendCloudVariable implements ObserverInterface
{
    /**
     * @var Json
     */
    private $serializer;

    private $order = null;

    public function __construct(Json $serializer)
    {
        $this->serializer = $serializer;
    }

    public function execute(Observer $observer)
    {
        $transportObject = $observer->getEvent()->getData('transportObject');
        if ($transportObject === null) {
            return;
        }
        $this->order = $transportObject->getOrder();

        if ($this->order !== null && $this->order->getSendcloudServicePointId()) {
            $this->getServicePointVariables($transportObject);
        }
    }

    private function getServicePointVariables($transportObject)
    {
        /** @var OrderInterface $order */
        $order = $this->order;

        // Set Sendcloud Service Point variables
        $transportObject['sc_servicepoint_id'] = $order->getSendcloudServicePointId();
        $transportObject['sc_servicepoint_name'] = $order->getSendcloudServicePointName();
        $transportObject['sc_servicepoint_street'] = $order->getSendcloudServicePointStreet();
        $transportObject['sc_servicepoint_house_no'] = $order->getSendcloudServicePointHouseNumber();
        $transportObject['sc_servicepoint_zipcode'] = $order->getSendcloudServicePointZipCode();
        $transportObject['sc_servicepoint_city'] = $order->getSendcloudServicePointCity();
        $transportObject['sc_servicepoint_post_no'] = $order->getSendcloudServicePointPostnumber();
    }
}
