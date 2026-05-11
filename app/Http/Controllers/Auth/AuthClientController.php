<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\Client;
use App\Models\SettingEmail;
use App\Models\VerificationCode;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthClientController extends Controller
{
    /**
     * Verifica se o usuário existe pelo WhatsApp, Nome e Email
     */
    public function checkUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'whatsapp' => 'required|string',
                'fullName' => 'required|string',
                'email' => 'required|email'  
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Limpa o WhatsApp enviado (remove formatação)
            $whatsappEnviado = preg_replace('/\D/', '', $request->whatsapp);
            $fullName = trim($request->fullName);
            $email = trim($request->email);

            \Log::info('CheckUser - Buscando cliente:', [
                'whatsapp_limpo' => $whatsappEnviado,
                'nome' => $fullName,
                'email' => $email
            ]);

            // Busca todos os clientes ativos
            $clients = Client::where('active', 1)->get();
            
            $client = null;
            
            // Percorre os clientes para comparar o telefone sem formatação
            foreach ($clients as $c) {
                $telefoneBanco = preg_replace('/\D/', '', $c->phone);
                if ($telefoneBanco === $whatsappEnviado) {
                    $client = $c;
                    break;
                }
            }

            if ($client) {
                \Log::info('Cliente encontrado:', [
                    'id' => $client->id,
                    'name' => $client->name,
                    'phone' => $client->phone,
                    'email_banco' => $client->email,
                    'email_enviado' => $email
                ]);

                // Verifica se o nome é igual (case insensitive)
                $nomeCorreto = strtolower(trim($client->name)) === strtolower($fullName);
                
                // 🔥 Verifica se o email é igual (case insensitive)
                $emailCorreto = strtolower(trim($client->email)) === strtolower($email);

                if ($nomeCorreto && $emailCorreto) {
                    return response()->json([
                        'success' => true,
                        'exists' => true,
                        'client_id' => $client->id
                    ]);
                } else {
                    $mensagem = '';
                    if (!$nomeCorreto && !$emailCorreto) {
                        $mensagem = 'Telefone encontrado mas nome e email não conferem';
                    } elseif (!$nomeCorreto) {
                        $mensagem = 'Telefone encontrado mas nome não confere';
                    } elseif (!$emailCorreto) {
                        $mensagem = 'Telefone encontrado mas email não confere';
                    }
                    
                    \Log::warning('Dados não conferem:', [
                        'nome_banco' => $client->name,
                        'nome_enviado' => $fullName,
                        'email_banco' => $client->email,
                        'email_enviado' => $email
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'exists' => false,
                        'message' => $mensagem
                    ]);
                }
            }

            \Log::info('Nenhum cliente encontrado para o WhatsApp: ' . $whatsappEnviado);

            return response()->json([
                'success' => true,
                'exists' => false
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro no checkUser: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Valida e autentica usuário existente (com email)
     */
    public function validateUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'whatsapp' => 'required|string',
                'fullName' => 'required|string',
                'email' => 'required|email'  
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $whatsappEnviado = preg_replace('/\D/', '', $request->whatsapp);
            $fullName = trim($request->fullName);
            $email = trim($request->email);

            \Log::info('ValidateUser - Buscando cliente:', [
                'whatsapp_limpo' => $whatsappEnviado,
                'nome' => $fullName,
                'email' => $email
            ]);

            // Busca todos os clientes ativos
            $clients = Client::where('active', 1)->get();
            
            $client = null;
            
            // Percorre os clientes para comparar o telefone sem formatação
            foreach ($clients as $c) {
                $telefoneBanco = preg_replace('/\D/', '', $c->phone);
                if ($telefoneBanco === $whatsappEnviado) {
                    $client = $c;
                    break;
                }
            }

            if (!$client) {
                \Log::warning('Cliente não encontrado para validação');
                return response()->json([
                    'success' => false,
                    'exists' => false,
                    'message' => 'Cliente não encontrado'
                ]);
            }

            // Verifica o nome (case insensitive)
            $nomeCorreto = strtolower(trim($client->name)) === strtolower($fullName);
            
            // 🔥 Verifica o email (case insensitive)
            $emailCorreto = strtolower(trim($client->email)) === strtolower($email);

            if (!$nomeCorreto && !$emailCorreto) {
                return response()->json([
                    'success' => false,
                    'exists' => false,
                    'message' => 'Nome e email não conferem com o cadastro'
                ]);
            }
            
            if (!$nomeCorreto) {
                return response()->json([
                    'success' => false,
                    'exists' => false,
                    'message' => 'Nome não confere com o cadastro'
                ]);
            }
            
            if (!$emailCorreto) {
                return response()->json([
                    'success' => false,
                    'exists' => false,
                    'message' => 'Email não confere com o cadastro'
                ]);
            }

            // Autentica o cliente
            Auth::guard('client')->login($client);
            $request->session()->regenerate();

            \Log::info('Cliente autenticado com sucesso:', [
                'id' => $client->id,
                'email' => $client->email
            ]);

            return response()->json([
                'success' => true,
                'exists' => true,
                'message' => 'Autenticação realizada com sucesso',
                'client' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'whatsapp' => $client->phone,
                    'path_image' => $client->path_image ?? null,
                    'isLogged' => true
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro no validateUser: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao autenticar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Registro de novo cliente (salva sem formatação)
     */
    public function identifyRegister(Request $request){
        try {
            $rules = [
                'whatsapp' => 'required|string|unique:clients,phone',
                'fullName' => 'required|string|min:3',
                'email' => 'nullable|email|unique:clients,email',
                'deliveryMethod' => 'required|array',
                'deliveryMethod.value' => 'required|in:delivery,pickup,local',
                'paymentMethod' => 'required|in:card,pix,cash',
            ];
            
            if ($request->deliveryMethod['value'] === 'delivery') {
                $rules['selectedAddress'] = 'required|array';
            } else {
                $rules['selectedAddress'] = 'nullable|array';
            }
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $whatsapp = preg_replace('/\D/', '', $request->whatsapp);
            
            // Cria o cliente
            $client = Client::create([
                'name' => $request->fullName,
                'email' => $request->email ?? $whatsapp . '@temp.com',
                'phone' => $whatsapp,
                'password' => Hash::make(substr($whatsapp, -6)),
                'active' => 1
            ]);

            $addressId = null;
            
            // Só salva o endereço se for delivery
            if ($request->deliveryMethod['value'] === 'delivery' && $request->has('selectedAddress') && $request->selectedAddress) {
                $address = $request->selectedAddress;
                
                $clientAddress = \App\Models\ClientAddress::create([
                    'client_id' => $client->id,
                    'nickname' => $address['nickname'] ?? 'Principal',
                    'cep' => preg_replace('/\D/', '', $address['cep']),
                    'street' => $address['street'],
                    'number' => $address['number'],
                    'complement' => $address['complement'] ?? null,
                    'neighborhood' => $address['neighborhood'],
                    'city' => $address['city'],
                    'state' => $address['state'],
                    'reference' => $address['reference'] ?? null,
                    'instructions' => $address['instructions'] ?? null,
                    'primary' => 1,
                    'active' => 1
                ]);
                
                // 🔥 PEGA O ID DO ENDEREÇO CRIADO
                $addressId = $clientAddress->id;
            }
            
            // 🔥 SALVA O selected_address_id NO CLIENTE
            $client->delivery_method = json_encode($request->deliveryMethod);
            $client->payment_method = $request->paymentMethod;
            $client->selected_address_id = $addressId; // 🔥 AQUI É ONDE O ID É SALVO
            $client->save();

            \Log::info('Novo cliente registrado:', [
                'id' => $client->id,
                'selected_address_id' => $addressId,
                'delivery_method' => $request->deliveryMethod['value'],
                'payment_method' => $request->paymentMethod
            ]);

            Auth::guard('client')->login($client);
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Cliente registrado com sucesso',
                'client' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'whatsapp' => $client->phone,
                    'path_image' => $client->path_image ?? null,
                    'isLogged' => true,
                    'delivery_method' => $client->delivery_method,
                    'payment_method' => $client->payment_method,
                    'selected_address_id' => $addressId // 🔥 RETORNA O ID
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro no identifyRegister: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registrar cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Formata número de telefone para o padrão (XX) XXXXX-XXXX
     */
    private function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);
        
        if (strlen($phone) === 11) {
            return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7, 4);
        } elseif (strlen($phone) === 10) {
            return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6, 4);
        }
        
        return $phone;
    }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso'
        ]);
    }

    public function getClientData(Request $request)
    {
        try {
            $client = Auth::guard('client')->user();
            
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente não autenticado'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'client' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'phone' => $client->phone,
                    'active' => $client->active,
                    'created_at' => $client->created_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar dados do cliente'
            ], 500);
        }
    }

        public function updateDeliveryMethod(Request $request)
    {
        try {
            $client = Auth::guard('client')->user();
            
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente não autenticado'
                ], 401);
            }
            
            $validator = Validator::make($request->all(), [
                'value' => 'required|in:delivery,pickup,local',
                'label' => 'required|string',
                'timeEstimate' => 'nullable|string'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Salvar no campo delivery_method (certifique-se que existe na tabela clients)
            $client->delivery_method = json_encode($request->all());
            $client->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Método de entrega atualizado',
                'delivery_method' => $request->all()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar método de entrega: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar método de entrega'
            ], 500);
        }
    }
    
    /**
     * Atualiza o método de pagamento do cliente
     */
    public function updatePaymentMethod(Request $request)
    {
        try {
            $client = Auth::guard('client')->user();
            
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente não autenticado'
                ], 401);
            }
            
            $validator = Validator::make($request->all(), [
                'method' => 'required|in:card,pix,cash'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Salvar no campo payment_method (certifique-se que existe na tabela clients)
            $client->payment_method = $request->method;
            $client->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Método de pagamento atualizado',
                'payment_method' => $request->method
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar método de pagamento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar método de pagamento'
            ], 500);
        }
    }
    
    /**
     * Busca cliente pelo WhatsApp (para login existente)
     */
    public function findByWhatsapp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'whatsapp' => 'required|string'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp é obrigatório'
                ], 422);
            }
            
            $whatsapp = preg_replace('/\D/', '', $request->whatsapp);
            
            // Busca cliente pelo telefone
            $clients = Client::where('active', 1)->get();
            $client = null;
            
            foreach ($clients as $c) {
                $telefoneBanco = preg_replace('/\D/', '', $c->phone);
                if ($telefoneBanco === $whatsapp) {
                    $client = $c;
                    break;
                }
            }
            
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'exists' => false,
                    'message' => 'Cliente não encontrado'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'exists' => true,
                'client' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'phone' => $client->phone,
                    'path_image' => $client->path_image ?? null,
                    'delivery_method' => $client->delivery_method ? json_decode($client->delivery_method, true) : null,
                    'payment_method' => $client->payment_method ?? null,
                    'selected_address_id' => $client->selected_address_id ?? null
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar cliente por WhatsApp: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar cliente'
            ], 500);
        }
    }

    /**
     * Busca dados completos do cliente incluindo endereços e métodos salvos
     */
    public function getCompleteClientData(Request $request)
    {
        try {
            $client = Auth::guard('client')->user();
            
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente não autenticado'
                ], 401);
            }
            
            // Buscar endereços do cliente
            $addresses = \App\Models\ClientAddress::where('client_id', $client->id)
                ->where('active', 1)
                ->get();
            
            // Buscar endereço selecionado
            $selectedAddress = null;
            if ($client->selected_address_id) {
                $selectedAddress = $addresses->firstWhere('id', $client->selected_address_id);
            }
            
            // Se não tem endereço selecionado mas tem endereços, pega o principal
            if (!$selectedAddress && $addresses->count() > 0) {
                $selectedAddress = $addresses->firstWhere('primary', 1) ?? $addresses->first();
            }
            
            return response()->json([
                'success' => true,
                'client' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'phone' => $client->phone,
                    'delivery_method' => $client->delivery_method ? json_decode($client->delivery_method, true) : null,
                    'payment_method' => $client->payment_method ?? null,
                    'selected_address_id' => $client->selected_address_id,
                    'selected_address' => $selectedAddress,
                    'addresses' => $addresses
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar dados completos do cliente: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar dados do cliente: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Atualiza o endereço selecionado pelo cliente
     */
    public function updateSelectedAddress(Request $request)
    {
        try {
            $client = Auth::guard('client')->user();
            
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente não autenticado'
                ], 401);
            }
            
            $validator = Validator::make($request->all(), [
                'address_id' => 'required|integer|exists:client_addresses,id'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // 🔥 SALVA O ID DO ENDEREÇO SELECIONADO
            $client->selected_address_id = $request->address_id;
            $client->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Endereço selecionado atualizado',
                'selected_address_id' => $request->address_id
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar endereço selecionado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar endereço selecionado'
            ], 500);
        }
    }

    /**
     * Envia código de verificação para o email
     */
    public function sendVerificationCode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'whatsapp' => 'required|string',
                'fullName' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $email = trim($request->email);
            $whatsapp = preg_replace('/\D/', '', $request->whatsapp);
            $fullName = trim($request->fullName);

            // 🔥 VERIFICAR NA MODEL CLIENT (NÃO NO VERIFICATIONCODE)
            $existingClient = Client::where('phone', $whatsapp)->first();
            
            if ($existingClient) {
                // Verificar se os dados conferem com o cadastro do cliente
                $emailCorreto = ($existingClient->email === $email);
                $nomeCorreto = ($existingClient->name === $fullName);
                            
                if (!$emailCorreto || !$nomeCorreto) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Dados incorretos. Este número de WhatsApp já está cadastrado com outro e-mail ou nome.'
                    ], 409);
                }
            }

            // Se não existe cliente OU cliente existe com dados corretos, prossegue com o código de verificação
            // Verificar se já existe um código válido não usado
            $existingCode = VerificationCode::where('email', $email)
                ->where('used', false)
                ->where('expires_at', '>', now())
                ->first();

            if ($existingCode) {
                $code = $existingCode->code;
                $token = $existingCode->token;
            } else {
                $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $token = Str::random(64);

                // Remover códigos antigos expirados
                VerificationCode::where('email', $email)
                    ->where('expires_at', '<', now())
                    ->delete();

                // Criar novo registro de código
                VerificationCode::create([
                    'email' => $email,
                    'whatsapp' => $whatsapp,
                    'full_name' => $fullName,
                    'code' => $code,
                    'token' => $token,
                    'expires_at' => now()->addMinutes(10),
                    'used' => false
                ]);
            }

            // Enviar email
            $emailSettings = SettingEmail::first();
            $emailService = new EmailService();
            $emailService->configureAndSend($emailSettings, $request->only('email'));
            
            try {
                Mail::to($email)->send(new VerificationCodeMail($code, $fullName));
            } catch (\Exception $e) {
                \Log::error('Erro ao enviar email: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao enviar email. Verifique se o endereço é válido.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Código enviado com sucesso!',
                'token' => $token
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao enviar código: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar código: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifica o código digitado pelo usuário
     */
    public function verifyCode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'code' => 'required|string|size:6',
                'token' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $email = $request->email;
            $code = $request->code;
            $token = $request->token;

            // Buscar o código válido
            $query = VerificationCode::where('email', $email)
                ->where('code', $code)
                ->where('used', false)
                ->where('expires_at', '>', now());

            if ($token) {
                $query->where('token', $token);
            }

            $verificationCode = $query->first();

            if (!$verificationCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código inválido ou expirado. Solicite um novo código.'
                ], 400);
            }

            // Marcar como usado
            $verificationCode->used = true;
            $verificationCode->save();

            // Verificar se o usuário já existe
            $whatsapp = $verificationCode->whatsapp;
            $fullName = $verificationCode->full_name;

            // Buscar cliente pelo WhatsApp
            $clients = Client::where('active', 1)->get();
            $client = null;

            foreach ($clients as $c) {
                $telefoneBanco = preg_replace('/\D/', '', $c->phone);
                if ($telefoneBanco === $whatsapp) {
                    $client = $c;
                    break;
                }
            }

            if ($client) {
                // Verificar se o nome confere
                if (strtolower(trim($client->name)) === strtolower($fullName)) {
                    // Usuário existente - já pode fazer login
                    return response()->json([
                        'success' => true,
                        'message' => 'Código verificado com sucesso!',
                        'isExistingUser' => true,
                        'client' => [
                            'id' => $client->id,
                            'name' => $client->name,
                            'email' => $client->email,
                            'whatsapp' => $client->phone,
                            'delivery_method' => $client->delivery_method ? json_decode($client->delivery_method, true) : null,
                            'payment_method' => $client->payment_method ?? null,
                            'selected_address_id' => $client->selected_address_id ?? null
                        ]
                    ]);
                }
            }

            // Usuário novo ou nome não confere
            return response()->json([
                'success' => true,
                'message' => 'Código verificado com sucesso!',
                'isExistingUser' => false
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao verificar código: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar código: ' . $e->getMessage()
            ], 500);
        }
    }
}

