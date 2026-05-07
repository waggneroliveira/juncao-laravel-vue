<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cliente 1: João Silva
        DB::table('clients')->insert([
            'id' => 1,
            'name' => 'João Carlos Silva',
            'email' => 'joao.silva@email.com',
            'phone' => '(11) 98765-4321',
            'active' => 1,
            'password' => Hash::make('12345678'),
            'path_image' => 'clients/joao_silva.jpg',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Cliente 2: Maria Oliveira
        DB::table('clients')->insert([
            'id' => 2,
            'name' => 'Maria Aparecida Oliveira',
            'email' => 'maria.oliveira@email.com',
            'phone' => '(11) 91234-5678',
            'active' => 1,
            'password' => Hash::make('12345678'),
            'path_image' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Cliente 3: Pedro Santos (inativo)
        DB::table('clients')->insert([
            'id' => 3,
            'name' => 'Pedro Henrique Santos',
            'email' => 'pedro.santos@email.com',
            'phone' => '(11) 99876-5432',
            'active' => 0,
            'password' => Hash::make('12345678'),
            'path_image' => 'clients/pedro_santos.jpg',
            'email_verified_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Cliente 4: Ana Beatriz Costa
        DB::table('clients')->insert([
            'id' => 4,
            'name' => 'Ana Beatriz Costa',
            'email' => 'ana.costa@email.com',
            'phone' => '(11) 97654-3210',
            'active' => 1,
            'password' => Hash::make('12345678'),
            'path_image' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Endereços do Cliente 1 (João)
        DB::table('client_addresses')->insert([
            [
                'client_id' => 1,
                'nickname' => 'Casa',
                'cep' => '01310-000',
                'street' => 'Avenida Paulista',
                'number' => '1000',
                'complement' => 'Apto 101',
                'neighborhood' => 'Bela Vista',
                'city' => 'São Paulo',
                'state' => 'SP',
                'reference' => 'Próximo ao metrô Trianon-Masp',
                'instructions' => 'Campainha: Silva. Deixar com porteiro.',
                'primary' => 1,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_id' => 1,
                'nickname' => 'Trabalho',
                'cep' => '04538-132',
                'street' => 'Rua Quatá',
                'number' => '300',
                'complement' => 'Sala 1505',
                'neighborhood' => 'Vila Olímpia',
                'city' => 'São Paulo',
                'state' => 'SP',
                'reference' => 'Mundo Plaza, próximo ao shopping',
                'instructions' => 'Entregar no horário comercial até 18h',
                'primary' => 0,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Endereços do Cliente 2 (Maria)
        DB::table('client_addresses')->insert([
            [
                'client_id' => 2,
                'nickname' => 'Apartamento',
                'cep' => '22030-015',
                'street' => 'Avenida Atlântica',
                'number' => '800',
                'complement' => 'Bloco B - 502',
                'neighborhood' => 'Copacabana',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'reference' => 'Em frente ao Hotel Copacabana Palace',
                'instructions' => 'Tocar interfone 502. Subir de elevador.',
                'primary' => 1,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Endereços do Cliente 4 (Ana) - cliente inativo não tem endereço ativo
        DB::table('client_addresses')->insert([
            [
                'client_id' => 4,
                'nickname' => 'Casa',
                'cep' => '30130-010',
                'street' => 'Rua da Bahia',
                'number' => '1200',
                'complement' => null,
                'neighborhood' => 'Centro',
                'city' => 'Belo Horizonte',
                'state' => 'MG',
                'reference' => 'Próximo ao Mercado Central',
                'instructions' => 'Campainha com nome "Costa"',
                'primary' => 1,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_id' => 4,
                'nickname' => 'Sítio',
                'cep' => '34000-000',
                'street' => 'Estrada do Retiro',
                'number' => 'S/N',
                'complement' => 'Sítio São João',
                'neighborhood' => 'Zona Rural',
                'city' => 'Nova Lima',
                'state' => 'MG',
                'reference' => 'Após a ponte de madeira',
                'instructions' => 'Entregar no portão branco',
                'primary' => 0,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
