<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Fbr\DigitalInvoicing\Facades\FbrDigitalInvoicing;
use Fbr\DigitalInvoicing\Builders\InvoiceBuilder;
use Fbr\DigitalInvoicing\Builders\InvoiceItemBuilder;
use Fbr\DigitalInvoicing\Constants\Scenarios;

class FbrTestController extends Controller
{


    public function test()
    {
        $results = [];



        try {
            // Create the same invoice as above
            $item = (new InvoiceItemBuilder())
            ->setHsCode('0101.2100')
            ->setProductDescription('TEST')
            ->setRate('18%')
            ->setUom('Numbers, pieces, units')
            ->setQuantity(1.0)
            ->setTotalValues(0)
            ->setValueSalesExcludingST(1000)
            ->setFixedNotifiedValueOrRetailPrice(0)
            ->setSalesTaxApplicable(180)
            ->setSalesTaxWithheldAtSource(0)
            ->setExtraTax(0)
            ->setFurtherTax(0)
            ->setFedPayable(0)
            ->setDiscount(0)
            ->setSaleType('Goods at standard rate (default)')
            ->setSroItemSerialNo('')
            ->build();

        $invoice = (new InvoiceBuilder())
            ->setInvoiceType('Sale Invoice')
            ->setInvoiceDate('2025-08-05')
            ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
            ->setBuyer('1350462888273', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Registered')
            ->setScenarioId('SN001')
            ->setInvoiceRefNo('SI-20250421-001')
            ->addItem($item)
            ->build();

            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
    public function SN001()
    {
        $results = [];



        try {
            // Create the same invoice as above
           $item = (new InvoiceItemBuilder())
                        ->setHsCode('0101.2100')
                        ->setProductDescription('test')
                        ->setRate('18%')
                        ->setUom('Numbers, pieces, units')
                        ->setQuantity(400) // from JSON
                        ->setTotalValues(0)
                        ->setValueSalesExcludingST(1000)
                        ->setFixedNotifiedValueOrRetailPrice(0.00)
                        ->setSalesTaxApplicable(180)
                        ->setSalesTaxWithheldAtSource(0)
                        ->setExtraTax(0) // converted empty string to 0
                        ->setFurtherTax(0)
                        ->setFedPayable(0)
                        ->setDiscount(0)
                        ->setSaleType('Goods at standard rate (default)')
                        ->setSroItemSerialNo('')
                        ->build();

                    $invoice = (new InvoiceBuilder())
                        ->setInvoiceType('Sale Invoice')
                        ->setInvoiceDate('2025-05-10') // from JSON
                        ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                        ->setBuyer('2046004', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Registered')
                        ->setScenarioId('SN001')
                        ->setInvoiceRefNo('') // as per JSON, this is blank
                        ->addItem($item)
                        ->build();


            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
      public function SN002()
    {
        $results = [];



        try {
                        // Create the same invoice as above
                    $item = (new InvoiceItemBuilder())
                ->setHsCode('0101.2100')
                ->setProductDescription('product Description')
                ->setRate('18%')
                ->setUom('Numbers, pieces, units')
                ->setQuantity(1.0000)
                ->setTotalValues(0.00)
                ->setValueSalesExcludingST(1000.00)
                ->setFixedNotifiedValueOrRetailPrice(0.00)
                ->setSalesTaxApplicable(180.00)
                ->setSalesTaxWithheldAtSource(0.00)
                ->setExtraTax(0.00)
                ->setFurtherTax(120.00)
                ->setFedPayable(0.00)
                ->setDiscount(0.00)
                ->setSaleType('Goods at standard rate (default)')
                ->setSroItemSerialNo('')
                ->build();

            $invoice = (new InvoiceBuilder())
                ->setInvoiceType('Sale Invoice')
                ->setInvoiceDate('2025-04-21')
                ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                ->setBuyer('1350439930769', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                ->setScenarioId('SN002')
                ->setInvoiceRefNo('')
                ->addItem($item)
                ->build();



            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN003()
    {
        $results = [];
        try {
                        // Create the same invoice as above
                   $item = (new InvoiceItemBuilder())
                    ->setHsCode('7214.1010')
                    ->setProductDescription('rtest') // Empty string from JSON
                    ->setRate('18%')
                    ->setUom('MT')
                    ->setQuantity(1)
                    ->setTotalValues(0)
                    ->setValueSalesExcludingST(205000.00)
                    ->setFixedNotifiedValueOrRetailPrice(0.00)
                    ->setSalesTaxApplicable(36900.00)
                    ->setSalesTaxWithheldAtSource(0)
                    ->setExtraTax(0)
                    ->setFurtherTax(0)
                    ->setFedPayable(0)
                    ->setDiscount(0)
                    ->setSaleType('Steel melting and re-rolling')
                    ->setSroItemSerialNo('')
                    ->build();

                $invoice = (new InvoiceBuilder())
                    ->setInvoiceType('Sale Invoice')
                    ->setInvoiceDate('2025-04-21')
                    ->setSeller('5076033', 'Company 7', 'Sindh', 'Karachi')
                    ->setBuyer('3710505701479', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                    ->setScenarioId('SN003')
                    ->setInvoiceRefNo('0') // Ref No is "0" as per JSON
                    ->addItem($item)
                    ->build();



            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
    public function SN005()
    {
        $results = [];
        try {
                $item = (new InvoiceItemBuilder())
                        ->setHsCode('0102.2930')
                        ->setProductDescription('product Description41')
                        ->setRate('1%')
                        ->setUom('Numbers, pieces, units')
                        ->setQuantity(1.0000)
                        ->setTotalValues(0.00)
                        ->setValueSalesExcludingST(1000.00)
                        ->setFixedNotifiedValueOrRetailPrice(0.00)
                        ->setSalesTaxApplicable(10)
                        ->setSalesTaxWithheldAtSource(50.23)
                        ->setExtraTax('1') // Empty string safely cast to 0
                        ->setFurtherTax(0)
                        ->setFedPayable(50.36)
                        ->setDiscount(56.36)
                        ->setSaleType('Goods at Reduced Rate')
                        ->setSroScheduleNo('EIGHTH SCHEDULE Table 1')
                        ->setSroItemSerialNo('82')
                        ->build();

                    $invoice = (new InvoiceBuilder())
                        ->setInvoiceType('Sale Invoice')
                        ->setInvoiceDate('2025-06-30')
                        ->setSeller('5076033', 'B2', 'Sindh', 'Karachi')
                        ->setBuyer('1000000000000', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                        ->setScenarioId('SN005')
                        ->setInvoiceRefNo('1234') // Still blank; you can auto-generate if needed
                        ->addItem($item)
                        ->build();


            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN006()
    {
        $results = [];
        try {
                        // Create the same invoice as above
                  $item = (new InvoiceItemBuilder())
                        ->setHsCode('0101.2100')
                        ->setProductDescription('test')
                        ->setRate('0%')
                        ->setUom('Numbers, pieces, units')
                        ->setQuantity(100)
                        ->setTotalValues(0)
                        ->setValueSalesExcludingST(100)
                        ->setFixedNotifiedValueOrRetailPrice(0.00)
                        ->setSalesTaxApplicable(0)
                        ->setSalesTaxWithheldAtSource(0)
                        ->setExtraTax(0)
                        ->setFurtherTax(0)
                        ->setFedPayable(0)
                        ->setSroScheduleNo('327(I)/2008')
                        ->setDiscount(0)
                        ->setSaleType('Goods at zero-rate')
                        ->setSroItemSerialNo('1')
                        ->build();

                    $invoice = (new InvoiceBuilder())
                        ->setInvoiceType('Sale Invoice')
                        ->setInvoiceDate('2025-04-21')
                        ->setSeller('5076033', 'Company 7', 'Sindh', 'Karachi')
                        ->setBuyer('3710505701479', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                        ->setScenarioId('SN007')
                        ->setInvoiceRefNo('0')
                        ->addItem($item)
                        ->build();



            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN011()
    {
        $results = [];
        try {
        $item = (new InvoiceItemBuilder())
                    ->setHsCode('7214.9990')
                    ->setProductDescription('test') // Empty product description
                    ->setRate('18%')
                    ->setUom('MT')
                    ->setQuantity(1)
                    ->setTotalValues(0)
                    ->setValueSalesExcludingST(205000)
                    ->setFixedNotifiedValueOrRetailPrice(0)
                    ->setSalesTaxApplicable(36900)
                    ->setSalesTaxWithheldAtSource(0)
                    ->setExtraTax(0)
                    ->setFurtherTax(0)
                    ->setFedPayable(0)
                    ->setDiscount(0)
                    ->setSaleType('Toll Manufacturing')
                    ->setSroScheduleNo('')
                    ->setSroItemSerialNo('')
                    ->build();

                $invoice = (new InvoiceBuilder())
                    ->setInvoiceType('Sale Invoice')
                    ->setInvoiceDate('2025-05-26')
                    ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                    ->setBuyer('3710505701479', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi','Unregistered')
                    ->setScenarioId('SN011')
                    ->setInvoiceRefNo('')
                    ->addItem($item)
                    ->build();

            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN007()
    {
        $results = [];
        try {
                        // Create the same invoice as above
                   $item = (new InvoiceItemBuilder())
                        ->setHsCode('0102.2930')
                        ->setProductDescription('product Description41')
                        ->setRate('Exempt')
                        ->setUom('Numbers, pieces, units')
                        ->setQuantity(1.0000)
                        ->setTotalValues(0.00)
                        ->setValueSalesExcludingST(10)
                        ->setFixedNotifiedValueOrRetailPrice(0.00)
                        ->setSalesTaxApplicable(0)
                        ->setSalesTaxWithheldAtSource(50.23)
                        ->setExtraTax(0) // Handled empty string as 0
                        ->setFurtherTax(120.00)
                        ->setFedPayable(50.36)
                        ->setDiscount(56.36)
                        ->setSaleType('Exempt goods')
                        ->setSroItemSerialNo('100')
                        // Optionally include this if supported by your builder
                        ->setSroScheduleNo('6th Schd Table I')
                        ->build();

                    $invoice = (new InvoiceBuilder())
                        ->setInvoiceType('Sale Invoice')
                        ->setInvoiceDate('2025-07-01')
                        ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                        ->setBuyer('2046004', 'FERTILIZERNUFAC IRS NEW', 'Sindh', 'Karachi', 'Registered')
                        ->setScenarioId('SN006')
                        ->setInvoiceRefNo('SI-20250515-001')
                        ->addItem($item)
                        ->build();



            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN008()
    {
        $results = [];
        try {
                        // Create the same invoice as above
                   $item = (new InvoiceItemBuilder())
                    ->setHsCode('0101.2100')
                    ->setProductDescription('test')
                    ->setRate('18%')
                    ->setUom('Numbers, pieces, units')
                    ->setQuantity(100)
                    ->setTotalValues(145)
                    ->setValueSalesExcludingST(0)
                    ->setFixedNotifiedValueOrRetailPrice(1000)
                    ->setSalesTaxApplicable(180)
                    ->setSalesTaxWithheldAtSource(0)
                    ->setExtraTax(0)
                    ->setFurtherTax(0)
                    ->setFedPayable(0)
                    ->setDiscount(0)
                    ->setSaleType('3rd Schedule Goods')
                    ->setSroItemSerialNo('')
                    ->build();

                $invoice = (new InvoiceBuilder())
                    ->setInvoiceType('Sale Invoice')
                    ->setInvoiceDate('2025-04-21')
                    ->setSeller('5076033', 'Company 7', 'Sindh', 'Karachi')
                    ->setBuyer('3710505701479', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                    ->setScenarioId('SN008')
                    ->setInvoiceRefNo('0')
                    ->addItem($item)
                    ->build();



            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN009()
    {
        $results = [];
        try {
                        // Create the same invoice as above
                  $item = (new InvoiceItemBuilder())
                                ->setHsCode('0101.2100')
                                ->setProductDescription('test')
                                ->setRate('18%')
                                ->setUom('Numbers, pieces, units')
                                ->setQuantity(0)
                                ->setTotalValues(2500)
                                ->setValueSalesExcludingST(2500)
                                ->setFixedNotifiedValueOrRetailPrice(0.00)
                                ->setSalesTaxApplicable(450)
                                ->setSalesTaxWithheldAtSource(0)
                                ->setExtraTax(0)
                                ->setFurtherTax(0)
                                ->setFedPayable(0)
                                ->setDiscount(0)
                                ->setSaleType('Cotton ginners')
                                ->setSroItemSerialNo('')
                                ->build();

                            $invoice = (new InvoiceBuilder())
                                ->setInvoiceType('Sale Invoice')
                                ->setInvoiceDate('2025-05-15')
                                ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                                ->setBuyer('2046004', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Registered')
                                ->setScenarioId('SN009')
                                ->setInvoiceRefNo('') // Empty as per JSON
                                ->addItem($item)
                                ->build();




            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
      public function SN010()
    {
        $results = [];
        try {
                        // Create the same invoice as above
                $item = (new InvoiceItemBuilder())
                    ->setHsCode('0101.2100')
                    ->setProductDescription('TEST')
                    ->setRate('1.43%')
                    ->setUom('Numbers, pieces, units')
                    ->setQuantity(123)
                    ->setTotalValues(132)
                    ->setValueSalesExcludingST(100)
                    ->setFixedNotifiedValueOrRetailPrice(0.00)
                    ->setSalesTaxApplicable(1.43)
                    ->setSalesTaxWithheldAtSource(2)
                    ->setExtraTax(0)
                    ->setFurtherTax(0)
                    ->setFedPayable(0)
                    ->setDiscount(0)
                    ->setSaleType('Petroleum Products')
                    ->setSroItemSerialNo('4')
                    // Optional: only if supported by your builder
                    ->setSroScheduleNo('1450(I)/2021')
                    ->build();

                $invoice = (new InvoiceBuilder())
                    ->setInvoiceType('Sale Invoice')
                    ->setInvoiceDate('2025-05-15')
                    ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                    ->setBuyer('1000000000000', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                    ->setScenarioId('SN012')
                    ->setInvoiceRefNo('SI-20250515-001')
                    ->addItem($item)
                    ->build();

                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN012()
    {
        $results = [];
        try {
                        // Create the same invoice as above
                $item = (new InvoiceItemBuilder())
                    ->setHsCode('0101.2100')
                    ->setProductDescription('TEST')
                    ->setRate('1.43%')
                    ->setUom('Numbers, pieces, units')
                    ->setQuantity(123)
                    ->setTotalValues(132)
                    ->setValueSalesExcludingST(100)
                    ->setFixedNotifiedValueOrRetailPrice(0.00)
                    ->setSalesTaxApplicable(1.43)
                    ->setSalesTaxWithheldAtSource(2)
                    ->setExtraTax(0)
                    ->setFurtherTax(0)
                    ->setFedPayable(0)
                    ->setDiscount(0)
                    ->setSaleType('Petroleum Products')
                    ->setSroItemSerialNo('4')
                    // Optional: only if supported by your builder
                    ->setSroScheduleNo('1450(I)/2021')
                    ->build();

                $invoice = (new InvoiceBuilder())
                    ->setInvoiceType('Sale Invoice')
                    ->setInvoiceDate('2025-05-15')
                    ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                    ->setBuyer('1000000000000', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                    ->setScenarioId('SN012')
                    ->setInvoiceRefNo('SI-20250515-001')
                    ->addItem($item)
                    ->build();

                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN013()
    {
        $results = [];
        try {
                        // Create the same invoice as above
               $item = (new InvoiceItemBuilder())
                    ->setHsCode('0101.2100')
                    ->setProductDescription('TEST')
                    ->setRate('5%')
                    ->setUom('Numbers, pieces, units')
                    ->setQuantity(123)
                    ->setTotalValues(212)
                    ->setValueSalesExcludingST(1000)
                    ->setFixedNotifiedValueOrRetailPrice(0.00)
                    ->setSalesTaxApplicable(50)
                    ->setSalesTaxWithheldAtSource(11)
                    ->setExtraTax(0)
                    ->setFurtherTax(0)
                    ->setFedPayable(0)
                    ->setDiscount(0)
                    ->setSaleType('Electricity Supply to Retailers')
                    ->setSroItemSerialNo('4')
                    // Uncomment if your builder supports this method:
                    // ->setSroScheduleNo('1450(I)/2021')
                    ->build();

                $invoice = (new InvoiceBuilder())
                    ->setInvoiceType('Sale Invoice')
                    ->setInvoiceDate('2025-05-15')
                    ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                    ->setBuyer('1000000000000', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                    ->setScenarioId('SN013')
                    ->setInvoiceRefNo('SI-20250515-001')
                    ->addItem($item)
                    ->build();


                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN014()
    {
        $results = [];
        try {
                        // Create the same invoice as above
              $item = (new InvoiceItemBuilder())
                        ->setHsCode('0101.2100')
                        ->setProductDescription('TEST')
                        ->setRate('18%')
                        ->setUom('Numbers, pieces, units')
                        ->setQuantity(123)
                        ->setTotalValues(0)
                        ->setValueSalesExcludingST(1000)
                        ->setFixedNotifiedValueOrRetailPrice(0)
                        ->setSalesTaxApplicable(180)
                        ->setSalesTaxWithheldAtSource(0)
                        ->setExtraTax(0)
                        ->setFurtherTax(0)
                        ->setFedPayable(0)
                        ->setDiscount(0)
                        ->setSaleType('Gas to CNG stations')
                        ->setSroItemSerialNo('')
                        ->build();

                    $invoice = (new InvoiceBuilder())
                        ->setInvoiceType('Sale Invoice')
                        ->setInvoiceDate('2025-05-15')
                        ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                        ->setBuyer('1000000000000', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                        ->setScenarioId('SN014')
                        ->setInvoiceRefNo('SI-20250515-001')
                        ->addItem($item)
                        ->build();

                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN015()
    {
        $results = [];
        try {
                        // Create the same invoice as above
               $item = (new InvoiceItemBuilder())
                            ->setHsCode('0101.2100')
                            ->setProductDescription('TEST')
                            ->setRate('18%')
                            ->setUom('Numbers, pieces, units')
                            ->setQuantity(123)
                            ->setTotalValues(0)
                            ->setValueSalesExcludingST(1234)
                            ->setFixedNotifiedValueOrRetailPrice(0)
                            ->setSalesTaxApplicable(222.12)
                            ->setSalesTaxWithheldAtSource(0)
                            ->setExtraTax(0)
                            ->setFurtherTax(0)
                            ->setFedPayable(0)
                            ->setDiscount(0)
                            ->setSaleType('Mobile Phones')
                            ->setSroScheduleNo('NINTH SCHEDULE')      // if supported
                            ->setSroItemSerialNo('1(A)')
                            ->build();

                        $invoice = (new InvoiceBuilder())
                            ->setInvoiceType('Sale Invoice')
                            ->setInvoiceDate('2025-05-15')
                            ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                            ->setBuyer('1000000000000', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                            ->setScenarioId('SN015')
                            ->setInvoiceRefNo('SI-20250515-001')
                            ->addItem($item)
                            ->build();


                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN016()
    {
        $results = [];
        try {
                        // Create the same invoice as above
               $item = (new InvoiceItemBuilder())
                            ->setHsCode('0101.2100')
                            ->setProductDescription('test')
                            ->setRate('5%')
                            ->setUom('Numbers, pieces, units')
                            ->setQuantity(1)
                            ->setTotalValues(0)
                            ->setValueSalesExcludingST(100)
                            ->setFixedNotifiedValueOrRetailPrice(0)
                            ->setSalesTaxApplicable(5)
                            ->setSalesTaxWithheldAtSource(0)
                            ->setExtraTax(0)
                            ->setFurtherTax(0)
                            ->setFedPayable(0)
                            ->setDiscount(0)
                            ->setSaleType('Processing/Conversion of Goods')
                            ->setSroItemSerialNo('')
                            ->setSroScheduleNo('')
                            ->build();

                        $invoice = (new InvoiceBuilder())
                            ->setInvoiceType('Sale Invoice')
                            ->setInvoiceDate('2025-05-16')
                            ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                            ->setBuyer('1000000000078', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                            ->setScenarioId('SN016')
                            ->setInvoiceRefNo('')
                            ->addItem($item)
                            ->build();
                                                    // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN017()
    {
        $results = [];
        try {
                        // Create the same invoice as above
               $item = (new InvoiceItemBuilder())
                        ->setHsCode('0101.2100')
                        ->setProductDescription('TEST')
                        ->setRate('8%')
                        ->setUom('Numbers, pieces, units')
                        ->setQuantity(1)
                        ->setTotalValues(0)
                        ->setValueSalesExcludingST(100)
                        ->setFixedNotifiedValueOrRetailPrice(0)
                        ->setSalesTaxApplicable(8)
                        ->setSalesTaxWithheldAtSource(0)
                        ->setExtraTax(0)
                        ->setFurtherTax(0)
                        ->setFedPayable(0)
                        ->setDiscount(0)
                        ->setSaleType('Goods (FED in ST Mode)')
                        ->setSroScheduleNo('')
                        ->setSroItemSerialNo('')
                        ->build();

                    $invoice = (new InvoiceBuilder())
                        ->setInvoiceType('Sale Invoice')
                        ->setInvoiceDate('2025-05-10')
                        ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                        ->setBuyer('7000009', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                        ->setScenarioId('SN017')
                        ->setInvoiceRefNo('')
                        ->addItem($item)
                        ->build();


                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN018()
    {
        $results = [];
        try {
                        // Create the same invoice as above
               $item = (new InvoiceItemBuilder())
                ->setHsCode('0101.2100')
                ->setProductDescription('TEST')
                ->setRate('8%')
                ->setUom('Numbers, pieces, units')
                ->setQuantity(20)
                ->setTotalValues(0)
                ->setValueSalesExcludingST(1000)
                ->setFixedNotifiedValueOrRetailPrice(0)
                ->setSalesTaxApplicable(80)
                ->setSalesTaxWithheldAtSource(0)
                ->setExtraTax(0)
                ->setFurtherTax(0)
                ->setFedPayable(0)
                ->setDiscount(0)
                ->setSaleType('Services (FED in ST Mode)')
                ->setSroScheduleNo('')
                ->setSroItemSerialNo('')
                ->build();

            $invoice = (new InvoiceBuilder())
                ->setInvoiceType('Sale Invoice')
                ->setInvoiceDate('2025-06-14')
                ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                ->setBuyer('1000000000056', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                ->setScenarioId('SN018')
                ->setInvoiceRefNo('SI-20250421-001')
                ->addItem($item)
                ->build();


                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN019()
    {
        $results = [];
        try {
                        // Create the same invoice as above
               $item = (new InvoiceItemBuilder())
                ->setHsCode('0101.2900')
                ->setProductDescription('TEST')
                ->setRate('5%')
                ->setUom('Numbers, pieces, units')
                ->setQuantity(1)
                ->setTotalValues(0)
                ->setValueSalesExcludingST(100)
                ->setFixedNotifiedValueOrRetailPrice(0)
                ->setSalesTaxApplicable(5)
                ->setSalesTaxWithheldAtSource(0)
                ->setExtraTax(0)
                ->setFurtherTax(0)
                ->setFedPayable(0)
                ->setDiscount(0)
                ->setSaleType('Services')
                ->setSroScheduleNo('ICTO TABLE I')
                ->setSroItemSerialNo('1(ii)(ii)(a)')
                ->build();

            $invoice = (new InvoiceBuilder())
                ->setInvoiceType('Sale Invoice')
                ->setInvoiceDate('2025-04-21')
                ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                ->setBuyer('1000000000000', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                ->setScenarioId('SN019')
                ->setInvoiceRefNo('SI-20250421-001')
                ->addItem($item)
                ->build();

                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN020()
    {
        $results = [];
        try {
                        // Create the same invoice as above
                $item = (new InvoiceItemBuilder())
                    ->setHsCode('0101.2900')
                    ->setProductDescription('TEST')
                    ->setRate('1%')
                    ->setUom('Numbers, pieces, units')
                    ->setQuantity(122)
                    ->setTotalValues(0)
                    ->setValueSalesExcludingST(1000)
                    ->setFixedNotifiedValueOrRetailPrice(0)
                    ->setSalesTaxApplicable(10)
                    ->setSalesTaxWithheldAtSource(0)
                    ->setExtraTax(0)
                    ->setFurtherTax(0)
                    ->setFedPayable(0)
                    ->setDiscount(0)
                    ->setSaleType('Electric Vehicle')
                    ->setSroScheduleNo('6th Schd Table III')
                    ->setSroItemSerialNo('20')
                    ->build();

                $invoice = (new InvoiceBuilder())
                    ->setInvoiceType('Sale Invoice')
                    ->setInvoiceDate('2025-04-21')
                    ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                    ->setBuyer('1000000000000', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                    ->setScenarioId('SN020')
                    ->setInvoiceRefNo('SI-20250421-001')
                    ->addItem($item)
                    ->build();


                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN021()
    {
        $results = [];
        try {
                        // Create the same invoice as above
               $item = (new InvoiceItemBuilder())

                        ->setHsCode('0101.2100')
                        ->setProductDescription('TEST')
                        ->setRate('Rs.3')  // Fixed amount rate
                        ->setUom('Numbers, pieces, units')
                        ->setQuantity(12)
                        ->setTotalValues(0)
                        ->setValueSalesExcludingST(123)
                        ->setFixedNotifiedValueOrRetailPrice(0)
                        ->setSalesTaxApplicable(36)
                        ->setSalesTaxWithheldAtSource(0)
                        ->setExtraTax(0)
                        ->setFurtherTax(0)
                        ->setFedPayable(0)
                        ->setDiscount(0)
                        ->setSaleType('Cement /Concrete Block')
                        ->setSroScheduleNo('')
                        ->setSroItemSerialNo('')
                        ->build();

                    $invoice = (new InvoiceBuilder())
                        ->setInvoiceType('Sale Invoice')
                        ->setInvoiceDate('2025-04-21')
                        ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                        ->setBuyer('1000000000000', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                        ->setScenarioId('SN021')
                        ->setInvoiceRefNo('SI-20250421-001')
                        ->addItem($item)
                        ->build();


                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN022()
    {
        $results = [];
        try {
                        // Create the same invoice as above
                $item = (new InvoiceItemBuilder())
                        ->setHsCode('3104.2000')
                        ->setProductDescription('TEST')
                        ->setRate('18% along with rupees 60 per kilogram')
                        ->setUom('KG')
                        ->setQuantity(1)
                        ->setTotalValues(0)
                        ->setValueSalesExcludingST(100)
                        ->setFixedNotifiedValueOrRetailPrice(0)
                        ->setSalesTaxApplicable(78)
                        ->setSalesTaxWithheldAtSource(0)
                        ->setExtraTax(0)
                        ->setFurtherTax(0)
                        ->setFedPayable(0)
                        ->setDiscount(0)
                        ->setSaleType('Potassium Chlorate')
                        ->setSroScheduleNo('EIGHTH SCHEDULE Table 1')
                        ->setSroItemSerialNo('56')
                        ->build();

                    $invoice = (new InvoiceBuilder())
                        ->setInvoiceType('Sale Invoice')
                        ->setInvoiceDate('2025-04-21')
                        ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                        ->setBuyer('1000000000000', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                        ->setScenarioId('SN022')
                        ->setInvoiceRefNo('SI-20250421-001')
                        ->addItem($item)
                        ->build();

                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN023()
    {
        $results = [];
        try {
                        // Create the same invoice as above
               $item = (new InvoiceItemBuilder())
                            ->setHsCode('0101.2100')
                            ->setProductDescription('TEST')
                            ->setRate('Rs.200')
                            ->setUom('Numbers, pieces, units')
                            ->setQuantity(123)
                            ->setTotalValues(0)
                            ->setValueSalesExcludingST(234)
                            ->setFixedNotifiedValueOrRetailPrice(0)
                            ->setSalesTaxApplicable(24600)
                            ->setSalesTaxWithheldAtSource(0)
                            ->setExtraTax(0)
                            ->setFurtherTax(0)
                            ->setFedPayable(0)
                            ->setDiscount(0)
                            ->setSaleType('CNG Sales')
                            ->setSroScheduleNo('581(1)/2024')
                            ->setSroItemSerialNo('Region-I')
                            ->build();

                        $invoice = (new InvoiceBuilder())
                            ->setInvoiceType('Sale Invoice')
                            ->setInvoiceDate('2025-04-21')
                            ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                            ->setBuyer('1000000000000', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                            ->setScenarioId('SN023')
                            ->setInvoiceRefNo('SI-20250421-001')
                            ->addItem($item)
                            ->build();

                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN024()
    {
        $results = [];
        try {
                        // Create the same invoice as above
                            $item = (new InvoiceItemBuilder())
                                ->setHsCode('0101.2100')
                                ->setProductDescription('TEST')
                                ->setRate('25%')
                                ->setUom('Numbers, pieces, units')
                                ->setQuantity(123)
                                ->setTotalValues(0)
                                ->setValueSalesExcludingST(1000)
                                ->setFixedNotifiedValueOrRetailPrice(0)
                                ->setSalesTaxApplicable(250)
                                ->setSalesTaxWithheldAtSource(0)
                                ->setExtraTax(0)
                                ->setFurtherTax(0)
                                ->setFedPayable(0)
                                ->setDiscount(0)
                                ->setSaleType('Goods as per SRO.297(|)/2023')
                                ->setSroScheduleNo('297(I)/2023-Table-I')
                                ->setSroItemSerialNo('12')
                                ->build();

                            $invoice = (new InvoiceBuilder())
                                ->setInvoiceType('Sale Invoice')
                                ->setInvoiceDate('2025-04-21')
                                ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                                ->setBuyer('1000000000000', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                                ->setScenarioId('SN024')
                                ->setInvoiceRefNo('SI-20250421-001')
                                ->addItem($item)
                                ->build();


                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN025()
    {
        $results = [];
        try {
                        // Create the same invoice as above
               $item = (new InvoiceItemBuilder())
                    ->setHsCode('0101.2100')
                    ->setProductDescription('TEST')
                    ->setRate('0%')
                    ->setUom('Numbers, pieces, units')
                    ->setQuantity(1)
                    ->setTotalValues(0)
                    ->setValueSalesExcludingST(100)
                    ->setFixedNotifiedValueOrRetailPrice(0)
                    ->setSalesTaxApplicable(0)
                    ->setSalesTaxWithheldAtSource(0)
                    ->setExtraTax(0) // Converted from empty string to 0
                    ->setFurtherTax(0)
                    ->setFedPayable(0)
                    ->setDiscount(0)
                    ->setSaleType('Non-Adjustable Supplies')
                    ->setSroScheduleNo('EIGHTH SCHEDULE Table 1')
                    ->setSroItemSerialNo('81')
                    ->build();

                $invoice = (new InvoiceBuilder())
                    ->setInvoiceType('Sale Invoice')
                    ->setInvoiceDate('2025-05-16')
                    ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                    ->setBuyer('1000000000078', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                    ->setScenarioId('SN025')
                    ->setInvoiceRefNo('') // As per your JSON
                    ->addItem($item)
                    ->build();

                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN026()
    {
        $results = [];
        try {
                        // Create the same invoice as above
                $item = (new InvoiceItemBuilder())
                ->setHsCode('0101.2100')
                ->setProductDescription('TEST')
                ->setRate('18%')
                ->setUom('Numbers, pieces, units')
                ->setQuantity(123)
                ->setTotalValues(0)
                ->setValueSalesExcludingST(1000)
                ->setFixedNotifiedValueOrRetailPrice(0)
                ->setSalesTaxApplicable(180)
                ->setSalesTaxWithheldAtSource(0)
                ->setExtraTax(0)
                ->setFurtherTax(0)
                ->setFedPayable(0)
                ->setDiscount(0)
                ->setSaleType('Goods at standard rate (default)')
                ->setSroScheduleNo('')
                ->setSroItemSerialNo('')
                ->build();

            $invoice = (new InvoiceBuilder())
                ->setInvoiceType('Sale Invoice')
                ->setInvoiceDate('2025-05-16')
                ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                ->setBuyer('1350439930769', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                ->setScenarioId('SN026')
                ->setInvoiceRefNo('SI-20250421-001')
                ->addItem($item)
                ->build();

                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
     public function SN027()
    {
        $results = [];
        try {
                        // Create the same invoice as above
               $item = (new InvoiceItemBuilder())
                    ->setHsCode('0101.2100')
                    ->setProductDescription('test')
                    ->setRate('18%')
                    ->setUom('Numbers, pieces, units')
                    ->setQuantity(1)
                    ->setTotalValues(0)
                    ->setValueSalesExcludingST(0) // 0 because value is taken from fixed retail price
                    ->setFixedNotifiedValueOrRetailPrice(100)
                    ->setSalesTaxApplicable(18)
                    ->setSalesTaxWithheldAtSource(0)
                    ->setExtraTax(0)
                    ->setFurtherTax(0)
                    ->setFedPayable(0)
                    ->setDiscount(0)
                    ->setSaleType('3rd Schedule Goods')
                    ->setSroScheduleNo('')
                    ->setSroItemSerialNo('')
                    ->build();

                $invoice = (new InvoiceBuilder())
                    ->setInvoiceType('Sale Invoice')
                    ->setInvoiceDate('2025-05-10')
                    ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
                    ->setBuyer('7000006', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
                    ->setScenarioId('SN027')
                    ->setInvoiceRefNo('') // You can auto-generate this if needed
                    ->addItem($item)
                    ->build();


                            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];

        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }



        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }

}
