<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class ClientController extends Controller
{
    protected $pathUpload = 'clients/avatars/';
    public function index()
    {
        $clients = Client::all();

        return view('admin.blades.client.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email',
            'password' => 'required|string|min:8',
            'active' => 'boolean',
        ], [
            'email.unique' => 'O e-mail informado já está sendo utilizado.',
        ]);

        try {
            DB::beginTransaction();

            Client::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'active' => 1,
            ]);

            DB::commit();

            session()->flash('success', 'Cadastro realizado com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro no cadastro: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, Client $client)
    {
        $client = auth('client')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'password' => 'nullable|string|min:8',
            'path_image' => ['nullable', 'file', 'image', 'max:2048'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']); 
        }

        $manager = new ImageManager(GdDriver::class);

        if ($request->hasFile('path_image')) {
            $file = $request->file('path_image');
            $mime = $file->getMimeType();
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';

            if ($client->path_image) {
                Storage::disk('public')->delete($client->path_image);
            }

            if ($mime === 'image/svg+xml') {
                $file->storeAs($this->pathUpload, $filename, 'public');
            } else {
                $image = $manager->read($file)
                    ->resize(null, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->toWebp(quality: 95)
                    ->toString();

                Storage::disk('public')->put($this->pathUpload . $filename, $image);
            }

            $validated['path_image'] = $this->pathUpload . $filename;
        }

        if (isset($request->delete_path_image)) {
            Storage::delete(isset($client->path_image)??$client->path_image);
            $data['path_image'] = null;
        }

        $client->update($validated);

        return back()->with('success', 'Dados atualizados com sucesso!');
    }

    public function getProfile(Request $request)
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
                'profile' => [
                    'id' => $client->id,
                    'nome' => $client->name,
                    'telefone' => $client->phone,
                    'email' => $client->email,
                    'avatar' => $client->path_image ? asset($client->path_image) : null, // Retorna URL completa
                    'created_at' => $client->created_at
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar perfil: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar perfil'
            ], 500);
        }
    }
    
    public function updateProfile(Request $request)
    {
        try {
            $client = Auth::guard('client')->user();
            
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente não autenticado'
                ], 401);
            }
            
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'nome' => 'required|string|min:3|max:255',
                'telefone' => 'required|string',
                'email' => 'required|email|unique:clients,email,' . $client->id,
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Limpar telefone
            $telefoneLimpo = preg_replace('/\D/', '', $request->telefone);
            
            // Atualizar dados
            $client->name = $request->nome;
            $client->phone = $telefoneLimpo;
            $client->email = $request->email;
            $client->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Perfil atualizado com sucesso!',
                'profile' => [
                    'id' => $client->id,
                    'nome' => $client->name,
                    'telefone' => $client->phone,
                    'email' => $client->email,
                    'avatar' => $client->path_image ? asset($client->path_image) : null // URL completa
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar perfil: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar perfil: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function updateAvatar(Request $request)
    {
        try {
            $client = Auth::guard('client')->user();
            
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente não autenticado'
                ], 401);
            }
            
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'avatar' => 'required|file|image|max:5120'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Imagem inválida',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $manager = new ImageManager(new GdDriver());
            $file = $request->file('avatar');
            $filename = 'avatar_' . $client->id . '_' . time() . '.webp';
            
            // Deletar avatar antigo
            if ($client->path_image) {
                // Remove o prefixo 'storage/' se existir para deletar
                $oldPath = str_replace('storage/', '', $client->path_image);
                Storage::disk('public')->delete($oldPath);
            }
            
            // Processar imagem
            $image = $manager->read($file)
                ->cover(300, 300)
                ->toWebp(quality: 90)
                ->toString();
            
            // Salvar sem o prefixo storage no disco
            Storage::disk('public')->put($this->pathUpload . $filename, $image);
            
            // 🔥 SALVAR NO BANCO COM O PREFIXO storage/
            $client->path_image = 'storage/' . $this->pathUpload . $filename;
            $client->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Avatar atualizado com sucesso!',
                'avatar' => asset($client->path_image) // Retorna URL completa
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar avatar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar avatar: ' . $e->getMessage()
            ], 500);
        }
    }  

    public function deleteAvatar(Request $request)
    {
        try {
            $client = Auth::guard('client')->user();
            
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente não autenticado'
                ], 401);
            }
            
            if ($client->path_image) {
                $oldPath = str_replace('storage/', '', $client->path_image);
                Storage::disk('public')->delete($oldPath);
                $client->path_image = null;
                $client->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Avatar removido com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao deletar avatar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar avatar'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        Storage::delete(isset($client->path_image)??$client->path_image);
        $client->delete();
        Session::flash('success',__('dashboard.response_item_delete'));
        return redirect()->back();
    }
}