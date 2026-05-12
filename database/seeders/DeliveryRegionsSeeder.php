<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryRegion;
use App\Models\DeliveryRegionZipcode;

class DeliveryRegionsSeeder extends Seeder
{
    public function run(): void
    {
        // Região 1: Lauro de Freitas - Centro e adjacências
        $region1 = DeliveryRegion::create([
            'name' => 'Lauro de Freitas - Centro',
            'delivery_fee' => 6.90,
            'estimated_time_min' => 20,
            'estimated_time_max' => 35,
            'minimum_order_value' => 15.00,
            'active' => true
        ]);
        
        // CEPs da região 1 (Lauro de Freitas - Centro)
        $zipcodesRegion1 = [
            '42700-000', // Centro
            '42700-001', // Centro
            '42700-010', // Centro
            '42700-020', // Centro
            '42700-030', // Centro
            '42702-000', // Vilas do Atlântico
            '42702-100', // Vilas do Atlântico
            '42702-200', // Vilas do Atlântico
            '42703-000', // Ipitanga
            '42703-100', // Ipitanga
        ];
        
        foreach ($zipcodesRegion1 as $zipcode) {
            DeliveryRegionZipcode::create([
                'delivery_region_id' => $region1->id,
                'zipcode_prefix' => $zipcode
            ]);
        }
        
        // Região 2: Lauro de Freitas - Área de Praia
        $region2 = DeliveryRegion::create([
            'name' => 'Lauro de Freitas - Orla e Praias',
            'delivery_fee' => 8.90,
            'estimated_time_min' => 25,
            'estimated_time_max' => 45,
            'minimum_order_value' => 20.00,
            'active' => true
        ]);
        
        // CEPs da região 2 (Praias)
        $zipcodesRegion2 = [
            '42700-100', // Praia de Ipitanga
            '42700-110', // Praia de Ipitanga
            '42700-120', // Praia de Vilas
            '42700-130', // Praia do Centro
            '42703-200', // Praia de Busca Vida
            '42703-300', // Jardim das Margaridas
        ];
        
        foreach ($zipcodesRegion2 as $zipcode) {
            DeliveryRegionZipcode::create([
                'delivery_region_id' => $region2->id,
                'zipcode_prefix' => $zipcode
            ]);
        }
        
        // Região 3: Lauro de Freitas - Região de Itinga
        $region3 = DeliveryRegion::create([
            'name' => 'Lauro de Freitas - Itinga',
            'delivery_fee' => 7.90,
            'estimated_time_min' => 30,
            'estimated_time_max' => 50,
            'minimum_order_value' => 18.00,
            'active' => true
        ]);
        
        // CEPs da região 3 (Itinga)
        $zipcodesRegion3 = [
            '42704-000', // Itinga
            '42704-100', // Itinga
            '42704-200', // Itinga
            '42704-300', // Portão
            '42705-000', // Vida Nova
        ];
        
        foreach ($zipcodesRegion3 as $zipcode) {
            DeliveryRegionZipcode::create([
                'delivery_region_id' => $region3->id,
                'zipcode_prefix' => $zipcode
            ]);
        }
        
        // Região 4: Salvador - Pituba e arredores
        $region4 = DeliveryRegion::create([
            'name' => 'Salvador - Pituba/Caminho das Árvores',
            'delivery_fee' => 12.90,
            'estimated_time_min' => 35,
            'estimated_time_max' => 60,
            'minimum_order_value' => 25.00,
            'active' => true
        ]);
        
        // CEPs da região 4 (Pituba)
        $zipcodesRegion4 = [
            '41810-000', // Pituba
            '41810-010', // Pituba
            '41810-020', // Pituba
            '41815-000', // Caminho das Árvores
            '41815-100', // Caminho das Árvores
            '41820-000', // Costa Azul
        ];
        
        foreach ($zipcodesRegion4 as $zipcode) {
            DeliveryRegionZipcode::create([
                'delivery_region_id' => $region4->id,
                'zipcode_prefix' => $zipcode
            ]);
        }
        
        // Região 5: Salvador - Paralela
        $region5 = DeliveryRegion::create([
            'name' => 'Salvador - Paralela/Imbuí',
            'delivery_fee' => 11.90,
            'estimated_time_min' => 30,
            'estimated_time_max' => 55,
            'minimum_order_value' => 22.00,
            'active' => true
        ]);
        
        // CEPs da região 5 (Paralela)
        $zipcodesRegion5 = [
            '41770-000', // Paralela
            '41770-100', // Paralela
            '41770-200', // Alphaville
            '41770-300', // Stiep
            '41770-400', // Imbuí
        ];
        
        foreach ($zipcodesRegion5 as $zipcode) {
            DeliveryRegionZipcode::create([
                'delivery_region_id' => $region5->id,
                'zipcode_prefix' => $zipcode
            ]);
        }
        
        // Região 6: Vilas do Atlântico (área nobre)
        $region6 = DeliveryRegion::create([
            'name' => 'Lauro de Freitas - Vilas do Atlântico',
            'delivery_fee' => 9.90,
            'estimated_time_min' => 20,
            'estimated_time_max' => 40,
            'minimum_order_value' => 20.00,
            'active' => true
        ]);
        
        // CEPs da região 6 (Vilas)
        $zipcodesRegion6 = [
            '42702-300', // Vilas do Atlântico
            '42702-400', // Vilas do Atlântico
            '42702-500', // Vilas do Atlântico
            '42702-600', // Vilas do Atlântico
        ];
        
        foreach ($zipcodesRegion6 as $zipcode) {
            DeliveryRegionZipcode::create([
                'delivery_region_id' => $region6->id,
                'zipcode_prefix' => $zipcode
            ]);
        }
        
        // Região 7: Simões Filho (cidade vizinha)
        $region7 = DeliveryRegion::create([
            'name' => 'Simões Filho',
            'delivery_fee' => 15.90,
            'estimated_time_min' => 45,
            'estimated_time_max' => 70,
            'minimum_order_value' => 30.00,
            'active' => true
        ]);
        
        // CEPs da região 7 (Simões Filho)
        $zipcodesRegion7 = [
            '43700-000', // Centro
            '43700-100', // CIA
            '43700-200', // Ponto Central
            '43700-300', // Simões
        ];
        
        foreach ($zipcodesRegion7 as $zipcode) {
            DeliveryRegionZipcode::create([
                'delivery_region_id' => $region7->id,
                'zipcode_prefix' => $zipcode
            ]);
        }
        
        // Região 8: Camaçari (centro)
        $region8 = DeliveryRegion::create([
            'name' => 'Camaçari - Centro',
            'delivery_fee' => 18.90,
            'estimated_time_min' => 50,
            'estimated_time_max' => 80,
            'minimum_order_value' => 35.00,
            'active' => true
        ]);
        
        // CEPs da região 8 (Camaçari)
        $zipcodesRegion8 = [
            '42800-000', // Centro
            '42800-100', // Centro
            '42800-200', // Gleba A
        ];
        
        foreach ($zipcodesRegion8 as $zipcode) {
            DeliveryRegionZipcode::create([
                'delivery_region_id' => $region8->id,
                'zipcode_prefix' => $zipcode
            ]);
        }
    }
}