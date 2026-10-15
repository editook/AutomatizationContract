<?php

function amountToEnglishWords(string $amount): string
{
    $amount = preg_replace('/[$,\s]/', '', trim($amount));

    if (!is_string($amount) || !preg_match('/^\d+(?:\.\d{1,2})?$/D', $amount)) {
        throw new InvalidArgumentException('The amount must be a non-negative number with up to two decimal places.');
    }

    [$wholePart, $fractionPart] = array_pad(explode('.', $amount, 2), 2, '');
    $wholePart = ltrim($wholePart, '0') ?: '0';

    $maxInteger = (string) PHP_INT_MAX;
    if (strlen($wholePart) > strlen($maxInteger)
        || (strlen($wholePart) === strlen($maxInteger) && strcmp($wholePart, $maxInteger) > 0)) {
        throw new InvalidArgumentException('The amount exceeds the supported range.');
    }

    $wholeNumber = (int) $wholePart;
    $cents = str_pad($fractionPart, 2, '0');
    $currency = $wholeNumber === 1 ? 'US Dollar' : 'US Dollars';

    return ucfirst(modelConfigurationNumberToWords($wholeNumber))
        . ' and ' . $cents . '/100 ' . $currency;
}

function modelConfigurationNumberToWords(int $number): string
{
    $ones = [
        'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
        'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen',
        'seventeen', 'eighteen', 'nineteen',
    ];
    $tens = [
        2 => 'twenty', 3 => 'thirty', 4 => 'forty', 5 => 'fifty',
        6 => 'sixty', 7 => 'seventy', 8 => 'eighty', 9 => 'ninety',
    ];

    if ($number < 20) {
        return $ones[$number];
    }

    if ($number < 100) {
        $word = $tens[intdiv($number, 10)];
        $remainder = $number % 10;

        return $remainder === 0 ? $word : $word . '-' . $ones[$remainder];
    }

    if ($number < 1000) {
        $word = $ones[intdiv($number, 100)] . ' hundred';
        $remainder = $number % 100;

        return $remainder === 0 ? $word : $word . ' ' . modelConfigurationNumberToWords($remainder);
    }

    $scales = [
        1_000_000_000_000_000_000 => 'quintillion',
        1_000_000_000_000_000 => 'quadrillion',
        1_000_000_000_000 => 'trillion',
        1_000_000_000 => 'billion',
        1_000_000 => 'million',
        1_000 => 'thousand',
    ];

    foreach ($scales as $scale => $scaleName) {
        if ($number >= $scale) {
            $wholeGroups = intdiv($number, $scale);
            $remainder = $number % $scale;
            $words = modelConfigurationNumberToWords($wholeGroups) . ' ' . $scaleName;

            return $remainder === 0
                ? $words
                : $words . ' ' . modelConfigurationNumberToWords($remainder);
        }
    }

    return '';
}
