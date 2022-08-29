<?php

namespace Grayloon\Magento\Api;

use Exception;
use Illuminate\Http\Client\Response;

class Countries extends AbstractApi
{
    /**
     * Get attribute metadata for a customer address.
     *
     * @return Response
     * @throws Exception
     */
    public function list(): Response
    {
        return $this->get('/directory/countries');
    }
}
