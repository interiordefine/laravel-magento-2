<?php

namespace Grayloon\Magento\Api;

use Exception;
use Illuminate\Http\Client\Response;

class Warranty extends AbstractApi
{

    /**
     * Get quote item available warranties.
     *
     * @param $quoteId
     * @param $itemId
     * @return Response
     * @throws Exception
     */
    public function getWarranties($quoteId, $itemId): Response
    {
        return $this->get('/point-of-sales/get-warranties-list', ['quoteId' => $quoteId, 'itemId' => $itemId]);
    }

    /**
     * Get quote item available warranties.
     *
     * @param $quoteId
     * @param $itemId
     * @param $warrantyHash
     * @return Response
     * @throws Exception
     */
    public function setItemWarranty($quoteId, $itemId, $warrantyHash): Response
    {
        return $this->post('/point-of-sales/set-item-warranty', ['quoteId' => $quoteId, 'itemId' => $itemId, 'warrantyHash' => $warrantyHash]);
    }

}
