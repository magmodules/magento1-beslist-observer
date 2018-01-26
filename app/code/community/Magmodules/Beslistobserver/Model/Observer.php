<?php
/**
 * Magmodules.eu - http://www.magmodules.eu.
 *
 * NOTICE OF LICENSE
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://www.magmodules.eu/MM-LICENSE.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magmodules.eu so we can send you a copy immediately.
 *
 * @category      Magmodules
 * @package       Magmodules_Beslistobserver
 * @author        Magmodules <info@magmodules.eu>
 * @copyright     Copyright (c) 2018 (http://www.magmodules.eu)
 * @license       https://www.magmodules.eu/terms.html  Single Service License
 */

class Magmodules_Beslistobserver_Model_Observer
{

    /**
     * Observer on "before_feed_item" event.
     *
     * @param Varien_Event_Observer $observer
     */
    public function beforeFeedItem(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $feedItem = $event->getFeedData();
        $item = $feedItem->getData();

        // Example for Product Id 231
        if (isset($item['ID']) && $item['ID'] == 231) {
            $item['Levertijd'] = 'Edited by Observer';
            $item['Levertijd_BE'] = 'Edited by Observer';

            // Or get attribute value
            try {
                $_resource = Mage::getSingleton('catalog/product')->getResource();
                $sku = $_resource->getAttributeRawValue($item['ID'], 'sku', Mage::app()->getStore());
            } catch (\Exception $e) {
                $sku = null;
            }

            if ($sku) {
                $item['Sku'] = $sku . '-new';
            }
        }

        $feedItem->setData($item);
    }


    /**
     * Observer on "before_item_update" event.
     *
     * @param Varien_Event_Observer $observer
     */
    public function beforeItemUpdate(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $itemData = $event->getItemData();
        $items = $itemData->getData();

        foreach($items as $k => $item) {
            if (isset($item['product_id']) && $item['product_id'] == 231) {
                $items[$k]['stock'] = 100;
                $items[$k]['delivery_time_nl'] = 'Edited by Observer';
                $items[$k]['delivery_time_be'] = 'Edited by Observer';
            }
        }

        $itemData->setData($items);
    }
}