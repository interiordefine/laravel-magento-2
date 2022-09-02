<?php

namespace Grayloon\Magento\Api;

use Exception;
use Illuminate\Http\Client\Response;

class CmsBlock extends AbstractApi
{
    const API_PATH = '/cmsBlock';

    /**
     * Get one or more CMS Blocks specified by $identifier
     *
     * @param $identifier mixed string or array of strings
     * @return Response
     * @throws Exception
     */
    public function getByIdentifier($identifier): Response
    {
        if (is_array($identifier)) {
            $identifier = implode(',', $identifier);
        }
        $query = [
            'searchCriteria' => [
                'filterGroups' => [
                    [
                        'filters' => [
                            [
                                'field' => 'identifier',
                                'condition_type' => 'in',
                                'value' => $identifier,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        return $this->get(self::API_PATH . '/search', $query);
    }
}
