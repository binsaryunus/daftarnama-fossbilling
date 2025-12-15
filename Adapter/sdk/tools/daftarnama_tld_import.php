<?php

/**
 * DaftarNama TLD Import & Sync Tool (CLI)
 * Tanpa modifikasi core FOSSBilling.
 *
 * Usage:
 *   php daftarnama_tld_import.php --api-key=KEY [--auto] [--source-currency=IDR] [--no-update] [--dry-run]
 *   php daftarnama_tld_import.php KEY auto                     (kompatibel dengan cara lama)
 */

require_once __DIR__ . '/load.php';
require_once __DIR__ . '/library/Registrar/Adapter/sdk/helpers/DaftarNamaTldImporter.php';

// CLI options
$options = getopt('', [
    'api-key:',
    'auto',
    'source-currency:',
    'no-update',
    'dry-run',
]);

$apiKey = $options['api-key'] ?? $argv[1] ?? null;
$autoMode = isset($options['auto']) || (isset($argv[2]) && $argv[2] === 'auto');
$sourceCurrency = $options['source-currency'] ?? getenv('DAFTARNAMA_SOURCE_CURRENCY') ?? 'IDR';
$updateExisting = !isset($options['no-update']);
$dryRun = isset($options['dry-run']);

if (!$apiKey) {
    echo "Enter your DaftarNama API Key: ";
    $apiKey = trim(fgets(STDIN));
}

if (!$apiKey) {
    echo "Error: API Key is required\n";
    exit(1);
}

try {
    $config = [
        'api_key' => $apiKey,
        'test_mode' => '0',
    ];

    $adapter = new Registrar_Adapter_DaftarNama($config);
    $importer = new DaftarNamaTldImporter($di, $adapter, $sourceCurrency);

    echo "🔄 Fetching TLD pricing from DaftarNama (source currency: {$sourceCurrency})...\n";
    $prepared = $importer->preparePricingPayloads();

    echo "✅ Success. Total TLDs: " . $prepared['meta']['total'] . "\n";
    echo "📌 Converted to default currency: " . $prepared['meta']['default_currency'] . "\n\n";

    if (!empty($prepared['rows'])) {
        echo "Sample TLD Pricing (converted):\n";
        echo str_pad('TLD', 12) . str_pad('Register', 14) . str_pad('Renew', 14) . "Transfer\n";
        echo str_repeat('-', 54) . "\n";

        foreach (array_slice($prepared['rows'], 0, 10) as $tld) {
            echo str_pad($tld['tld'], 12) .
                str_pad(number_format($tld['price_registration'], 2), 14) .
                str_pad(number_format($tld['price_renew'], 2), 14) .
                number_format($tld['price_transfer'], 2) . "\n";
        }

        if (count($prepared['rows']) > 10) {
            echo "... and " . (count($prepared['rows']) - 10) . " more TLDs\n";
        }
    }

    if (!$autoMode && !$dryRun) {
        echo "\nℹ️  Use --auto to import/update TLDs automatically.\n";
        echo "    Use --dry-run with --auto to preview counts without writing.\n";
        exit(0);
    }

    echo "\n🤖 Auto mode: syncing TLDs into FOSSBilling (update existing: " . ($updateExisting ? 'yes' : 'no') . ", dry-run: " . ($dryRun ? 'yes' : 'no') . ")\n";
    $summary = $importer->sync($updateExisting, $dryRun, $prepared);

    echo "📊 Result:\n";
    echo " - Created : {$summary['created']}\n";
    echo " - Updated : {$summary['updated']}\n";
    echo " - Skipped : {$summary['skipped']}\n";
    echo " - Total   : {$summary['total']}\n";
    echo " - Currency: {$summary['default_currency']} (source {$summary['source_currency']})\n";
    echo $dryRun ? "✔️  Dry-run only. No changes were written.\n" : "🎉 Import complete.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
