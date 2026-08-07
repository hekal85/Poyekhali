<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class CountryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Countries/Index', [
            'countries' => Country::withCount('visaTypes')->orderBy('order')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Countries/Create');
    }

    public function store(Request $request)
    {
        $data = $this->validateCountry($request);
        $country = Country::create($data['country']);
        $this->syncVisaTypes($country, $data['visa_types']);
        $this->handleImage($request, $country);

        return redirect()->route('admin.countries.index')->with('success', 'تمت إضافة الدولة بنجاح.');
    }

    public function edit(Country $country): Response
    {
        $country->load('visaTypes.documents');

        return Inertia::render('admin/Countries/Edit', ['country' => $country]);
    }

    public function update(Request $request, Country $country)
    {
        $data = $this->validateCountry($request, $country->id);
        $country->update($data['country']);
        $this->syncVisaTypes($country, $data['visa_types']);
        $this->handleImage($request, $country);

        return redirect()->route('admin.countries.index')->with('success', 'تم تحديث بيانات الدولة.');
    }

    public function destroy(Country $country)
    {
        if ($country->image_path) {
            Storage::disk('public')->delete($country->image_path);
        }
        $country->delete();

        return redirect()->route('admin.countries.index')->with('success', 'تم حذف الدولة.');
    }

    private function validateCountry(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'slug' => ['required', 'string', 'alpha_dash', Rule::unique('countries', 'slug')->ignore($ignoreId)],
            'flag' => ['required', 'string', 'size:2'],
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'region' => ['required', Rule::in(['gulf', 'other'])],
            'processing_time_en' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],

            'visa_types' => ['array'],
            'visa_types.*.key' => ['required', 'string', 'max:50'],
            'visa_types.*.name_ar' => ['required', 'string', 'max:255'],
            'visa_types.*.name_en' => ['required', 'string', 'max:255'],
            'visa_types.*.fee' => ['required', 'integer', 'min:0'],
            'visa_types.*.processing_time_ar' => ['nullable', 'string', 'max:255'],
            'visa_types.*.is_active' => ['nullable', 'boolean'],
            'visa_types.*.documents' => ['array'],
            'visa_types.*.documents.*.text_ar' => ['required', 'string', 'max:500'],
            'visa_types.*.documents.*.text_en' => ['required', 'string', 'max:500'],
        ]);

        return [
            'country' => [
                'slug' => $validated['slug'],
                'flag' => strtolower($validated['flag']),
                'name_ar' => $validated['name_ar'],
                'name_en' => $validated['name_en'],
                'region' => $validated['region'],
                'processing_time_en' => $validated['processing_time_en'],
                'order' => $validated['order'] ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ],
            'visa_types' => $validated['visa_types'] ?? [],
        ];
    }

    private function syncVisaTypes(Country $country, array $visaTypes): void
    {
        $country->visaTypes()->delete();

        foreach ($visaTypes as $i => $vt) {
            $created = $country->visaTypes()->create([
                'key' => $vt['key'],
                'name_ar' => $vt['name_ar'],
                'name_en' => $vt['name_en'],
                'fee' => $vt['fee'],
                'processing_time_ar' => $vt['processing_time_ar'] ?? null,
                'is_active' => $vt['is_active'] ?? true,
                'order' => $i,
            ]);

            foreach (($vt['documents'] ?? []) as $di => $doc) {
                $created->documents()->create([
                    'text_ar' => $doc['text_ar'],
                    'text_en' => $doc['text_en'],
                    'order' => $di,
                ]);
            }
        }
    }

    private function handleImage(Request $request, Country $country): void
    {
        if (! $request->hasFile('image')) {
            return;
        }
        if ($country->image_path) {
            Storage::disk('public')->delete($country->image_path);
        }
        $country->update(['image_path' => $request->file('image')->store('countries', 'public')]);
    }
}
