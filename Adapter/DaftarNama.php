<?php

/**
 * FOSSBilling Domain Registrar Adapter untuk DaftarNama
 * 
 * Provider: DaftarNama (domain registrar Indonesia)
 * Authentication: API Key
 * Compatible dengan FOSSBilling domain management system
 * Menggunakan DaftarNama SDK Official
 */

// Include DaftarNama SDK
require_once dirname(__FILE__) . '/sdk/apis/daftarnama_api.php';
require_once dirname(__FILE__) . '/sdk/apis/requests/register_domain_request.php';
require_once dirname(__FILE__) . '/sdk/apis/requests/renew_domain_request.php';
require_once dirname(__FILE__) . '/sdk/apis/requests/transfer_domain_request.php';
require_once dirname(__FILE__) . '/sdk/apis/requests/update_contact_request.php';

class Registrar_Adapter_DaftarNama extends Registrar_AdapterAbstract
{
    protected $config = [];
    protected $api;

    public function __construct($options)
    {
        $this->config = $options;
        
        // Validate required configuration untuk DaftarNama
        if (empty($this->config['api_key'])) {
            throw new Registrar_Exception('Missing required DaftarNama configuration: api_key');
        }
        
        // Initialize DaftarNama API SDK
        $isTestMode = !empty($this->config['test_mode']) && $this->config['test_mode'] == '1';
        $apiUrl = !empty($this->config['api_url']) ? $this->config['api_url'] : null;
        
        $this->api = new DaftarnamaApi(
            $this->config['api_key'],
            $isTestMode,
            $apiUrl
        );
    }

    /**
     * Return adapter configuration form untuk DaftarNama
     */
    public static function getConfig(): array
    {
        return [
            'label' => 'DaftarNama Domain Management',
            'form' => [
                'api_key' => [
                    'text',
                    [
                        'label' => 'API Key',
                        'description' => 'API Key dari akun DaftarNama Anda',
                        'required' => true,
                    ]
                ],
                'api_url' => [
                    'text',
                    [
                        'label' => 'API URL (Optional)',
                        'description' => 'Custom API URL (kosongkan untuk default)',
                        'required' => false,
                    ]
                ],
                'test_mode' => [
                    'radio',
                    [
                        'label' => 'Test Mode',
                        'multiOptions' => ['1' => 'Yes', '0' => 'No'],
                        'description' => 'Gunakan Sandbox mode untuk testing',
                        'required' => false,
                        'value' => '1'
                    ]
                ]
            ]
        ];
    }

    /**
     * Get TLD list dari DaftarNama untuk FOSSBilling
     */
    public function getTlds(): array
    {
        try {
            return $this->getAvailableTlds();
        } catch (Exception $e) {
            error_log('DaftarNama getTlds Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Import TLD pricing dari DaftarNama - method utama untuk import
     */
    public function importTldPricing(): array
    {
        try {
            $pricingData = $this->importPricingData();
            
            $result = [
                'success' => true,
                'message' => 'TLD pricing imported successfully',
                'count' => count($pricingData),
                'data' => []
            ];
            
            foreach ($pricingData as $tld => $pricing) {
                $result['data'][] = [
                    'tld' => $tld,
                    'registrar' => 'DaftarNama',
                    'price_registration' => $pricing['registration'],
                    'price_renewal' => $pricing['renewal'],
                    'price_transfer' => $pricing['transfer'],
                    'currency' => $pricing['currency'] ?? null,
                    'min_years' => 1,
                    'max_years' => 5
                ];
            }
            
            return $result;
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to import: ' . $e->getMessage(),
                'count' => 0,
                'data' => []
            ];
        }
    }

    /**
     * Check if domain is available for registration - menggunakan DaftarNama SDK
     */
    public function isDomainAvailable(Registrar_Domain $domain): bool
    {
        try {
            $domainName = $domain->getName();
            $available = $this->api->getDomainAvailability($domainName);
            return (bool) $available;
        } catch (Exception $e) {
            // Log error and return false to be safe
            error_log('DaftarNama isDomainAvailable Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if domain can be transferred - menggunakan DaftarNama SDK
     */
    public function isDomaincanBeTransferred(Registrar_Domain $domain): bool
    {
        try {
            // DaftarNama tidak ada API khusus untuk cek transfer, 
            // gunakan getDomainInfo untuk cek status
            $domainName = $domain->getName();
            $domainInfo = $this->api->getDomainInfo($domainName);
            
            // Cek apakah domain tidak di proteksi transfer
            return !$domainInfo->isEnableTransferProtection();
            
        } catch (Exception $e) {
            // Default to transferable jika API error
            return true;
        }
    }

    /**
     * Register a new domain - menggunakan DaftarNama SDK
     */
    public function registerDomain(Registrar_Domain $domain): bool
    {
        try {
            // Prepare registration request
            $request = new RegisterDomainRequest();
            $request->setDomainName($domain->getName());
            $request->setPeriod($domain->getRegistrationPeriod() ?: 1);
            
            // Set customer information
            $contacts = $domain->getContactRegistrant();
            if ($contacts) {
                $request->setCustomerUsername($contacts->getEmail()); // Use email as username
                $request->setCustomerPassword(substr(md5($contacts->getEmail() . time()), 0, 12));
                $request->setCustomerEmail($contacts->getEmail());
                $request->setCustomerFirstname($contacts->getFirstName());
                $request->setCustomerLastname($contacts->getLastName());
                $request->setCustomerAddress($contacts->getAddress1());
                $request->setCustomerCity($contacts->getCity());
                $request->setCustomerState($contacts->getState());
                $request->setCustomerZip($contacts->getZip());
                $request->setCustomerCountry($contacts->getCountry());
                $request->setCustomerPhone($contacts->getTel());
            }
            
            // Set nameservers
            $ns = $domain->getNs();
            if (!empty($ns)) {
                $nameservers = [];
                for ($i = 0; $i < min(4, count($ns)); $i++) {
                    if (!empty($ns[$i])) {
                        $nameservers[] = $ns[$i];
                    }
                }
                $request->setNameservers($nameservers);
            }
            
            // Call API
            $this->api->registerDomain($request);
            return true;
            
        } catch (Exception $e) {
            throw new Registrar_Exception('Failed to register domain: ' . $e->getMessage());
        }
    }

    /**
     * Renew domain registration - menggunakan DaftarNama SDK
     */
    public function renewDomain(Registrar_Domain $domain): bool
    {
        try {
            $domainName = $domain->getName();
            $request = new RenewDomainRequest();
            $request->setPeriod($domain->getRegistrationPeriod() ?: 1);
            
            $this->api->renewDomain($domainName, $request);
            return true;
            
        } catch (Exception $e) {
            throw new Registrar_Exception('Failed to renew domain: ' . $e->getMessage());
        }
    }

    /**
     * Transfer domain to this registrar - menggunakan DaftarNama SDK
     */
    public function transferDomain(Registrar_Domain $domain): bool
    {
        try {
            $request = new TransferDomainRequest();
            $request->setDomainName($domain->getName());
            $request->setEppCode($domain->getEpp() ?: '');
            
            // Set customer information for transfer
            $contacts = $domain->getContactRegistrant();
            if ($contacts) {
                $request->setCustomerUsername($contacts->getEmail());
                $request->setCustomerEmail($contacts->getEmail());
            }
            
            $this->api->transferDomain($request);
            return true;
            
        } catch (Exception $e) {
            throw new Registrar_Exception('Failed to transfer domain: ' . $e->getMessage());
        }
    }

    /**
     * Get domain details - menggunakan DaftarNama SDK
     */
    public function getDomainDetails(Registrar_Domain $domain): Registrar_Domain
    {
        try {
            $domainName = $domain->getName();
            $domainInfo = $this->api->getDomainInfo($domainName);
            
            // Update domain object dengan info dari API
            $domain->setExpirationTime($domainInfo->getExpiryDate());
            $domain->setEpp($domainInfo->getEppCode());
            $domain->setNs($domainInfo->getNameservers());
            
            // Set contact information
            $contact = new Registrar_Domain_Contact();
            $contact->setFirstName(explode(' ', $domainInfo->getRegistrantName())[0] ?? '');
            $contact->setLastName(explode(' ', $domainInfo->getRegistrantName())[1] ?? '');
            $contact->setEmail($domainInfo->getRegistrantEmail());
            $contact->setTel($domainInfo->getRegistrantPhoneNumber());
            $contact->setCompany($domainInfo->getRegistrantOrganizationName());
            $contact->setAddress1($domainInfo->getRegistrantAddress1());
            $contact->setCity($domainInfo->getRegistrantCity());
            $contact->setState($domainInfo->getRegistrantProvince());
            $contact->setZip($domainInfo->getRegistrantPostalCode());
            $contact->setCountry($domainInfo->getRegistrantCountry());
            
            $domain->setContactRegistrant($contact);
            
            return $domain;
            
        } catch (Exception $e) {
            // Return domain as-is if API fails
            return $domain;
        }
    }

    /**
     * Modify domain nameservers - menggunakan DaftarNama SDK
     */
    public function modifyNs(Registrar_Domain $domain): bool
    {
        try {
            $domainName = $domain->getName();
            $ns = $domain->getNs();
            
            if (empty($ns)) {
                throw new Registrar_Exception('No nameservers provided');
            }
            
            // Convert array to proper format
            $nameservers = [];
            for ($i = 0; $i < min(4, count($ns)); $i++) {
                if (!empty($ns[$i])) {
                    $nameservers[] = $ns[$i];
                }
            }
            
            $this->api->updateNameservers($domainName, $nameservers);
            return true;
            
        } catch (Exception $e) {
            throw new Registrar_Exception('Failed to modify nameservers: ' . $e->getMessage());
        }
    }

    /**
     * Modify domain contact information - menggunakan DaftarNama SDK
     */
    public function modifyContact(Registrar_Domain $domain): bool
    {
        try {
            $domainName = $domain->getName();
            $contacts = $domain->getContactRegistrant();
            
            if (!$contacts) {
                throw new Registrar_Exception('No contact information provided');
            }
            
            $request = new UpdateContactRequest();
            $request->setName($contacts->getFirstName() . ' ' . $contacts->getLastName());
            $request->setOrganizationName($contacts->getCompany() ?: '');
            $request->setEmail($contacts->getEmail());
            $request->setAddress1($contacts->getAddress1() ?: '');
            $request->setCity($contacts->getCity() ?: '');
            $request->setProvince($contacts->getState() ?: '');
            $request->setCountry($contacts->getCountry() ?: '');
            $request->setPostalCode($contacts->getZip() ?: '');
            $request->setPhoneNumber($contacts->getTel() ?: '');
            
            $this->api->updateRegistrantContact($domainName, $request);
            return true;
            
        } catch (Exception $e) {
            throw new Registrar_Exception('Failed to modify contact information: ' . $e->getMessage());
        }
    }

    /**
     * Get domain EPP/Auth code - menggunakan DaftarNama SDK
     */
    public function getEpp(Registrar_Domain $domain): string
    {
        try {
            $domainName = $domain->getName();
            $domainInfo = $this->api->getDomainInfo($domainName);
            return $domainInfo->getEppCode();
            
        } catch (Exception $e) {
            throw new Registrar_Exception('Failed to get EPP code: ' . $e->getMessage());
        }
    }

    /**
     * Delete/Cancel domain - DaftarNama tidak support delete domain via API
     */
    public function deleteDomain(Registrar_Domain $domain): bool
    {
        throw new Registrar_Exception('Domain deletion not supported via DaftarNama API');
    }

    /**
     * Enable domain privacy protection - belum tersedia di DaftarNama API
     */
    public function enablePrivacyProtection(Registrar_Domain $domain): bool
    {
        throw new Registrar_Exception('Privacy protection not yet supported by DaftarNama API');
    }

    /**
     * Disable domain privacy protection - belum tersedia di DaftarNama API
     */
    public function disablePrivacyProtection(Registrar_Domain $domain): bool
    {
        throw new Registrar_Exception('Privacy protection not yet supported by DaftarNama API');
    }

    /**
     * Lock domain - menggunakan transfer protection
     */
    public function lock(Registrar_Domain $domain): bool
    {
        try {
            $domainName = $domain->getName();
            $this->api->updateEnableTransferProtection($domainName, true);
            return true;
            
        } catch (Exception $e) {
            throw new Registrar_Exception('Failed to lock domain: ' . $e->getMessage());
        }
    }

    /**
     * Unlock domain - disable transfer protection
     */
    public function unlock(Registrar_Domain $domain): bool
    {
        try {
            $domainName = $domain->getName();
            $this->api->updateEnableTransferProtection($domainName, false);
            return true;
            
        } catch (Exception $e) {
            throw new Registrar_Exception('Failed to unlock domain: ' . $e->getMessage());
        }
    }

    /**
     * Check if domain is locked
     */
    public function isLocked(Registrar_Domain $domain): bool
    {
        try {
            $domainName = $domain->getName();
            $domainInfo = $this->api->getDomainInfo($domainName);
            return $domainInfo->isEnableTransferProtection();
            
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get list of TLDs available from DaftarNama
     * 
     * @return array
     * @throws Registrar_Exception
     */
    public function getAvailableTlds(): array
    {
        try {
            return $this->api->getSellingTlds();
        } catch (Exception $e) {
            throw new Registrar_Exception('Failed to get TLD list: ' . $e->getMessage());
        }
    }

    /**
     * Get pricing for all TLDs from DaftarNama
     * 
     * @return array Associative array with TLD as key and pricing info as value
     * @throws Registrar_Exception
     */
    public function getTldPricing(): array
    {
        try {
            $response = $this->api->getCustomerTldPricings();
            
            $pricing = [];
            foreach ($response as $tld => $currencies) {
                // Ambil currency pertama yang tersedia
                $periods = is_array($currencies) ? reset($currencies) : [];
                $currency = is_array($currencies) ? array_key_first($currencies) : null;

                $pricing[$tld] = [
                    'currency' => $currency,
                    'registration' => [],
                    'renewal' => [],
                    'transfer' => []
                ];
                
                foreach ($periods as $period => $prices) {
                    $pricing[$tld]['registration'][$period] = $prices['register'] ?? 0;
                    $pricing[$tld]['renewal'][$period] = $prices['renew'] ?? 0;
                    $pricing[$tld]['transfer'][$period] = $prices['transfer'] ?? 0;
                }
            }
            
            return $pricing;
        } catch (Exception $e) {
            throw new Registrar_Exception('Failed to get pricing: ' . $e->getMessage());
        }
    }

    /**
     * Import TLD pricing from DaftarNama and return formatted for FOSSBilling
     * 
     * @return array Pricing data formatted for FOSSBilling import
     * @throws Registrar_Exception
     */
    public function importPricingData(): array
    {
        try {
            $pricingData = $this->getTldPricing();
            $fossBillingFormat = [];
            
            foreach ($pricingData as $tld => $pricing) {
                // Format untuk FOSSBilling domain pricing
                $fossBillingFormat[$tld] = [
                    'currency' => $pricing['currency'] ?? null,
                    'registration' => $pricing['registration'][1] ?? 0, // 1 year registration
                    'renewal' => $pricing['renewal'][1] ?? 0, // 1 year renewal  
                    'transfer' => $pricing['transfer'][1] ?? 0, // 1 year transfer
                    'setup' => 0,
                    'price_2' => $pricing['registration'][2] ?? 0, // 2 years
                    'price_3' => $pricing['registration'][3] ?? 0, // 3 years
                    'price_4' => $pricing['registration'][4] ?? 0, // 4 years
                    'price_5' => $pricing['registration'][5] ?? 0, // 5 years
                ];
            }
            
            return $fossBillingFormat;
        } catch (Exception $e) {
            throw new Registrar_Exception('Failed to import pricing data: ' . $e->getMessage());
        }
    }

    /**
     * Synchronize pricing with DaftarNama - simple method
     */
    public function syncPricing(): array
    {
        try {
            $pricingData = $this->importPricingData();
            
            return [
                'status' => 'success',
                'total_tlds' => count($pricingData),
                'updated_at' => date('Y-m-d H:i:s'),
                'pricing_data' => $pricingData
            ];
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'total_tlds' => 0,
                'pricing_data' => []
            ];
        }
    }
}
