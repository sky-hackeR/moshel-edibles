<?php

namespace App\Services\BulkOperations;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportProcessor
{
    /**
     * Process a bulk import.
     */
    public function process(
        string $module,
        UploadedFile $file
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Validate Module
        |--------------------------------------------------------------------------
        */

        if (!BulkRegistry::exists($module)) {

            throw new Exception(
                "Bulk module [$module] is not registered."
            );

        }

        $config = BulkRegistry::module($module);

        /*
        |--------------------------------------------------------------------------
        | Validate File Extension
        |--------------------------------------------------------------------------
        */

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        if (!in_array($extension, $config['extensions'])) {

            throw new Exception(
                "Only ".implode(', ', $config['extensions'])." files are allowed."
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Validate File Size
        |--------------------------------------------------------------------------
        */

        if (
            $file->getSize() >
            ($config['max_size'] * 1024)
        ) {

            throw new Exception(
                "Maximum upload size is {$config['max_size']} KB."
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Create Import Log
        |--------------------------------------------------------------------------
        */

        $log = BulkLogger::start(

            'import',

            $module,

            $file->getClientOriginalName(),

            request()

        );

        try {

            /*
            |--------------------------------------------------------------------------
            | Store Uploaded File
            |--------------------------------------------------------------------------
            */

            $path = $file->store(

                'bulk/imports',

                'public'

            );

            BulkLogger::file(

                $log,

                $path

            );

            /*
            |--------------------------------------------------------------------------
            | Execute Import
            |--------------------------------------------------------------------------
            */

            $importClass = BulkRegistry::import($module);

            $import = app($importClass);

            Excel::import(

                $import,

                Storage::disk('public')->path($path)

            );

            /*
            |--------------------------------------------------------------------------
            | Get Summary
            |--------------------------------------------------------------------------
            */

            $summary = method_exists(
                $import,
                'summary'
            )

                ? $import->summary()

                : [

                    'total' => 0,

                    'successful' => 0,

                    'failed' => 0,

                    'metadata' => [],

                ];

            /*
            |--------------------------------------------------------------------------
            | Update Log
            |--------------------------------------------------------------------------
            */

            BulkLogger::success(

                $log,

                $summary['total'],

                $summary['successful'],

                $summary['failed'],

                $summary['metadata']

            );

            /*
            |--------------------------------------------------------------------------
            | Return Summary
            |--------------------------------------------------------------------------
            */

            return [

                'success' => true,

                'message' => BulkRegistry::label($module).' imported successfully.',

                'summary' => $summary,

                'log' => $log,

            ];

        } catch (Exception $e) {

            BulkLogger::failed(

                $log,

                $e->getMessage()

            );

            throw $e;

        }

    }
}