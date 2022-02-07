<?php

namespace Interiordefine\Magento\Api;

use Exception;
use Illuminate\Http\Client\Response;

class Customers extends AbstractApi
{
    /**
     * The list of customers.
     *
     * @param  int  $pageSize
     * @param  int  $currentPage
     * @param  array  $filters
     * @return array
     */
    public function all($pageSize = 50, $currentPage = 1, $filters = [])
    {
        return $this->get('/customers/search', array_merge($filters, [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]));
    }

    /**
     * Get customer info by Email
     *
     * @param string $email
     * @param int $pageSize
     * @param int $currentPage
     * @return Response
     * @throws Exception
     */
    public function showByEmail(string $email, int $pageSize = 50, int $currentPage = 1): Response
    {
        return $this->get('/customers/search', [
            'searchCriteria[filterGroups][0][filters][0][conditionType]' => 'eq',
            'searchCriteria[filterGroups][0][filters][0][field]' => 'email',
            'searchCriteria[filterGroups][0][filters][0][value]' => $email,
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]);
    }

    /**
     * Create customer account. Perform necessary business operations like sending email.
     *
     * @param array $body
     * @return Response
     * @throws Exception
     */
    public function create(array $body): Response
    {
        return $this->post('/customers', $body);
    }

    /**
     * Send an email to the customer with a password reset link.
     *
     * @param string $email
     * @param string $template
     * @param int $websiteId
     * @return Response
     * @throws Exception
     */
    public function password(string $email, string $template, int $websiteId): Response
    {
        return $this->put('/customers/password', [
            'email'     => $email,
            'template'  => $template,
            'websiteId' => $websiteId,
        ]);
    }

    /**
     * Reset customer password.
     *
     * @param string $email
     * @param string $resetToken
     * @param string $newPassword
     * @return Response
     * @throws Exception
     */
    public function resetPassword(string $email, string $resetToken, string $newPassword): Response
    {
        return $this->post('/customers/resetPassword', [
            'email'       => $email,
            'resetToken'  => $resetToken,
            'newPassword' => $newPassword,
        ]);
    }

    /**
     * Get the customer by Customer ID.
     *
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function show(int $id): Response
    {
        return $this->get('/customers/'.$id);
    }

    /**
     * Update the Customer by Customer ID.
     * Or create a new customer with Email, First and Last Name
     *
     * https://magento.redoc.ly/2.4.3-admin/tag/customerscustomerId#operation/customerCustomerRepositoryV1SavePut
     *
     * @param int $id
     * @param string $params
     * @return Response
     * @throws Exception
     */
    public function edit(int $id, string $params): Response
    {
        $data = [
            'customer' => $params,
        ];

        return $this->put('/customers/'.$id, $data);
    }
}
