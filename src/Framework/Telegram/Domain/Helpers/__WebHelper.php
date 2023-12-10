<?php

namespace Untek\Framework\Telegram\Domain\Helpers;

class WebHelper
{

    public static function getDocumentHead(): string
    {
        return <<<HTML
<!doctype html >
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
HTML;
    }

    public static function getDocumentFoot(): string
    {
        return <<<HTML
</body>
</html>
HTML;
    }

}