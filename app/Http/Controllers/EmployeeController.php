<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeController extends Controller
{
    public function index()
    {
        return EmployeeResource::collection(
            Employee::with(['department', 'phoneNumbers', 'addresses'])->paginate(10)
        );
    }

    public function store(StoreEmployeeRequest $request)
    {
        try {
            // Create employee
            $employee = Employee::create($request->validated());

            // Save phone numbers
            if ($request->has('phone_numbers')) {
                foreach ($request->phone_numbers as $phone) {
                    $employee->phoneNumbers()->create([
                        'phone'      => $phone['phone'],
                        'label'      => $phone['label'] ?? null,
                        'is_primary' => $phone['is_primary'] ?? false,
                    ]);
                }
            }

            // Save addresses
            if ($request->has('addresses')) {
                foreach ($request->addresses as $address) {
                    $employee->addresses()->create([
                        'line1'       => $address['line1'] ?? null,
                        'line2'       => $address['line2'] ?? null,
                        'city'        => $address['city'] ?? null,
                        'state'       => $address['state'] ?? null,
                        'country'     => $address['country'] ?? null,
                        'postal_code' => $address['postal_code'] ?? null,
                        'label'       => $address['label'] ?? null,
                        'is_primary'  => $address['is_primary'] ?? false,
                    ]);
                }
            }

            return response()->json([
                'status'   => true,
                'message'  => 'Employee created successfully',
                'employee' => new EmployeeResource(
                    $employee->load(['department', 'phoneNumbers', 'addresses'])
                )
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to create employee',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function show($id)
    {
        $employee = Employee::with(['phoneNumbers', 'addresses', 'department'])->find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        return new EmployeeResource($employee);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        DB::beginTransaction();

        try {
            // Update employee basic details
            $employee->update($request->validated());

            // ----- Phone Numbers -----
            if ($request->has('phone_numbers')) {
                $existingPhoneIds = [];

                foreach ($request->phone_numbers as $phone) {
                    $phoneModel = $employee->phoneNumbers()->updateOrCreate(
                        ['id' => $phone['id'] ?? null], // update if id exists, else create
                        [
                            'phone'      => $phone['phone'],
                            'label'      => $phone['label'] ?? null,
                            'is_primary' => $phone['is_primary'] ?? false,
                        ]
                    );
                    $existingPhoneIds[] = $phoneModel->id;
                }

                // Delete phones not included in request
                $employee->phoneNumbers()->whereNotIn('id', $existingPhoneIds)->delete();
            }

            // ----- Addresses -----
            if ($request->has('addresses')) {
                $existingAddressIds = [];

                foreach ($request->addresses as $address) {
                    $addressModel = $employee->addresses()->updateOrCreate(
                        ['id' => $address['id'] ?? null],
                        [
                            'line1'       => $address['line1'],
                            'line2'       => $address['line2'] ?? null,
                            'city'        => $address['city'] ?? null,
                            'state'       => $address['state'] ?? null,
                            'country'     => $address['country'] ?? null,
                            'postal_code' => $address['postal_code'] ?? null,
                            'label'       => $address['label'] ?? null,
                            'is_primary'  => $address['is_primary'] ?? false,
                        ]
                    );
                    $existingAddressIds[] = $addressModel->id;
                }

                // Delete addresses not included in request
                $employee->addresses()->whereNotIn('id', $existingAddressIds)->delete();
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Employee updated successfully',
                'data' => new EmployeeResource($employee->load(['department', 'phoneNumbers', 'addresses']))
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Employee not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully'
        ]);
    }
    public function search(SearchEmployeeRequest $request)
    {
        $query = Employee::with(['department', 'phoneNumbers', 'addresses']);

        if ($request->filled('first_name')) {
            $query->where('first_name', 'like', '%' . $request->first_name . '%');
        }

        if ($request->filled('last_name')) {
            $query->where('last_name', 'like', '%' . $request->last_name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', $request->email);
        }

        if ($request->filled('designation')) {
            $query->where('position', 'like', '%' . $request->position . '%');
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('phone')) {
            $query->whereHas('phoneNumbers', function ($q) use ($request) {
                $q->where('phone', 'like', '%' . $request->phone_number . '%');
            });
        }

        if ($request->filled('city')) {
            $query->whereHas('addresses', function ($q) use ($request) {
                $q->where('city', 'like', '%' . $request->address . '%');
            });
        }

        $employees = $query->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Employees retrieved successfully',
            'data' => EmployeeResource::collection($employees),
        ]);
    }
}
