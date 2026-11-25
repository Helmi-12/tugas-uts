<?php
/**
 * Kalkulator Kargo Volumetrik & Logistik Mebel
 * Berdasarkan Formula pada Dokumen Analisis Rancang Bangun Perangkat Lunak E-Commerce Mebel:
 * 1. Berat Volumetrik Darat/Laut = (P x L x T) / 4000
 * 2. Berat Volumetrik Udara = (P x L x T) / 6000
 * 3. Kubikasi (m3) = (P x L x T) / 1.000.000
 * 4. Charged Weight = max(Berat Aktual, Berat Volumetrik)
 */

class VolumetricCargo {
    public const DIVISOR_LAND_SEA = 4000;
    public const DIVISOR_AIR = 6000;
    public const CUBIC_DIVISOR = 1000000;

    /**
     * Hitung berat volumetrik jalur darat/laut (kg)
     */
    public static function calculateLandSeaVolumetric(float $p, float $l, float $t): float {
        return round(($p * $l * $t) / self::DIVISOR_LAND_SEA, 2);
    }

    /**
     * Hitung berat volumetrik jalur udara (kg)
     */
    public static function calculateAirVolumetric(float $p, float $l, float $t): float {
        return round(($p * $l * $t) / self::DIVISOR_AIR, 2);
    }

    /**
     * Hitung kubikasi dalam satuan meter kubik (m3)
     */
    public static function calculateCubicMeters(float $p, float $l, float $t): float {
        return round(($p * $l * $t) / self::CUBIC_DIVISOR, 4);
    }

    /**
     * Hitung Berat Terpakai (Charged Weight)
     */
    public static function calculateChargedWeight(float $actualWeight, float $volumetricWeight): float {
        return max($actualWeight, $volumetricWeight);
    }

    /**
     * Dapatkan daftar kota tujuan kargo beserta zona tarif per kg
     */
    public static function getDestinationCities(): array {
        return [
            ['name' => 'Surabaya / Sidoarjo / Gresik (Lokal Jatim)', 'base_rate' => 2500, 'etd' => '1-2 Hari'],
            ['name' => 'Malang / Pasuruan / Mojokerto', 'base_rate' => 3000, 'etd' => '1-2 Hari'],
            ['name' => 'Semarang / Solo / DI Yogyakarta', 'base_rate' => 4500, 'etd' => '2-3 Hari'],
            ['name' => 'DKI Jakarta & Bodetabek', 'base_rate' => 5000, 'etd' => '2-4 Hari'],
            ['name' => 'Bandung & Jawa Barat', 'base_rate' => 5500, 'etd' => '2-4 Hari'],
            ['name' => 'Denpasar & Bali', 'base_rate' => 6000, 'etd' => '3-5 Hari'],
            ['name' => 'Bandar Lampung / Palembang', 'base_rate' => 8500, 'etd' => '4-6 Hari'],
            ['name' => 'Medan / Pekanbaru / Padang', 'base_rate' => 11000, 'etd' => '5-7 Hari'],
            ['name' => 'Makassar / Banjarmasin / Balikpapan', 'base_rate' => 12500, 'etd' => '5-8 Hari'],
        ];
    }

    /**
     * Hitung simulasi ongkos kirim berbagai kurir kargo
     */
    public static function calculateShippingOptions(float $p, float $l, float $t, float $actualWeight, string $cityName = ''): array {
        $volLand = self::calculateLandSeaVolumetric($p, $l, $t);
        $volAir = self::calculateAirVolumetric($p, $l, $t);
        $cubic = self::calculateCubicMeters($p, $l, $t);
        $chargedWeight = self::calculateChargedWeight($actualWeight, $volLand);

        // Cari base rate kota
        $cities = self::getDestinationCities();
        $selectedCity = $cities[0];
        foreach ($cities as $c) {
            if ($c['name'] === $cityName) {
                $selectedCity = $c;
                break;
            }
        }

        $baseRate = $selectedCity['base_rate'];

        // Daftar kurir kargo spesialis furnitur & mebel
        $couriers = [
            [
                'code' => 'JNE-JTR',
                'name' => 'JNE Trucking (JTR)',
                'service' => 'Kargo Darat Standar',
                'min_weight' => 10,
                'multiplier' => 1.0,
                'wooden_pack_fee' => 75000,
                'etd' => $selectedCity['etd'],
            ],
            [
                'code' => 'INDAH-CARGO',
                'name' => 'Indah Logistik Cargo',
                'service' => 'Trucking Furniture Express',
                'min_weight' => 20,
                'multiplier' => 0.9,
                'wooden_pack_fee' => 60000,
                'etd' => $selectedCity['etd'],
            ],
            [
                'code' => 'BARAKA',
                'name' => 'Baraka Sarana Tama (BST)',
                'service' => 'Kargo Mebel Spesialis',
                'min_weight' => 15,
                'multiplier' => 0.85,
                'wooden_pack_fee' => 50000,
                'etd' => $selectedCity['etd'],
            ],
            [
                'code' => 'DAKOTA',
                'name' => 'Dakota Cargo',
                'service' => 'Heavy Cargo Eco',
                'min_weight' => 20,
                'multiplier' => 0.88,
                'wooden_pack_fee' => 65000,
                'etd' => $selectedCity['etd'],
            ],
        ];

        $results = [];
        foreach ($couriers as $courier) {
            $effectiveWeight = max($chargedWeight, $courier['min_weight']);
            $freightCost = round($effectiveWeight * $baseRate * $courier['multiplier']);
            $totalCost = $freightCost + $courier['wooden_pack_fee'];

            $results[] = [
                'code' => $courier['code'],
                'name' => $courier['name'],
                'service' => $courier['service'],
                'min_weight' => $courier['min_weight'],
                'effective_weight' => $effectiveWeight,
                'freight_cost' => $freightCost,
                'wooden_pack_fee' => $courier['wooden_pack_fee'],
                'total_cost' => $totalCost,
                'etd' => $courier['etd'],
            ];
        }

        return [
            'dimensions' => [
                'length' => $p,
                'width' => $l,
                'height' => $t,
            ],
            'actual_weight' => $actualWeight,
            'volumetric_land_sea' => $volLand,
            'volumetric_air' => $volAir,
            'cubic_meters' => $cubic,
            'charged_weight' => $chargedWeight,
            'city' => $selectedCity['name'],
            'couriers' => $results
        ];
    }
}

