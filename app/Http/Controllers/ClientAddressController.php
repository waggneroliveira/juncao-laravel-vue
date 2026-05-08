<?php

namespace App\Http\Controllers;

use App\Models\ClientAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientAddressController extends Controller
{

    public function index()
    {
        $client = Auth::guard('client')->user();
        
        $addresses = ClientAddress::where('client_id', $client->id)
            ->where('active', 1)
            ->orderBy('primary', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'addresses' => $addresses
        ]);
    }
    
    public function store(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $validator = Validator::make($request->all(), [
            'nickname' => 'required|string|max:50',
            'cep' => 'required|string|max:9',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'reference' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'primary' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Se for primary, remove primary dos outros
        if ($request->primary) {
            ClientAddress::where('client_id', $client->id)->update(['primary' => false]);
        }
        
        $address = ClientAddress::create([
            'client_id' => $client->id,
            'nickname' => $request->nickname,
            'cep' => preg_replace('/\D/', '', $request->cep),
            'street' => $request->street,
            'number' => $request->number,
            'complement' => $request->complement,
            'neighborhood' => $request->neighborhood,
            'city' => $request->city,
            'state' => $request->state,
            'reference' => $request->reference,
            'instructions' => $request->instructions,
            'primary' => $request->primary ?? false,
            'active' => 1
        ]);
        
        return response()->json([
            'success' => true,
            'address' => $address
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $client = Auth::guard('client')->user();
        
        $address = ClientAddress::where('id', $id)
            ->where('client_id', $client->id)
            ->firstOrFail();
        
        $validator = Validator::make($request->all(), [
            'nickname' => 'required|string|max:50',
            'cep' => 'required|string|max:9',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'reference' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'primary' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Se for primary, remove primary dos outros
        if ($request->primary) {
            ClientAddress::where('client_id', $client->id)
                ->where('id', '!=', $id)
                ->update(['primary' => false]);
        }
        
        $address->update([
            'nickname' => $request->nickname,
            'cep' => preg_replace('/\D/', '', $request->cep),
            'street' => $request->street,
            'number' => $request->number,
            'complement' => $request->complement,
            'neighborhood' => $request->neighborhood,
            'city' => $request->city,
            'state' => $request->state,
            'reference' => $request->reference,
            'instructions' => $request->instructions,
            'primary' => $request->primary ?? false
        ]);
        
        return response()->json([
            'success' => true,
            'address' => $address
        ]);
    }
    
    // public function destroy($id)
    // {
    //     $client = Auth::guard('client')->user();
        
    //     $address = ClientAddress::where('id', $id)
    //         ->where('client_id', $client->id)
    //         ->firstOrFail();
        
    //     $address->update(['active' => 0]);
        
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Endereço removido com sucesso'
    //     ]);
    // }
    // App/Http/Controllers/ClientAddressController.php

    public function destroy($id)
    {
        try {
            $client = Auth::guard('client')->user();
            
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não autenticado'
                ], 401);
            }
            
            $address = ClientAddress::where('client_id', $client->id)
                ->where('id', $id)
                ->first();
            
            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Endereço não encontrado'
                ], 404);
            }
            
            // 🔥 DELETAR FISICAMENTE (ou soft delete)
            $address->delete();
            
            // Se o endereço deletado era o selecionado, limpar o selected_address_id do cliente
            if ($client->selected_address_id == $id) {
                $client->selected_address_id = null;
                $client->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Endereço removido com sucesso'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao deletar endereço: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar endereço'
            ], 500);
        }
    }
    
    public function setPrimary($id)
    {
        $client = Auth::guard('client')->user();
        
        // Remove primary de todos
        ClientAddress::where('client_id', $client->id)->update(['primary' => false]);
        
        // Seta o novo primary
        $address = ClientAddress::where('id', $id)
            ->where('client_id', $client->id)
            ->firstOrFail();
        
        $address->update(['primary' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'Endereço principal definido com sucesso'
        ]);
    }
}