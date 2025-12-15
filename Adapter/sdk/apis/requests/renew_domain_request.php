<?php

class RenewDomainRequest
{
    private $currentExpiryDate;
    private $duration;

    /**
     * @param string $currentExpiryDate
     * @param int $duration
     */
    public function __construct($currentExpiryDate, $duration)
    {
        $this->currentExpiryDate = $currentExpiryDate;
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getCurrentExpiryDate()
    {
        return $this->currentExpiryDate;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }
}