<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View{
        $search=trim((string)$request->input('q',''));
        $role=$request->input('role','');
        $users=User::query()
            ->when($search,fn($q)=>$q->where(fn($q)=>$q->whereRaw('LOWER(name) LIKE ?',['%'.mb_strtolower($search,'UTF-8').'%'])->orWhereRaw('LOWER(email) LIKE ?',['%'.mb_strtolower($search,'UTF-8').'%'])))
            ->when($role,fn($q)=>$q->where('role',$role))
            ->orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'gestor' THEN 2 ELSE 3 END")
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $roles=$this->roles();
        $counts=[
            'total'=>User::count(),
            'admin'=>User::where('role',User::ROLE_ADMIN)->count(),
            'gestor'=>User::where('role',User::ROLE_GESTOR)->count(),
            'estudiante'=>User::where('role',User::ROLE_ESTUDIANTE)->count(),
        ];

        return view('admin.users.index',compact('users','roles','counts','search','role'));
    }

    public function create(): View{
        $roles=$this->roles();
        $user=new User(['role'=>User::ROLE_ESTUDIANTE]);
        return view('admin.users.create',compact('user','roles'));
    }

    public function store(Request $request): RedirectResponse{
        $validated=$request->validate([
            'name'=>['required','string','max:255'],
            'email'=>['required','email','max:255','unique:users,email'],
            'role'=>['required',Rule::in(array_keys($this->roles()))],
            'password'=>['required','string','min:8','confirmed'],
        ],[
            'name.required'=>'El nombre es obligatorio.',
            'email.required'=>'El correo es obligatorio.',
            'email.email'=>'Ingresa un correo válido.',
            'email.unique'=>'Este correo ya está registrado.',
            'role.required'=>'Selecciona un rol.',
            'password.required'=>'La contraseña es obligatoria.',
            'password.min'=>'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'=>'La confirmación de contraseña no coincide.',
        ]);

        User::create($validated);
        return redirect()->route('admin.users.index')->with('status','Usuario creado correctamente.');
    }

    public function edit(User $user): View{
        $roles=$this->roles();
        return view('admin.users.edit',compact('user','roles'));
    }

    public function update(Request $request,User $user): RedirectResponse{
        $validated=$request->validate([
            'name'=>['required','string','max:255'],
            'email'=>['required','email','max:255',Rule::unique('users','email')->ignore($user->id)],
            'role'=>['required',Rule::in(array_keys($this->roles()))],
            'password'=>['nullable','string','min:8','confirmed'],
        ],[
            'name.required'=>'El nombre es obligatorio.',
            'email.required'=>'El correo es obligatorio.',
            'email.email'=>'Ingresa un correo válido.',
            'email.unique'=>'Este correo ya está registrado.',
            'role.required'=>'Selecciona un rol.',
            'password.min'=>'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'=>'La confirmación de contraseña no coincide.',
        ]);

        if($user->id===auth()->id()&&$validated['role']!==User::ROLE_ADMIN){
            return back()->withErrors(['role'=>'No puedes quitarte tu propio rol de administrador.'])->withInput();
        }

        if($user->role===User::ROLE_ADMIN&&$validated['role']!==User::ROLE_ADMIN&&User::where('role',User::ROLE_ADMIN)->count()<=1){
            return back()->withErrors(['role'=>'Debe existir al menos un administrador.'])->withInput();
        }

        if(empty($validated['password']))unset($validated['password']);
        $user->update($validated);

        return redirect()->route('admin.users.index')->with('status','Usuario actualizado correctamente.');
    }

    public function destroy(User $user): RedirectResponse{
        if($user->id===auth()->id())return back()->with('status','No puedes eliminar tu propio usuario.');
        if($user->role===User::ROLE_ADMIN&&User::where('role',User::ROLE_ADMIN)->count()<=1)return back()->with('status','No puedes eliminar el último administrador.');

        $user->delete();
        return redirect()->route('admin.users.index')->with('status','Usuario eliminado correctamente.');
    }

    private function roles(): array{
        return [
            User::ROLE_ADMIN=>'Administrador',
            User::ROLE_GESTOR=>'Gestor de contenido',
            User::ROLE_ESTUDIANTE=>'Estudiante',
        ];
    }
}