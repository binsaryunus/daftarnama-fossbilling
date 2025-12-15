<?php

class RegisterDomainRequest
{
    private $domainName;
    private $duration;
    private $nameservers;
    private $customerUsername;
    private $customerPassword;
    private $fullName;
    private $companyName;
    private $email;
    private $address1;
    private $address2;
    private $address3;
    private $city;
    private $province;
    private $countryCode;
    private $postalCode;
    private $phoneNumber;
    private $mobilePhoneNumber;

    /**
     * @param string $domainName
     * @param int $duration
     * @param string[] $nameservers
     * @param string $customerUsername
     * @param string $customerPassword
     * @param string $fullName
     * @param string $companyName
     * @param string $email
     * @param string $address1
     * @param string $address2
     * @param string $address3
     * @param string $city
     * @param string $province
     * @param string $countryCode
     * @param string $postalCode
     * @param string $phoneNumber
     * @param string $mobilePhoneNumber
     */
    public function __construct($domainName, $duration, $nameservers, $customerUsername, $customerPassword, $fullName, $companyName, $email, $address1, $address2, $address3, $city, $province, $countryCode, $postalCode, $phoneNumber, $mobilePhoneNumber)
    {
        $this->domainName = $domainName;
        $this->duration = $duration;
        $this->nameservers = $nameservers;
        $this->customerUsername = $customerUsername;
        $this->customerPassword = $customerPassword;
        $this->fullName = $fullName;
        $this->companyName = $companyName;
        $this->email = $email;
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->address3 = $address3;
        $this->city = $city;
        $this->province = $province;
        $this->countryCode = $countryCode;
        $this->postalCode = $postalCode;
        $this->phoneNumber = $phoneNumber;
        $this->mobilePhoneNumber = $mobilePhoneNumber;
    }

    /**
     * @return string
     */
    public function getDomainName()
    {
        return $this->domainName;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return string[]
     */
    public function getNameservers()
    {
        $nameservers = [];
        foreach ($this->nameservers as $nameserver) {
            if ($nameserver) {
                $nameservers[] = $nameserver;
            }
        }
        return $nameservers;
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
    public function getCustomerPassword()
    {
        return $this->customerPassword;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName ?: $this->fullName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @return string
     */
    public function getAddress3()
    {
        return $this->address3;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getMobilePhoneNumber()
    {
        return $this->mobilePhoneNumber;
    }
}