<?php
namespace App\Imports;

use App\Models\User;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class PanelsImport implements ToModel, WithValidation, SkipsOnFailure, WithHeadingRow
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new User([
        'matricNo'        => $row['matric_no'] ?? '',
        'name'            => $row['name'] ?? '',
        'username'        => $row['username'] ?? '',
        'email'           => $row['email'] ?? '',
        'password'        => Hash::make($row['password']),
        'isSupervisorPSM1'=> strtolower($row['is_supervisor_psm1'] ?? '') === 'yes',
        'isProposalPanel' => strtolower($row['is_proposal_panel'] ?? '') === 'yes',
        'isPanelPSM1'     => strtolower($row['is_panel_psm1'] ?? '') === 'yes',
        'isSupervisorPSM2'=> strtolower($row['is_supervisor_psm2'] ?? '') === 'yes',
        'isPanelPSM2'     => strtolower($row['is_panel_psm2'] ?? '') === 'yes',
        'isArchivePSM1'   => strtolower($row['is_archive_psm1'] ?? '') === 'yes',
        'isArchivePSM2'   => strtolower($row['is_archive_psm2'] ?? '') === 'yes',
        ]);
    }

    public function rules(): array
    {
        return [
        'matric_no'          => 'required|string|max:50|unique:users,matricNo', 
        'name'               => 'required|string|max:255',
        'username'           => 'required|string|max:100|unique:users,username',
        'email'              => 'required|email|max:255|unique:users,email',
        'password'           => 'required|string',
        'is_supervisor_psm1' => 'nullable|string|in:yes,no',
        'is_proposal_panel'  => 'nullable|string|in:yes,no',
        'is_panel_psm1'      => 'nullable|string|in:yes,no',
        'is_supervisor_psm2' => 'nullable|string|in:yes,no',
        'is_panel_psm2'      => 'nullable|string|in:yes,no',
        'is_archive_psm1'    => 'nullable|string|in:yes,no',
        'is_archive_psm2'    => 'nullable|string|in:yes,no',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'matric_no.required' => 'Matric is required.',
            'matric_no.string' => 'Matric must be a valid string.',
            'matric_no.unique' => 'Matric number already exists.',
            'matric_no.max' => 'Matric cannot exceed 50 characters.',

            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a valid string.',
            'name.max' => 'Name cannot exceed 255 characters.',

            'username.required' => 'Username is required.',
            'username.string' => 'Username must be a valid string.',
            'username.unique' => 'Username already exists.',
            'username.max' => 'Username cannot exceed 100 characters.',

            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'email.unique' => 'Email already exists.',

            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a valid string.',

            'is_supervisor_psm1.in' => 'Supervisor PSM1 must be either "yes" or "no".',
            'is_proposal_panel.in' => 'Proposal Panel must be either "yes" or "no".',
            'is_panel_psm1.in' => 'Panel PSM1 must be either "yes" or "no".',
            'is_supervisor_psm2.in' => 'Supervisor PSM2 must be either "yes" or "no".',
            'is_panel_psm2.in' => 'Panel PSM2 must be either "yes" or "no".',
            'is_archive_psm1.in' => 'Archive PSM1 must be either "yes" or "no".',
            'is_archive_psm2.in' => 'Archive PSM2 must be either "yes" or "no".',
        ];
    }


    public function onFailure(Failure ...$failures)
    {
        // Store failures in cache
        $existing = Cache::get('panels_import_failures', []);
        Cache::put('panels_import_failures', array_merge($existing, $failures));
    }

}
