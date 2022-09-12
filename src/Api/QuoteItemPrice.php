<?php

namespace Grayloon\Magento\Api;

use Exception;
use Illuminate\Http\Client\Response;

class QuoteItemPrice extends AbstractApi
{
    /**
     * Set cart item price.
     *
     * @param $quoteId
     * @param $itemId
     * @param $price
     * @return Response
     * @throws Exception
     */
    public function setItemPrice($quoteId, $itemId, $price): Response
    {
        return $this->get('/guest-carts/price', [
            "cartId" => $quoteId,
            "itemId" => (string)$itemId,
            "price" => $price
        ]);
    }
}
