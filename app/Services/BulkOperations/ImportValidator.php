<?php

namespace App\Services\BulkOperations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ImportValidator
{
    /**
     * Validate an import request.
     *
     * @throws ValidationException
     */
    public static function validate(
        Request $request,
        string $module
    ): void {

        if (! BulkRegistry::exists($module)) {

            throw ValidationException::withMessages([

                'module' => [
                    'Invalid import module.'
                ],

            ]);

        }

        $extensions = implode(',', BulkRegistry::extensions($module));

        $maxSize = BulkRegistry::maxSize($module);

        Validator::make(

            $request->all(),

            [

                'file' => [
                    'required',
                    'file',
                    "mimes:$extensions",
                    "max:$maxSize",
                ],

            ],

            [

                'file.required' => 'Please choose a file to upload.',

                'file.file' => 'The uploaded item must be a valid file.',

                'file.mimes' => 'Supported file types: ' . strtoupper(str_replace(',', ', ', $extensions)),

                'file.max' => 'The file may not be larger than ' . ($maxSize / 1024) . ' MB.',

            ]

        )->validate();
    }
}