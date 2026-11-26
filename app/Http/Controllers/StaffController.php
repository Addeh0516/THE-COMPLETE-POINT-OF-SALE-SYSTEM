<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        return view('staff.index', ['staff' => User::latest()->paginate(12)]);
    }

    public function create(): View
    {
        return view('staff.form', ['staffMember' => new User, 'formTitle' => 'Add staff member', 'formAction' => route('staff.store')]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->storePublicAvatar($request->file('avatar'));
        }
        User::create($data);

        return redirect()->route('staff.index')->with('success', 'Staff account created.');
    }

    public function edit(User $staff): View
    {
        return view('staff.form', ['staffMember' => $staff, 'formTitle' => 'Edit staff member', 'formAction' => route('staff.update', $staff)]);
    }

    public function update(Request $request, User $staff): RedirectResponse
    {
        $data = $this->validated($request, $staff);
        if (! $request->filled('password')) {
            unset($data['password']);
        }
        if ($request->hasFile('avatar')) {
            $this->deleteStoredAvatar($staff->avatar);
            $data['avatar'] = $this->storePublicAvatar($request->file('avatar'));
        }
        $staff->update($data);

        return redirect()->route('staff.index')->with('success', 'Staff account updated.');
    }

    public function destroy(User $staff): RedirectResponse
    {
        if ($staff->is(Auth::user())) {
            return back()->with('error', 'You cannot delete the account currently running this shift.');
        }

        if ($staff->sales()->exists()) {
            return back()->with('error', 'This staff member has sales history, so the account is kept for auditability.');
        }

        $this->deleteStoredAvatar($staff->avatar);
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff account removed.');
    }

    private function validated(Request $request, ?User $staff = null): array
    {
        return $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($staff?->id)],
            'full_name' => ['required', 'string', 'max:100'],
            'password' => [$staff ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
    }

    private function deleteStoredAvatar(?string $avatar): void
    {
        if (! $avatar) {
            return;
        }

        if (str_starts_with($avatar, 'uploads/')) {
            @unlink(public_path($avatar));

            return;
        }

        Storage::disk('public')->delete($avatar);
    }

    private function storePublicAvatar(UploadedFile $file): string
    {
        $directory = public_path('uploads/avatars');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = $file->hashName();
        $file->move($directory, $filename);

        return 'uploads/avatars/'.$filename;
    }
}
