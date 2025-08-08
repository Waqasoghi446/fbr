<?php

// =============================================================================
// CONTROLLER: app/Http/Controllers/FbrScenarioController.php
// =============================================================================

namespace App\Http\Controllers;

use App\Models\FbrScenario;
use App\Models\FbrBusinessTypeScenario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FbrScenarioController extends Controller
{
    /**
     * Get all available business types for dropdown
     * GET /api/fbr-scenarios/business-types
     */
    public function getBusinessTypes()
    {
        try {
            $businessTypes = DB::table('fbr_business_type_scenarios')
                ->distinct()
                ->orderBy('business_type')
                ->pluck('business_type')
                ->map(function ($type) {
                    return [
                        'value' => $type,
                        'label' => ucwords(str_replace('_', ' ', $type))
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'data' => $businessTypes,
                'message' => 'Business types retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving business types: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get business types with scenario counts
     * GET /api/fbr-scenarios/business-types-with-counts
     */
    public function getBusinessTypesWithCounts()
    {
        try {
            $businessTypes = DB::table('fbr_business_type_scenarios as bts')
                ->join('fbr_scenarios as fs', 'bts.scenario_code', '=', 'fs.scenario_code')
                ->where('fs.is_active', true)
                ->select('bts.business_type', DB::raw('COUNT(*) as scenario_count'))
                ->groupBy('bts.business_type')
                ->orderBy('bts.business_type')
                ->get()
                ->map(function ($item) {
                    return [
                        'value' => $item->business_type,
                        'label' => ucwords(str_replace('_', ' ', $item->business_type)),
                        'count' => $item->scenario_count
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $businessTypes,
                'message' => 'Business types with counts retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving business types: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get scenarios for dropdown by business type
     * GET /api/fbr-scenarios/by-business-type?business_type=manufacturer
     */
    public function getScenariosByBusinessType(Request $request)
    {
        try {
            $businessType = $request->query('business_type');
            // dd($request);
            if (!$businessType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business type is required'
                ], 400);
            }

            // Get scenarios using the mapping table
            $scenarios = DB::table('fbr_scenarios as fs')
                ->join('fbr_business_type_scenarios as bts', 'fs.scenario_code', '=', 'bts.scenario_code')
                ->where('bts.business_type', $businessType)
                ->where('fs.is_active', true)
                ->select('fs.scenario_code', 'fs.name')
                ->orderBy('fs.scenario_code')
                ->get()
                ->map(function ($scenario) {
                    return [
                        'value' => $scenario->scenario_code,
                        'label' => $scenario->scenario_code . ' - ' . $scenario->name,
                        'text' => $scenario->name
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'business_type' => $businessType,
                    'scenarios' => $scenarios,
                    'count' => $scenarios->count()
                ],
                'message' => 'Scenarios retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving scenarios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get scenario details by scenario code
     * GET /api/fbr-scenarios/{scenarioCode}
     */
    public function getScenarioDetails($scenarioCode)
    {
        try {
            $scenario = DB::table('fbr_scenarios')
                ->where('scenario_code', $scenarioCode)
                ->where('is_active', true)
                ->first();

            if (!$scenario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Scenario not found'
                ], 404);
            }

            // Get scenario items
            $items = DB::table('fbr_scenario_items')
                ->where('scenario_id', $scenario->id)
                ->get();

            // Get business types for this scenario
            $businessTypes = DB::table('fbr_business_type_scenarios')
                ->where('scenario_code', $scenarioCode)
                ->pluck('business_type')
                ->toArray();

            $scenarioData = [
                'id' => $scenario->id,
                'scenario_code' => $scenario->scenario_code,
                'name' => $scenario->name,
                'description' => $scenario->description,
                'business_types' => $businessTypes,
                'seller_info' => [
                    'ntn_cnic' => $scenario->seller_ntn_cnic,
                    'business_name' => $scenario->seller_business_name,
                    'province' => $scenario->seller_province,
                    'address' => $scenario->seller_address,
                ],
                'buyer_info' => [
                    'ntn_cnic' => $scenario->buyer_ntn_cnic,
                    'business_name' => $scenario->buyer_business_name,
                    'province' => $scenario->buyer_province,
                    'address' => $scenario->buyer_address,
                    'registration_type' => $scenario->buyer_registration_type,
                ],
                'invoice_ref_no' => $scenario->invoice_ref_no,
                'items' => $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'hs_code' => $item->hs_code,
                        'product_description' => $item->product_description,
                        'rate' => $item->rate,
                        'uom' => $item->uom,
                        'quantity' => (float) $item->quantity,
                        'value_sales_excluding_st' => (float) $item->value_sales_excluding_st,
                        'sales_tax_applicable' => (float) $item->sales_tax_applicable,
                        'total_values' => (float) $item->total_values,
                        'fixed_notified_value_or_retail_price' => (float) $item->fixed_notified_value_or_retail_price,
                        'sales_tax_withheld_at_source' => (float) $item->sales_tax_withheld_at_source,
                        'extra_tax' => (float) $item->extra_tax,
                        'further_tax' => (float) $item->further_tax,
                        'fed_payable' => (float) $item->fed_payable,
                        'discount' => (float) $item->discount,
                        'sro_schedule_no' => $item->sro_schedule_no,
                        'sro_item_serial_no' => $item->sro_item_serial_no,
                        'sale_type' => $item->sale_type
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'data' => $scenarioData,
                'message' => 'Scenario details retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving scenario details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all scenarios for dropdown (without business type filter)
     * GET /api/fbr-scenarios/all-dropdown
     */
    public function getAllScenariosForDropdown()
    {
        try {
            $scenarios = DB::table('fbr_scenarios')
                ->where('is_active', true)
                ->select('scenario_code', 'name')
                ->orderBy('scenario_code')
                ->get()
                ->map(function ($scenario) {
                    return [
                        'value' => $scenario->scenario_code,
                        'label' => $scenario->scenario_code . ' - ' . $scenario->name,
                        'text' => $scenario->name
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $scenarios,
                'message' => 'All scenarios retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving scenarios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search scenarios
     * GET /api/fbr-scenarios/search?search=steel&business_type=manufacturer
     */
    public function searchScenarios(Request $request)
    {
        try {
            $search = $request->input('search');
            $businessType = $request->input('business_type');

            $query = DB::table('fbr_scenarios as fs')
                ->where('fs.is_active', true);

            // Join with business type mapping if business type is specified
            if ($businessType) {
                $query->join('fbr_business_type_scenarios as bts', 'fs.scenario_code', '=', 'bts.scenario_code')
                      ->where('bts.business_type', $businessType);
            }

            // Add search conditions
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('fs.scenario_code', 'like', "%{$search}%")
                      ->orWhere('fs.name', 'like', "%{$search}%")
                      ->orWhere('fs.description', 'like', "%{$search}%");
                });
            }

            $scenarios = $query->select('fs.scenario_code', 'fs.name', 'fs.description')
                ->distinct()
                ->orderBy('fs.scenario_code')
                ->get()
                ->map(function ($scenario) {
                    return [
                        'value' => $scenario->scenario_code,
                        'label' => $scenario->scenario_code . ' - ' . $scenario->name,
                        'text' => $scenario->name,
                        'description' => $scenario->description
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'search_term' => $search,
                    'business_type' => $businessType,
                    'scenarios' => $scenarios,
                    'count' => $scenarios->count()
                ],
                'message' => 'Search completed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching scenarios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate sample invoice data for scenario
     * GET /api/fbr-scenarios/{scenarioCode}/sample-invoice
     */
    public function generateSampleInvoice($scenarioCode)
    {
        try {
            $scenario = DB::table('fbr_scenarios')
                ->where('scenario_code', $scenarioCode)
                ->where('is_active', true)
                ->first();

            if (!$scenario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Scenario not found'
                ], 404);
            }

            // Get scenario items
            $items = DB::table('fbr_scenario_items')
                ->where('scenario_id', $scenario->id)
                ->get();

            $sampleInvoice = [
                'scenario_code' => $scenario->scenario_code,
                'scenario_name' => $scenario->name,
                'invoice_date' => now()->format('Y-m-d'),
                'invoice_ref_no' => $scenario->invoice_ref_no ?: 'SI-' . date('Ymd') . '-001',
                'seller' => [
                    'ntn_cnic' => $scenario->seller_ntn_cnic,
                    'business_name' => $scenario->seller_business_name,
                    'province' => $scenario->seller_province,
                    'address' => $scenario->seller_address,
                ],
                'buyer' => [
                    'ntn_cnic' => $scenario->buyer_ntn_cnic,
                    'business_name' => $scenario->buyer_business_name,
                    'province' => $scenario->buyer_province,
                    'address' => $scenario->buyer_address,
                    'registration_type' => $scenario->buyer_registration_type,
                ],
                'items' => $items->map(function ($item) {
                    return [
                        'hs_code' => $item->hs_code,
                        'description' => $item->product_description,
                        'rate' => $item->rate,
                        'uom' => $item->uom,
                        'quantity' => (float) $item->quantity,
                        'unit_price' => (float) $item->value_sales_excluding_st,
                        'sales_tax' => (float) $item->sales_tax_applicable,
                        'total_value' => (float) $item->total_values,
                        'sale_type' => $item->sale_type,
                        'sro_schedule_no' => $item->sro_schedule_no,
                        'sro_item_serial_no' => $item->sro_item_serial_no,
                    ];
                }),
                'totals' => [
                    'total_amount_excluding_tax' => (float) $items->sum('value_sales_excluding_st'),
                    'total_sales_tax' => (float) $items->sum('sales_tax_applicable'),
                    'grand_total' => (float) ($items->sum('value_sales_excluding_st') + $items->sum('sales_tax_applicable'))
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $sampleInvoice,
                'message' => 'Sample invoice generated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating sample invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dropdown data (business types and scenarios)
     * GET /api/fbr-scenarios/dropdown-data
     */
    public function getDropdownData()
    {
        try {
            // Get business types
            $businessTypes = DB::table('fbr_business_type_scenarios')
                ->distinct()
                ->orderBy('business_type')
                ->pluck('business_type')
                ->map(function ($type) {
                    return [
                        'value' => $type,
                        'label' => ucwords(str_replace('_', ' ', $type))
                    ];
                })
                ->values();

            // Get all scenarios
            $allScenarios = DB::table('fbr_scenarios')
                ->where('is_active', true)
                ->select('scenario_code', 'name')
                ->orderBy('scenario_code')
                ->get()
                ->map(function ($scenario) {
                    return [
                        'value' => $scenario->scenario_code,
                        'label' => $scenario->scenario_code . ' - ' . $scenario->name,
                        'text' => $scenario->name
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'business_types' => $businessTypes,
                    'all_scenarios' => $allScenarios
                ],
                'message' => 'Dropdown data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving dropdown data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get scenarios summary/stats
     * GET /api/fbr-scenarios/stats
     */
    public function getScenarioStats()
    {
        try {
            $stats = [
                'total_scenarios' => DB::table('fbr_scenarios')->where('is_active', true)->count(),
                'total_business_types' => DB::table('fbr_business_type_scenarios')->distinct('business_type')->count(),
                'scenarios_by_business_type' => DB::table('fbr_business_type_scenarios as bts')
                    ->join('fbr_scenarios as fs', 'bts.scenario_code', '=', 'fs.scenario_code')
                    ->where('fs.is_active', true)
                    ->select('bts.business_type', DB::raw('COUNT(*) as count'))
                    ->groupBy('bts.business_type')
                    ->orderBy('count', 'desc')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'business_type' => ucwords(str_replace('_', ' ', $item->business_type)),
                            'count' => $item->count
                        ];
                    })
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
