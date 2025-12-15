<?php

/**
 * DaftarNama Sync module - standalone service (tidak mengubah core Servicedomain).
 */

namespace Box\Mod\Daftarnamasync;

use FOSSBilling\InjectionAwareInterface;

class Service implements InjectionAwareInterface
{
    protected ?\Pimple\Container $di = null;

    public function setDi(\Pimple\Container $di): void
    {
        $this->di = $di;
    }

    public function getDi(): ?\Pimple\Container
    {
        return $this->di;
    }

    private function loadImporter(): array
    {
        require_once PATH_LIBRARY . '/Registrar/Adapter/sdk/helpers/DaftarNamaTldImporter.php';

        $registrar = $this->di['db']->findOne('TldRegistrar', 'registrar = :reg', [':reg' => 'DaftarNama']);
        if (!$registrar instanceof \Model_TldRegistrar) {
            throw new \FOSSBilling\Exception('Registrar DaftarNama belum terpasang. Tambahkan di Domain Registration > Registrars.');
        }

        $adapter = $this->di['mod_service']('Servicedomain')->registrarGetRegistrarAdapter($registrar);

        return [$adapter, $registrar];
    }

    private function getRegistrarName(?int $id): ?string
    {
        if (!$id) {
            return null;
        }
        $row = $this->di['db']->getRow('SELECT name FROM tld_registrar WHERE id = :id', [':id' => $id]);

        return $row['name'] ?? null;
    }

    public function preview(array $options): array
    {
        [$adapter, $registrar] = $this->loadImporter();

        $sourceCurrency = $options['source_currency'] ?? 'IDR';
        $importer = new \DaftarNamaTldImporter($this->di, $adapter, $sourceCurrency);
        $prepared = $importer->preparePricingPayloads();

        $domainSvc = $this->di['mod_service']('Servicedomain');
        $rows = [];
        foreach ($prepared['rows'] as $row) {
            $existing = $domainSvc->tldFindOneByTld($row['tld']);
            $existingRegistrar = $existing ? $this->getRegistrarName($existing->tld_registrar_id) : null;

            $rows[] = array_merge($row, [
                'exists' => (bool) $existing,
                'existing_registrar' => $existingRegistrar,
            ]);
        }

        return [
            'meta' => $prepared['meta'],
            'rows' => $rows,
        ];
    }

    /**
     * Validasi SLD/TLD tanpa menyentuh core.
     */
    public function validateTld(array $data): array
    {
        $sld = $data['sld'] ?? '';
        $tld = $data['tld'] ?? '';

        $sldLen = strlen((string) $sld);
        $errors = [];

        // Khusus .id: tolak 2-4 karakter
        if (str_ends_with((string) $tld, '.id') && $sldLen >= 2 && $sldLen <= 4) {
            $errors[] = 'SLD untuk .id minimal 5 karakter';
        }

        return [
            'valid' => count($errors) === 0,
            'errors' => $errors,
        ];
    }

    /**
     * Hook: tolak order domain .id (dan turunannya) jika SLD < 5 karakter.
     */
    public static function onBeforeClientOrderCreate(\Box_Event $event): bool
    {
        $params = $event->getParameters();

        // Pastikan ini order domain
        $config = $params['config'] ?? [];
        if (($config['type'] ?? null) !== 'domain') {
            return true;
        }

        $action = $config['action'] ?? '';
        if ($action !== 'register') {
            return true;
        }

        $sld = $config['register_sld'] ?? '';
        $tld = $config['register_tld'] ?? '';

        $sldLen = strlen((string) $sld);
        if ($tld && str_ends_with((string) $tld, '.id') && $sldLen > 0 && $sldLen < 5) {
            throw new \FOSSBilling\InformationException('SLD untuk .id (dan turunannya) minimal 5 karakter');
        }

        return true;
    }

    /**
     * Jalankan sync hanya untuk TLD terpilih.
     */
    public function syncSelected(array $options): array
    {
        $tlds = $options['tlds'] ?? [];
        if (!is_array($tlds) || count($tlds) === 0) {
            throw new \FOSSBilling\Exception('No TLDs selected for sync.');
        }

        [$adapter, $registrar] = $this->loadImporter();

        $sourceCurrency = $options['source_currency'] ?? 'IDR';
        $updateExisting = $options['update_existing'] ?? true;
        $dryRun = $options['dry_run'] ?? false;

        $importer = new \DaftarNamaTldImporter($this->di, $adapter, $sourceCurrency);
        $prepared = $importer->preparePricingPayloads();

        // Filter rows by selected TLDs
        $selected = [];
        $normTlds = array_map(function ($tld) {
            $tld = trim($tld);
            return str_starts_with($tld, '.') ? $tld : '.' . $tld;
        }, $tlds);

        foreach ($prepared['rows'] as $row) {
            if (in_array($row['tld'], $normTlds, true)) {
                $selected[] = $row;
            }
        }

        if (count($selected) === 0) {
            throw new \FOSSBilling\Exception('Selected TLDs not found in fetched pricing.');
        }

        $prepared['rows'] = $selected;
        $prepared['meta']['total'] = count($selected);

        $summary = $importer->sync((bool) $updateExisting, (bool) $dryRun, $prepared);

        return [
            'summary' => $summary,
            'meta' => $prepared['meta'],
            'selected' => $selected,
        ];
    }
}
