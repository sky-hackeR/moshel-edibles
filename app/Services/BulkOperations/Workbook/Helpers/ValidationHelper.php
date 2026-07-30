<?php

namespace App\Services\BulkOperations\Workbook\Helpers;

use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ValidationHelper
{
    /**
     * ---------------------------------------------------------
     * Create a base validation object
     * ---------------------------------------------------------
     */
    public static function make(): DataValidation
    {
        $validation = new DataValidation();

        $validation->setAllowBlank(true);

        $validation->setShowInputMessage(true);

        $validation->setShowErrorMessage(true);

        $validation->setErrorStyle(
            DataValidation::STYLE_STOP
        );

        return $validation;
    }

    /**
     * ---------------------------------------------------------
     * Dropdown Validation
     * ---------------------------------------------------------
     */
    public static function list(
        string $formula
    ): DataValidation {

        $validation = self::make();

        $validation->setType(
            DataValidation::TYPE_LIST
        );

        $validation->setFormula1($formula);

        $validation->setShowDropDown(true);

        return $validation;
    }

    /**
     * ---------------------------------------------------------
     * Whole Number
     * ---------------------------------------------------------
     */
    public static function wholeNumber(): DataValidation
    {
        $validation = self::make();

        $validation->setType(
            DataValidation::TYPE_WHOLE
        );

        return $validation;
    }

    /**
     * ---------------------------------------------------------
     * Decimal
     * ---------------------------------------------------------
     */
    public static function decimal(): DataValidation
    {
        $validation = self::make();

        $validation->setType(
            DataValidation::TYPE_DECIMAL
        );

        return $validation;
    }

    /**
     * ---------------------------------------------------------
     * Date
     * ---------------------------------------------------------
     */
    public static function date(): DataValidation
    {
        $validation = self::make();

        $validation->setType(
            DataValidation::TYPE_DATE
        );

        return $validation;
    }

    /**
     * ---------------------------------------------------------
     * Text Length
     * ---------------------------------------------------------
     */
    public static function textLength(
        int $maximum
    ): DataValidation {

        $validation = self::make();

        $validation->setType(
            DataValidation::TYPE_TEXTLENGTH
        );

        $validation->setOperator(
            DataValidation::OPERATOR_LESSTHANOREQUAL
        );

        $validation->setFormula1($maximum);

        return $validation;
    }

    /**
     * ---------------------------------------------------------
     * Between
     * ---------------------------------------------------------
     */
    public static function between(
        DataValidation $validation,
        $minimum,
        $maximum
    ): DataValidation {

        $validation->setOperator(
            DataValidation::OPERATOR_BETWEEN
        );

        $validation->setFormula1($minimum);

        $validation->setFormula2($maximum);

        return $validation;
    }

    /**
     * ---------------------------------------------------------
     * Prompt
     * ---------------------------------------------------------
     */
    public static function prompt(
        DataValidation $validation,
        string $title,
        string $message
    ): DataValidation {

        $validation->setPromptTitle($title);

        $validation->setPrompt($message);

        return $validation;
    }

    /**
     * ---------------------------------------------------------
     * Error
     * ---------------------------------------------------------
     */
    public static function error(
        DataValidation $validation,
        string $title,
        string $message
    ): DataValidation {

        $validation->setErrorTitle($title);

        $validation->setError($message);

        return $validation;
    }
}