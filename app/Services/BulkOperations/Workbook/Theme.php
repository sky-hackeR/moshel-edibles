<?php

namespace App\Services\BulkOperations\Workbook;

class Theme
{
    /*
    |--------------------------------------------------------------------------
    | Font Settings
    |--------------------------------------------------------------------------
    */

    public const FONT_FAMILY = 'Calibri';

    public const FONT_SIZE = 11;

    public const TITLE_FONT_SIZE = 18;

    public const SUBTITLE_FONT_SIZE = 14;

    public const HEADER_FONT_SIZE = 12;

    /*
    |--------------------------------------------------------------------------
    | Brand Colours
    |--------------------------------------------------------------------------
    */

    public const PRIMARY = '1F4E78';

    public const SECONDARY = '5B9BD5';

    public const SUCCESS = '70AD47';

    public const WARNING = 'FFC000';

    public const DANGER = 'C00000';

    public const INFO = '5B9BD5';

    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    public const HEADER_BACKGROUND = '1F4E78';

    public const HEADER_TEXT = 'FFFFFF';

    /*
    |--------------------------------------------------------------------------
    | Required / Optional Columns
    |--------------------------------------------------------------------------
    */

    public const REQUIRED_BACKGROUND = 'FFF2CC';

    public const OPTIONAL_BACKGROUND = 'DDEBF7';

    /*
    |--------------------------------------------------------------------------
    | Lookup Sheet
    |--------------------------------------------------------------------------
    */

    public const LOOKUP_BACKGROUND = 'E2EFDA';

    /*
    |--------------------------------------------------------------------------
    | Workbook Background
    |--------------------------------------------------------------------------
    */

    public const TITLE_BACKGROUND = 'D9EAD3';

    public const SUBTITLE_BACKGROUND = 'EAF3F8';

    /*
    |--------------------------------------------------------------------------
    | Borders
    |--------------------------------------------------------------------------
    */

    public const BORDER = 'BFBFBF';

    /*
    |--------------------------------------------------------------------------
    | Alternating Rows
    |--------------------------------------------------------------------------
    */

    public const TABLE_ROW_ODD = 'FFFFFF';

    public const TABLE_ROW_EVEN = 'F7F9FB';

    /*
    |--------------------------------------------------------------------------
    | Instruction Sheet
    |--------------------------------------------------------------------------
    */

    public const INSTRUCTION_TITLE = '1F4E78';

    public const INSTRUCTION_TEXT = '404040';

    /*
    |--------------------------------------------------------------------------
    | Comments
    |--------------------------------------------------------------------------
    */

    public const COMMENT_BACKGROUND = 'FFF2CC';

    /*
    |--------------------------------------------------------------------------
    | Icons / Legend Colours
    |--------------------------------------------------------------------------
    */

    public const LEGEND_REQUIRED = 'FFF2CC';

    public const LEGEND_OPTIONAL = 'DDEBF7';

    public const LEGEND_DROPDOWN = 'E2EFDA';

    public const LEGEND_SYSTEM = 'F4CCCC';

    /*
    |--------------------------------------------------------------------------
    | Default Sizes
    |--------------------------------------------------------------------------
    */

    public const DEFAULT_COLUMN_WIDTH = 20;

    public const TITLE_ROW_HEIGHT = 28;

    public const HEADER_ROW_HEIGHT = 22;

    public const DEFAULT_ROW_HEIGHT = 20;

    /*
    |--------------------------------------------------------------------------
    | Sheet Names
    |--------------------------------------------------------------------------
    */

    public const SHEET_TEMPLATE = 'Template';

    public const SHEET_LOOKUPS = 'Lookups';

    public const SHEET_INSTRUCTIONS = 'Instructions';

    public const SHEET_ABOUT = 'About';

    /*
    |--------------------------------------------------------------------------
    | Workbook Information
    |--------------------------------------------------------------------------
    */

    public const COMPANY_NAME = 'MOSHEL Inventory System';

    public const TEMPLATE_VERSION = '3.0';
}