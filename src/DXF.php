<?php


namespace SNicholson\PHPDocxTemplates;


class DXF
{
    const BOLD_START = '#~~#DXF_BOLD_START#~~#';
    const BOLD_END = '#~~#DXF_X_BOLD_END#~~#';
    const UNDERLINE_START = '#~~#DXF_UNDERLINE_START#~~#';
    const UNDERLINE_END = '#~~#DXF_X_UNDERLINE_END#~~#';
    const LINEBREAK = '#~~#DXF_X_LINEBREAK#~~#';

    public static function getAsArrayWithReplacements()
    {
        return [
            self::BOLD_START      => '</w:t></w:r><w:r><w:rPr><w:b/><w:bCs/></w:rPr><w:t>',
            self::BOLD_END        => "</w:t></w:r><w:r><w:t>",
            self::UNDERLINE_START => '<w:rPr><w:u /><w:t xml:space="preserve">',
            self::UNDERLINE_END   => "</w:t></w:rPr>",
            self::LINEBREAK       => "<w:br/>",
        ];
    }
}