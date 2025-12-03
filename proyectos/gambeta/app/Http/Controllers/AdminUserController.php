<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Validation rules for user management.
     */
    private function rules(?int $ignoreId = null, bool $isUpdate = false): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($ignoreId)],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:8', 'max:255'],
            'activo' => ['nullable', 'boolean'],
        ];
    }

    private function messages(): array
    {
        return [
            'email.required' => 'Ingresa un correo electrónico válido.',
            'email.email' => 'El formato del correo es inválido.',
            'email.unique' => 'Este correo ya está registrado, elige uno diferente.',
            'password.required' => 'Ingresa una contraseña segura de al menos 8 caracteres.',
            'password.string' => 'La contraseña debe ser texto y puede incluir letras, números y símbolos.',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres.',
            'password.max' => 'La contraseña no puede superar los 255 caracteres.',
        ];
    }

    private function validationFeedbackMessage($validator): string
    {
        $errors = $validator->errors();

        if ($errors->has('email')) {
            return 'El correo ingresado ya existe o es inválido. Ajusta ese campo y vuelve a intentarlo.';
        }

        if ($errors->has('password')) {
            return 'Corrige la contraseña: debe tener al menos 8 caracteres.';
        }

        return 'Corrige los campos indicados antes de continuar.';
    }

    private function fillUser(User $user, array $validated, Request $request): void
    {
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = (int) $validated['role_id'];
        $user->activo = $request->boolean('activo');

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }
    }

    private function syncAssignedRole(User $user): void
    {
        $role = Role::find($user->role_id);

        if ($role) {
            $user->syncRoles([$role->name]);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            $modalMessage = $this->validationFeedbackMessage($validator);

            return back()
                ->withInput()
                ->withErrors($validator, 'crearUsuario')
                ->with('error', $modalMessage);
        }

        $validated = $validator->validated();

        $user = new User();
        $this->fillUser($user, $validated, $request);
        $user->save();
        $this->syncAssignedRole($user);

        return redirect()->route('admin.index')->with('status', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules($usuario->id, true),
            $this->messages()
        );

        if ($validator->fails()) {
            $modalMessage = $this->validationFeedbackMessage($validator);

            return back()
                ->withInput()
                ->with('editarUsuarioId', $usuario->id)
                ->withErrors($validator, 'editarUsuario')
                ->with('error', $modalMessage);
        }

        $validated = $validator->validated();

        $this->fillUser($usuario, $validated, $request);
        $usuario->save();
        $this->syncAssignedRole($usuario);

        return redirect()->route('admin.index')->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy(Request $request, User $usuario): RedirectResponse
    {
        if ($request->user()?->is($usuario)) {
            return redirect()->route('admin.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();

        return redirect()->route('admin.index')->with('status', 'Usuario eliminado correctamente.');
    }
}
