<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Member;
use App\Models\SiteSetting;
use App\Models\User;
use App\Notifications\AdminNewMemberRegistered;
use App\Notifications\MemberRegistrationReceived;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class MemberRegistrationController extends Controller
{
    public function create(): View
    {
        return view('member-registration.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'gender' => ['required', 'in:male,female,other'],
            'birth_date' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'address_line_1' => ['nullable', 'string', 'max:255'],
            'address_village' => ['nullable', 'string', 'max:120'],
            'address_district' => ['nullable', 'string', 'max:120'],
            'address_regency' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $address = null;
        $addressFields = [
            'line_1' => $validated['address_line_1'] ?? null,
            'village' => $validated['address_village'] ?? null,
            'district' => $validated['address_district'] ?? null,
            'regency' => $validated['address_regency'] ?? null,
        ];
        if (array_filter($addressFields)) {
            $address = Address::create($addressFields);
        }

        $member = Member::create([
            'full_name' => $validated['full_name'],
            'gender' => $validated['gender'],
            'birth_place' => null,
            'birth_date' => $validated['birth_date'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address_id' => $address?->id,
            'status' => 'pending',
            'joined_at' => now(),
            'notes' => $validated['notes'] ?? null,
            'created_by' => null,
            'updated_by' => null,
        ]);

        try {
            if (filled($member->email)) {
                Notification::route('mail', $member->email)->notify(new MemberRegistrationReceived($member));
            }

            $internalUsers = User::query()
                ->where('is_active', true)
                ->whereHas('roles', fn ($q) => $q->whereIn('name', ['superadmin', 'admin', 'operator']))
                ->get();

            if ($internalUsers->isNotEmpty()) {
                Notification::send($internalUsers, new AdminNewMemberRegistered($member));
            }

            $contactEmail = SiteSetting::getValue('contact_email');
            if (! empty($contactEmail) && $internalUsers->where('email', $contactEmail)->isEmpty()) {
                Notification::route('mail', $contactEmail)->notify(new AdminNewMemberRegistered($member));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('member-registration.create')
            ->with('success', 'Pendaftaran diterima. Sekretariat akan menghubungi Anda.');
    }
}
