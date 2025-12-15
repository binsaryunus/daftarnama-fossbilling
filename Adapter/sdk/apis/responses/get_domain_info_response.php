<?php

class GetDomainInfoResponse
{
    private $domainName;
    private $type;
    private $eppCode;
    private $registrationDate;
    private $expiryDate;
    private $status;
    private $documentStatus;
    private $isEnableTransferProtection;
    private $isAutoProvisioning;
    private $isSuspended;
    private $nameservers;
    private $customerUsername;
    private $registrantFullName;
    private $registrantOrganizationName;
    private $registrantEmail;
    private $registrantAddress1;
    private $registrantAddress2;
    private $registrantAddress3;
    private $registrantCity;
    private $registrantProvince;
    private $registrantCountryCode;
    private $registrantPostalCode;
    private $registrantPhoneNumber;
    private $registrantMobilePhoneNumber;
    private $adminFullName;
    private $adminOrganizationName;
    private $adminEmail;
    private $adminAddress1;
    private $adminAddress2;
    private $adminAddress3;
    private $adminCity;
    private $adminProvince;
    private $adminCountryCode;
    private $adminPostalCode;
    private $adminPhoneNumber;
    private $adminMobilePhoneNumber;
    private $billingFullName;
    private $billingOrganizationName;
    private $billingEmail;
    private $billingAddress1;
    private $billingAddress2;
    private $billingAddress3;
    private $billingCity;
    private $billingProvince;
    private $billingCountryCode;
    private $billingPostalCode;
    private $billingPhoneNumber;
    private $billingMobilePhoneNumber;
    private $techFullName;
    private $techOrganizationName;
    private $techEmail;
    private $techAddress1;
    private $techAddress2;
    private $techAddress3;
    private $techCity;
    private $techProvince;
    private $techCountryCode;
    private $techPostalCode;
    private $techPhoneNumber;
    private $techMobilePhoneNumber;

    /**
     * @param string $domainName
     * @param string $eppCode
     * @param string $registrationDate
     * @param string $expiryDate
     * @param string $status
     * @param string $documentStatus
     * @param bool $isEnableTransferProtection
     * @param bool $isAutoProvisioning
     * @param bool $isSuspended
     * @param array $nameservers
     * @param string $customerUsername
     * @param string $registrantFullName
     * @param string $registrantOrganizationName
     * @param string $registrantEmail
     * @param string $registrantAddress1
     * @param string $registrantAddress2
     * @param string $registrantAddress3
     * @param string $registrantCity
     * @param string $registrantProvince
     * @param string $registrantCountryCode
     * @param string $registrantPostalCode
     * @param string $registrantPhoneNumber
     * @param string $registrantMobilePhoneNumber
     * @param string $adminFullName
     * @param string $adminOrganizationName
     * @param string $adminEmail
     * @param string $adminAddress1
     * @param string $adminAddress2
     * @param string $adminAddress3
     * @param string $adminCity
     * @param string $adminProvince
     * @param string $adminCountryCode
     * @param string $adminPostalCode
     * @param string $adminPhoneNumber
     * @param string $adminMobilePhoneNumber
     * @param string $billingFullName
     * @param string $billingOrganizationName
     * @param string $billingEmail
     * @param string $billingAddress1
     * @param string $billingAddress2
     * @param string $billingAddress3
     * @param string $billingCity
     * @param string $billingProvince
     * @param string $billingCountryCode
     * @param string $billingPostalCode
     * @param string $billingPhoneNumber
     * @param string $billingMobilePhoneNumber
     * @param string $techFullName
     * @param string $techOrganizationName
     * @param string $techEmail
     * @param string $techAddress1
     * @param string $techAddress2
     * @param string $techAddress3
     * @param string $techCity
     * @param string $techProvince
     * @param string $techCountryCode
     * @param string $techPostalCode
     * @param string $techPhoneNumber
     * @param string $techMobilePhoneNumber
     */
    public function __construct($domainName, $type, $eppCode, $registrationDate, $expiryDate, $status, $documentStatus, $isEnableTransferProtection, $isAutoProvisioning, $isSuspended, $nameservers, $customerUsername, $registrantFullName, $registrantOrganizationName, $registrantEmail, $registrantAddress1, $registrantAddress2, $registrantAddress3, $registrantCity, $registrantProvince, $registrantCountryCode, $registrantPostalCode, $registrantPhoneNumber, $registrantMobilePhoneNumber, $adminFullName, $adminOrganizationName, $adminEmail, $adminAddress1, $adminAddress2, $adminAddress3, $adminCity, $adminProvince, $adminCountryCode, $adminPostalCode, $adminPhoneNumber, $adminMobilePhoneNumber, $billingFullName, $billingOrganizationName, $billingEmail, $billingAddress1, $billingAddress2, $billingAddress3, $billingCity, $billingProvince, $billingCountryCode, $billingPostalCode, $billingPhoneNumber, $billingMobilePhoneNumber, $techFullName, $techOrganizationName, $techEmail, $techAddress1, $techAddress2, $techAddress3, $techCity, $techProvince, $techCountryCode, $techPostalCode, $techPhoneNumber, $techMobilePhoneNumber)
    {
        $this->domainName = $domainName;
        $this->type = $type;
        $this->eppCode = $eppCode;
        $this->registrationDate = $registrationDate;
        $this->expiryDate = $expiryDate;
        $this->status = $status;
        $this->documentStatus = $documentStatus;
        $this->isEnableTransferProtection = $isEnableTransferProtection;
        $this->isAutoProvisioning = $isAutoProvisioning;
        $this->isSuspended = $isSuspended;
        $this->nameservers = $nameservers;
        $this->customerUsername = $customerUsername;
        $this->registrantFullName = $registrantFullName;
        $this->registrantOrganizationName = $registrantOrganizationName;
        $this->registrantEmail = $registrantEmail;
        $this->registrantAddress1 = $registrantAddress1;
        $this->registrantAddress2 = $registrantAddress2;
        $this->registrantAddress3 = $registrantAddress3;
        $this->registrantCity = $registrantCity;
        $this->registrantProvince = $registrantProvince;
        $this->registrantCountryCode = $registrantCountryCode;
        $this->registrantPostalCode = $registrantPostalCode;
        $this->registrantPhoneNumber = $registrantPhoneNumber;
        $this->registrantMobilePhoneNumber = $registrantMobilePhoneNumber;
        $this->adminFullName = $adminFullName;
        $this->adminOrganizationName = $adminOrganizationName;
        $this->adminEmail = $adminEmail;
        $this->adminAddress1 = $adminAddress1;
        $this->adminAddress2 = $adminAddress2;
        $this->adminAddress3 = $adminAddress3;
        $this->adminCity = $adminCity;
        $this->adminProvince = $adminProvince;
        $this->adminCountryCode = $adminCountryCode;
        $this->adminPostalCode = $adminPostalCode;
        $this->adminPhoneNumber = $adminPhoneNumber;
        $this->adminMobilePhoneNumber = $adminMobilePhoneNumber;
        $this->billingFullName = $billingFullName;
        $this->billingOrganizationName = $billingOrganizationName;
        $this->billingEmail = $billingEmail;
        $this->billingAddress1 = $billingAddress1;
        $this->billingAddress2 = $billingAddress2;
        $this->billingAddress3 = $billingAddress3;
        $this->billingCity = $billingCity;
        $this->billingProvince = $billingProvince;
        $this->billingCountryCode = $billingCountryCode;
        $this->billingPostalCode = $billingPostalCode;
        $this->billingPhoneNumber = $billingPhoneNumber;
        $this->billingMobilePhoneNumber = $billingMobilePhoneNumber;
        $this->techFullName = $techFullName;
        $this->techOrganizationName = $techOrganizationName;
        $this->techEmail = $techEmail;
        $this->techAddress1 = $techAddress1;
        $this->techAddress2 = $techAddress2;
        $this->techAddress3 = $techAddress3;
        $this->techCity = $techCity;
        $this->techProvince = $techProvince;
        $this->techCountryCode = $techCountryCode;
        $this->techPostalCode = $techPostalCode;
        $this->techPhoneNumber = $techPhoneNumber;
        $this->techMobilePhoneNumber = $techMobilePhoneNumber;
    }

    /**
     * @return string
     */
    public function getDomainName()
    {
        return $this->domainName;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getEppCode()
    {
        return $this->eppCode;
    }

    /**
     * @return string
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * @return string
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getDocumentStatus()
    {
        return $this->documentStatus;
    }

    /**
     * @return bool
     */
    public function isEnableTransferProtection()
    {
        return $this->isEnableTransferProtection;
    }

    /**
     * @return bool
     */
    public function isAutoProvisioning()
    {
        return $this->isAutoProvisioning;
    }

    /**
     * @return bool
     */
    public function isSuspended()
    {
        return $this->isSuspended;
    }

    /**
     * @return string[]
     */
    public function getNameservers()
    {
        return $this->nameservers;
    }

    /**
     * @return string
     */
    public function getCustomerUsername()
    {
        return $this->customerUsername;
    }

    /**
     * @return string
     */
    public function getRegistrantFullName()
    {
        return $this->registrantFullName;
    }

    /**
     * @return string
     */
    public function getRegistrantOrganizationName()
    {
        return $this->registrantOrganizationName;
    }

    /**
     * @return string
     */
    public function getRegistrantEmail()
    {
        return $this->registrantEmail;
    }

    /**
     * @return string
     */
    public function getRegistrantAddress1()
    {
        return $this->registrantAddress1;
    }

    /**
     * @return string
     */
    public function getRegistrantAddress2()
    {
        return $this->registrantAddress2;
    }

    /**
     * @return string
     */
    public function getRegistrantAddress3()
    {
        return $this->registrantAddress3;
    }

    /**
     * @return string
     */
    public function getRegistrantCity()
    {
        return $this->registrantCity;
    }

    /**
     * @return string
     */
    public function getRegistrantProvince()
    {
        return $this->registrantProvince;
    }

    /**
     * @return string
     */
    public function getRegistrantCountryCode()
    {
        return $this->registrantCountryCode;
    }

    /**
     * @return string
     */
    public function getRegistrantPostalCode()
    {
        return $this->registrantPostalCode;
    }

    /**
     * @return string
     */
    public function getRegistrantPhoneNumber()
    {
        return $this->registrantPhoneNumber;
    }

    /**
     * @return string
     */
    public function getRegistrantMobilePhoneNumber()
    {
        return $this->registrantMobilePhoneNumber;
    }

    /**
     * @return string
     */
    public function getAdminFullName()
    {
        return $this->adminFullName;
    }

    /**
     * @return string
     */
    public function getAdminOrganizationName()
    {
        return $this->adminOrganizationName;
    }

    /**
     * @return string
     */
    public function getAdminEmail()
    {
        return $this->adminEmail;
    }

    /**
     * @return string
     */
    public function getAdminAddress1()
    {
        return $this->adminAddress1;
    }

    /**
     * @return string
     */
    public function getAdminAddress2()
    {
        return $this->adminAddress2;
    }

    /**
     * @return string
     */
    public function getAdminAddress3()
    {
        return $this->adminAddress3;
    }

    /**
     * @return string
     */
    public function getAdminCity()
    {
        return $this->adminCity;
    }

    /**
     * @return string
     */
    public function getAdminProvince()
    {
        return $this->adminProvince;
    }

    /**
     * @return string
     */
    public function getAdminCountryCode()
    {
        return $this->adminCountryCode;
    }

    /**
     * @return string
     */
    public function getAdminPostalCode()
    {
        return $this->adminPostalCode;
    }

    /**
     * @return string
     */
    public function getAdminPhoneNumber()
    {
        return $this->adminPhoneNumber;
    }

    /**
     * @return string
     */
    public function getAdminMobilePhoneNumber()
    {
        return $this->adminMobilePhoneNumber;
    }

    /**
     * @return string
     */
    public function getBillingFullName()
    {
        return $this->billingFullName;
    }

    /**
     * @return string
     */
    public function getBillingOrganizationName()
    {
        return $this->billingOrganizationName;
    }

    /**
     * @return string
     */
    public function getBillingEmail()
    {
        return $this->billingEmail;
    }

    /**
     * @return string
     */
    public function getBillingAddress1()
    {
        return $this->billingAddress1;
    }

    /**
     * @return string
     */
    public function getBillingAddress2()
    {
        return $this->billingAddress2;
    }

    /**
     * @return string
     */
    public function getBillingAddress3()
    {
        return $this->billingAddress3;
    }

    /**
     * @return string
     */
    public function getBillingCity()
    {
        return $this->billingCity;
    }

    /**
     * @return string
     */
    public function getBillingProvince()
    {
        return $this->billingProvince;
    }

    /**
     * @return string
     */
    public function getBillingCountryCode()
    {
        return $this->billingCountryCode;
    }

    /**
     * @return string
     */
    public function getBillingPostalCode()
    {
        return $this->billingPostalCode;
    }

    /**
     * @return string
     */
    public function getBillingPhoneNumber()
    {
        return $this->billingPhoneNumber;
    }

    /**
     * @return string
     */
    public function getBillingMobilePhoneNumber()
    {
        return $this->billingMobilePhoneNumber;
    }

    /**
     * @return string
     */
    public function getTechFullName()
    {
        return $this->techFullName;
    }

    /**
     * @return string
     */
    public function getTechOrganizationName()
    {
        return $this->techOrganizationName;
    }

    /**
     * @return string
     */
    public function getTechEmail()
    {
        return $this->techEmail;
    }

    /**
     * @return string
     */
    public function getTechAddress1()
    {
        return $this->techAddress1;
    }

    /**
     * @return string
     */
    public function getTechAddress2()
    {
        return $this->techAddress2;
    }

    /**
     * @return string
     */
    public function getTechAddress3()
    {
        return $this->techAddress3;
    }

    /**
     * @return string
     */
    public function getTechCity()
    {
        return $this->techCity;
    }

    /**
     * @return string
     */
    public function getTechProvince()
    {
        return $this->techProvince;
    }

    /**
     * @return string
     */
    public function getTechCountryCode()
    {
        return $this->techCountryCode;
    }

    /**
     * @return string
     */
    public function getTechPostalCode()
    {
        return $this->techPostalCode;
    }

    /**
     * @return string
     */
    public function getTechPhoneNumber()
    {
        return $this->techPhoneNumber;
    }

    /**
     * @return string
     */
    public function getTechMobilePhoneNumber()
    {
        return $this->techMobilePhoneNumber;
    }

    /**
     * @param string $fullName
     * @return string
     */
    public function getFirstNameFromFullName($fullName)
    {
        $parts = explode(' ', $fullName);
        if (count($parts) === 1) {
            return $fullName;
        }
        array_pop($parts);
        return implode(' ', $parts);
    }

    /**
     * @param string $fullName
     * @return mixed|string|null
     */
    public function getLastNameFromFullName($fullName)
    {
        $parts = explode(' ', $fullName);
        if (count($parts) === 1) {
            return '';
        }
        return array_pop($parts);
    }
}