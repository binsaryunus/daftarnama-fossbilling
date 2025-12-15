<?php

/**
 * Helper untuk import & sinkronisasi harga TLD DaftarNama ke FOSSBilling
 * tanpa mengubah core codebase. Menggunakan service FOSSBilling yang ada.
 */
class DaftarNamaTldImporter
{
    private \Pimple\Container $di;
    private Registrar_Adapter_DaftarNama $adapter;
    private $domainService;
    private $currencyService;
    private string $sourceCurrency;

    public function __construct(\Pimple\Container $di, Registrar_Adapter_DaftarNama $adapter, string $sourceCurrency = 'IDR')
    {
        $this->di = $di;
        $this->adapter = $adapter;
        $this->domainService = $di['mod_service']('Servicedomain');
        $this->currencyService = $di['mod_service']('Currency');
        $this->sourceCurrency = strtoupper($sourceCurrency ?: 'IDR');
    }

    public function fetchPricing(): array
    {
        $result = $this->adapter->importTldPricing();

        if (!$result['success']) {
            throw new RuntimeException($result['message']);
        }

        return $result;
    }

    /**
     * Bangun payload TLD yang sudah dikonversi ke currency default.
     */
    public function preparePricingPayloads(): array
    {
        $result = $this->fetchPricing();
        $registrarId = $this->getRegistrarId();

        if (!$registrarId) {
            throw new RuntimeException('DaftarNama registrar belum dikonfigurasi di FOSSBilling admin.');
        }

        $rows = [];

        foreach ($result['data'] as $item) {
            $rows[] = $this->buildPayload($item, $registrarId);
        }

        return [
            'rows' => $rows,
            'registrar_id' => $registrarId,
            'meta' => [
                'total' => count($rows),
                'source_currency' => $this->sourceCurrency,
                'default_currency' => $this->currencyService->getDefault()->code ?? 'N/A',
            ],
        ];
    }

    public function sync(bool $updateExisting = true, bool $dryRun = false, ?array $prepared = null): array
    {
        $prepared = $prepared ?? $this->preparePricingPayloads();

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($prepared['rows'] as $payload) {
            $existing = $this->domainService->tldFindOneByTld($payload['tld']);

            if ($existing instanceof \Model_Tld) {
                if ($updateExisting) {
                    if (!$dryRun) {
                        $this->domainService->tldUpdate($existing, $payload);
                    }
                    $updated++;
                } else {
                    $skipped++;
                }
            } else {
                if (!$dryRun) {
                    $this->domainService->tldCreate($payload);
                }
                $created++;
            }
        }

        return [
            'success' => true,
            'source_currency' => $this->sourceCurrency,
            'default_currency' => $this->currencyService->getDefault()->code ?? 'N/A',
            'total' => $prepared['meta']['total'],
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'dry_run' => $dryRun,
        ];
    }

    private function getRegistrarId(): ?int
    {
        $row = $this->di['db']->getRow("SELECT id FROM tld_registrar WHERE name = 'DaftarNama' LIMIT 1");

        return $row ? (int) $row['id'] : null;
    }

    private function normalizeTld(string $tld): string
    {
        $tld = trim($tld);

        return str_starts_with($tld, '.') ? $tld : '.' . $tld;
    }

    private function buildPayload(array $item, int $registrarId): array
    {
        $currency = $item['currency'] ?? null;

        return [
            'tld' => $this->normalizeTld($item['tld']),
            'tld_registrar_id' => $registrarId,
            'price_registration' => $this->convertToDefaultCurrency($item['price_registration'], $currency),
            'price_renew' => $this->convertToDefaultCurrency($item['price_renewal'], $currency),
            'price_transfer' => $this->convertToDefaultCurrency($item['price_transfer'], $currency),
            'min_years' => $item['min_years'] ?? 1,
            'allow_register' => 1,
            'allow_transfer' => 1,
            'active' => 1,
        ];
    }

    private function convertToDefaultCurrency($amount, ?string $currencyOverride = null): float
    {
        $amount = (float) $amount;
        $sourceCurrency = $currencyOverride ? strtoupper($currencyOverride) : $this->sourceCurrency;

        try {
            $defaultCurrency = $this->currencyService->getDefault();

            if ($defaultCurrency && strcasecmp($defaultCurrency->code, $sourceCurrency) !== 0) {
                return (float) $this->currencyService->toBaseCurrency($sourceCurrency, $amount);
            }
        } catch (\Throwable $e) {
            error_log('DaftarNama import currency conversion failed: ' . $e->getMessage());
        }

        return $amount;
    }
}
