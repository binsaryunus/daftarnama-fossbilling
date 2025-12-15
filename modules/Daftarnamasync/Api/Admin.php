<?php

/**
 * Admin API untuk DaftarNama Sync (mod terpisah).
 */

namespace Box\Mod\Daftarnamasync\Api;

class Admin extends \Api_Abstract
{
    /**
     * Jalankan import & sync TLD DaftarNama.
     *
     * @optional string $source_currency Default: IDR
     * @optional bool $update_existing Default: true
     * @optional bool $dry_run Default: false
     */
    public function sync($data)
    {
        return $this->getService()->sync([
            'source_currency' => $data['source_currency'] ?? 'IDR',
            'update_existing' => isset($data['update_existing']) ? (bool) $data['update_existing'] : true,
            'dry_run' => isset($data['dry_run']) ? (bool) $data['dry_run'] : false,
        ]);
    }

    /**
     * Preview daftar TLD & status existing.
     */
    public function preview($data)
    {
        return $this->getService()->preview([
            'source_currency' => $data['source_currency'] ?? 'IDR',
        ]);
    }

    /**
     * Sync hanya TLD yang dipilih.
     */
    public function sync_selected($data)
    {
        $required = [
            'tlds' => 'TLD list is required',
        ];
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        return $this->getService()->syncSelected([
            'tlds' => $data['tlds'],
            'source_currency' => $data['source_currency'] ?? 'IDR',
            'update_existing' => isset($data['update_existing']) ? (bool) $data['update_existing'] : true,
            'dry_run' => isset($data['dry_run']) ? (bool) $data['dry_run'] : false,
        ]);
    }

    /**
     * Validasi SLD/TLD (non-core).
     *
     * @required string $sld
     * @required string $tld
     */
    public function validate($data)
    {
        $required = [
            'sld' => 'SLD is required',
            'tld' => 'TLD is required',
        ];
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        return $this->getService()->validateTld($data);
    }
}
