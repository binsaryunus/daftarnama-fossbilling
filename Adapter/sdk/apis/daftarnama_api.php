<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'requests/last_request.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'requests/register_domain_request.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'requests/transfer_domain_request.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'requests/renew_domain_request.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'requests/update_contact_request.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'responses/get_domain_info_response.php';

/**
 * DaftarNama API
 *
 * @link https://exabytes.co.id Exabytes Network Indonesia
 */
class DaftarnamaApi
{
    const PRODUCTION_API_URL = 'https://api.dnama.id';
    const SANDBOX_API_URL = 'https://api.sandboxv2.daftarnama.id';

    /**
     * @var string
     */
    private $apiUrl, $apiKey;
    /**
     * @var LastRequest|null
     */
    private $lastRequest = null;
    /**
     * @var array|object|null
     */
    private $lastResponse = null;

    /**
     * @param string $apiKey
     * @param string $isTestMode
     */
    public function __construct($apiKey, $isTestMode = false, $apiUrl = null)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $isTestMode ? self::SANDBOX_API_URL : self::PRODUCTION_API_URL;
        if ($apiUrl) {
            $this->apiUrl = $apiUrl;
        }
    }

    /**
     * @param string $apiUrl
     * @return void
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * @return mixed
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @param RegisterDomainRequest $data
     * @return void
     * @throws Exception
     */
    public function registerDomain($data)
    {
        try {
            $checkIsUsernameExist = $this->callApi(
                'GET',
                $this->apiUrl . "/customers/" . $data->getCustomerUsername(),
                null,
                200
            );
            $checkIsUsernameExist = !!$checkIsUsernameExist;
        } catch (Exception $e) {
            $checkIsUsernameExist = false;
        }

        $this->callApi(
            'POST',
            $this->apiUrl . "/domains",
            [
                "domain_name" => $data->getDomainName(),
                "duration" => $data->getDuration(),
                "nameservers" => $data->getNameservers(),
                "with_existing_customer" => $checkIsUsernameExist,
                "username" => $data->getCustomerUsername(),
                "password" => $data->getCustomerPassword(),
                "name" => $data->getFullName(),
                "company_name" => $data->getCompanyName(),
                "email" => $data->getEmail(),
                "address_1" => $data->getAddress1(),
                "address_2" => $data->getAddress2(),
                "address_3" => $data->getAddress3(),
                "city" => $data->getCity(),
                "province" => $data->getProvince(),
                "country" => $data->getCountryCode(),
                "postal_code" => $data->getPostalCode(),
                "phone_number" => $data->getPhoneNumber(),
                "mobile_phone_number" => $data->getMobilePhoneNumber()
            ],
            201
        );
    }

    /**
     * @param TransferDomainRequest $data
     * @return void
     * @throws Exception
     */
    public function transferDomain($data)
    {
        try {
            $checkIsUsernameExist = $this->callApi(
                'GET',
                $this->apiUrl . "/customers/" . $data->getCustomerUsername(),
                null,
                200
            );
            $checkIsUsernameExist = !!$checkIsUsernameExist;
        } catch (Exception $e) {
            $checkIsUsernameExist = false;
        }

        $this->callApi(
            'POST',
            $this->apiUrl . '/domain/transfer',
            [
                "domain_name" => $data->getDomainName(),
                "duration" => $data->getDuration(),
                "epp_code" => $data->getEppCode(),
                "with_existing_customer" => $checkIsUsernameExist,
                "username" => $data->getCustomerUsername(),
                "password" => $data->getCustomerPassword(),
                "name" => $data->getFullName(),
                "company_name" => $data->getCompanyName(),
                "email" => $data->getEmail(),
                "address_1" => $data->getAddress1(),
                "address_2" => $data->getAddress2(),
                "address_3" => $data->getAddress3(),
                "city" => $data->getCity(),
                "province" => $data->getProvince(),
                "country" => $data->getCountryCode(),
                "postal_code" => $data->getPostalCode(),
                "phone_number" => $data->getPhoneNumber(),
                "mobile_phone_number" => $data->getMobilePhoneNumber()
            ],
            201
        );
    }

    public function ping()
    {
        $this->callApi(
            'GET',
            $this->apiUrl . '/ping',
            null,
            200
        );
    }

    /**
     * @param string $domainName
     * @return bool
     * @throws Exception
     */
    public function getDomainAvailability($domainName)
    {
        $response = $this->callApi(
            'GET',
            $this->apiUrl . '/domain-availability',
            [
                'domain_name' => $domainName
            ],
            200
        );
        return $response['data']['available'];
    }

    /**
     * @param string $domainName
     * @param RenewDomainRequest $data
     * @return void
     * @throws Exception
     */
    public function renewDomain($domainName, $data)
    {
        $this->callApi(
            'POST',
            $this->apiUrl . "/domains/$domainName/renew",
            [
                "current_expiry_date" => $data->getCurrentExpiryDate(),
                "duration" => $data->getDuration()
            ],
            200
        );
    }

    /**
     * @param string $domainName
     * @return void
     * @throws Exception
     */
    public function restoreDomain($domainName)
    {
        $this->callApi(
            'POST',
            $this->apiUrl . "/domains/$domainName/restore",
            null,
            200
        );
    }

    /**
     * @param string $domainName
     * @param bool $enableTransferProtection
     * @return void
     * @throws Exception
     */
    public function updateEnableTransferProtection($domainName, $enableTransferProtection)
    {
        $this->callApi(
            'PUT',
            $this->apiUrl . "/domains/$domainName/is_enable_transfer_protection",
            ['is_enable_transfer_protection' => $enableTransferProtection],
            200
        );
    }

    /**
     * @param string $domainName
     * @return GetDomainInfoResponse
     * @throws Exception
     */
    public function getDomainInfo($domainName)
    {
        $response = $this->callApi(
            'GET',
            $this->apiUrl . "/domains/$domainName",
            null,
            200
        );

        $responseData = $response['data'];
        return new GetDomainInfoResponse(
            $responseData['domain_name'],
            $responseData['type'],
            $responseData['epp_code'],
            $responseData['registration_date'],
            $responseData['expiry_date'],
            $responseData['status'],
            $responseData['document_status'],
            $responseData['is_enable_transfer_protection'],
            $responseData['is_auto_provisioning'],
            $responseData['is_suspended'],
            $responseData['nameservers'],
            $responseData['customer_username'],
            $responseData['registrant_contact']['name'],
            $responseData['registrant_contact']['organization_name'],
            $responseData['registrant_contact']['email'],
            $responseData['registrant_contact']['address_1'],
            $responseData['registrant_contact']['address_2'],
            $responseData['registrant_contact']['address_3'],
            $responseData['registrant_contact']['city'],
            $responseData['registrant_contact']['province'],
            $responseData['registrant_contact']['country'],
            $responseData['registrant_contact']['postal_code'],
            $responseData['registrant_contact']['phone_number'],
            $responseData['registrant_contact']['mobile_phone_number'],
            $responseData['admin_contact']['name'],
            $responseData['admin_contact']['organization_name'],
            $responseData['admin_contact']['email'],
            $responseData['admin_contact']['address_1'],
            $responseData['admin_contact']['address_2'],
            $responseData['admin_contact']['address_3'],
            $responseData['admin_contact']['city'],
            $responseData['admin_contact']['province'],
            $responseData['admin_contact']['country'],
            $responseData['admin_contact']['postal_code'],
            $responseData['admin_contact']['phone_number'],
            $responseData['admin_contact']['mobile_phone_number'],
            $responseData['billing_contact']['name'],
            $responseData['billing_contact']['organization_name'],
            $responseData['billing_contact']['email'],
            $responseData['billing_contact']['address_1'],
            $responseData['billing_contact']['address_2'],
            $responseData['billing_contact']['address_3'],
            $responseData['billing_contact']['city'],
            $responseData['billing_contact']['province'],
            $responseData['billing_contact']['country'],
            $responseData['billing_contact']['postal_code'],
            $responseData['billing_contact']['phone_number'],
            $responseData['billing_contact']['mobile_phone_number'],
            $responseData['technical_contact']['name'],
            $responseData['technical_contact']['organization_name'],
            $responseData['technical_contact']['email'],
            $responseData['technical_contact']['address_1'],
            $responseData['technical_contact']['address_2'],
            $responseData['technical_contact']['address_3'],
            $responseData['technical_contact']['city'],
            $responseData['technical_contact']['province'],
            $responseData['technical_contact']['country'],
            $responseData['technical_contact']['postal_code'],
            $responseData['technical_contact']['phone_number'],
            $responseData['technical_contact']['mobile_phone_number']
        );
    }

    /**
     * @param string $domainName
     * @param array $nameservers
     * @return void
     * @throws Exception
     */
    public function updateNameservers($domainName, $nameservers)
    {
        $this->callApi(
            'PUT',
            $this->apiUrl . "/domains/$domainName/nameservers",
            ['nameservers' => $nameservers],
            200
        );
    }

    /**
     * @param string $domainName
     * @param UpdateContactRequest $data
     * @return void
     * @throws Exception
     */
    public function updateRegistrantContact($domainName, $data)
    {
        $this->callApi(
            'PUT',
            $this->apiUrl . "/domains/$domainName/registrant_contact",
            [
                "name" => $data->getFullName(),
                "organization_name" => $data->getCompanyName(),
                "email" => $data->getEmail(),
                "address_1" => $data->getAddress1(),
                "address_2" => $data->getAddress2(),
                "address_3" => $data->getAddress3(),
                "city" => $data->getCity(),
                "province" => $data->getProvince(),
                "country" => $data->getCountryCode(),
                "postal_code" => $data->getPostalCode(),
                "phone_number" => $data->getPhoneNumber(),
                "mobile_phone_number" => $data->getMobilePhoneNumber()
            ],
            200
        );
    }

    /**
     * @param string $domainName
     * @param UpdateContactRequest $data
     * @return void
     * @throws Exception
     */
    public function updateAdminContact($domainName, $data)
    {
        $this->callApi(
            'PUT',
            $this->apiUrl . "/domains/$domainName/admin_contact",
            [
                "name" => $data->getFullName(),
                "organization_name" => $data->getCompanyName(),
                "email" => $data->getEmail(),
                "address_1" => $data->getAddress1(),
                "address_2" => $data->getAddress2(),
                "address_3" => $data->getAddress3(),
                "city" => $data->getCity(),
                "province" => $data->getProvince(),
                "country" => $data->getCountryCode(),
                "postal_code" => $data->getPostalCode(),
                "phone_number" => $data->getPhoneNumber(),
                "mobile_phone_number" => $data->getMobilePhoneNumber()
            ],
            200
        );
    }

    /**
     * @param string $domainName
     * @param UpdateContactRequest $data
     * @return void
     * @throws Exception
     */
    public function updateBillingContact($domainName, $data)
    {
        $this->callApi(
            'PUT',
            $this->apiUrl . "/domains/$domainName/billing_contact",
            [
                "name" => $data->getFullName(),
                "organization_name" => $data->getCompanyName(),
                "email" => $data->getEmail(),
                "address_1" => $data->getAddress1(),
                "address_2" => $data->getAddress2(),
                "address_3" => $data->getAddress3(),
                "city" => $data->getCity(),
                "province" => $data->getProvince(),
                "country" => $data->getCountryCode(),
                "postal_code" => $data->getPostalCode(),
                "phone_number" => $data->getPhoneNumber(),
                "mobile_phone_number" => $data->getMobilePhoneNumber()
            ],
            200
        );
    }

    /**
     * @param string $domainName
     * @param UpdateContactRequest $data
     * @return void
     * @throws Exception
     */
    public function updateTechContact($domainName, $data)
    {
        $this->callApi(
            'PUT',
            $this->apiUrl . "/domains/$domainName/technical_contact",
            [
                "name" => $data->getFullName(),
                "organization_name" => $data->getCompanyName(),
                "email" => $data->getEmail(),
                "address_1" => $data->getAddress1(),
                "address_2" => $data->getAddress2(),
                "address_3" => $data->getAddress3(),
                "city" => $data->getCity(),
                "province" => $data->getProvince(),
                "country" => $data->getCountryCode(),
                "postal_code" => $data->getPostalCode(),
                "phone_number" => $data->getPhoneNumber(),
                "mobile_phone_number" => $data->getMobilePhoneNumber()
            ],
            200
        );
    }

    /**
     * @param string $domainName
     * @return string
     * @throws Exception
     */
    public function getUploadDocumentUrl($domainName)
    {
        $response = $this->callApi(
            'GET',
            $this->apiUrl . "/domains/$domainName/upload_document_url",
            null,
            200
        );

        return $response['data']['upload_document_url'];
    }


    /**
     * @return array<string>
     * @throws Exception
     */
    public function getSellingTlds()
    {
        $response = $this->callApi(
            'GET',
            $this->apiUrl . '/customer-tld-pricings',
            null,
            200
        );

        $tlds = [];
        foreach ($response['data'] as $sellingTld) {
            if (!in_array($sellingTld['tld'], $tlds)) {
                $tlds[] = $sellingTld['tld'];
            }
        }

        return $tlds;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getCustomerTldPricings()
    {
        $response = $this->callApi(
            'GET',
            $this->apiUrl . '/customer-tld-pricings',
            null,
            200
        );

        $nonPremiumTlds = [];
        foreach ($response['data'] as $sellingTld) {
            if (!$sellingTld['is_premium']) {
                $nonPremiumTlds[] = $sellingTld;
            }
        }

        $result = [];
        foreach ($nonPremiumTlds as $sellingTld) {
            $pricings = [];
            foreach ($sellingTld['pricings'] as $pricing) {
                $pricings[$pricing['duration']] = [
                    'register' => $pricing['register_price'],
                    'transfer' => $pricing['transfer_price'],
                    'renew' => $pricing['renewal_price'],
                ];
            }
            $result[$sellingTld['tld']] = [
                $sellingTld['currency'] => $pricings
            ];
        }
        return $result;
    }

    /**
     * @param string $method The HTTP method to use, must be one of: "GET", "POST", "PUT", "DELETE"
     * @param string $url
     * @param array|object|null $body
     * @param integer $expectedHttpCodeResult
     * @return mixed
     * @throws Exception
     */
    private function callApi($method, $url, $body, $expectedHttpCodeResult)
    {
        $this->lastRequest = new LastRequest(
            $method,
            $url,
            $body
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-API-Key: ' . $this->apiKey]);

        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, 1);
                if ($body)
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($body)
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
                break;
            default:
                if ($body)
                    $url = sprintf("%s?%s", $url, http_build_query($body));
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Connection Error: ' . curl_errno($ch) . ' - ' . curl_error($ch));
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->lastResponse = json_decode($output, true);

        if ($httpCode != $expectedHttpCodeResult) {
            $message = isset($this->lastResponse['message']) ? "Error $httpCode from DaftarNama: " . $this->lastResponse['message'] : "Error $httpCode from Daftarnama";
            throw new Exception($message);
        }

        return $this->lastResponse;
    }
}
