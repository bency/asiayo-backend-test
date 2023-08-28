<?php

namespace App\Models;

use InvalidArgumentException;

class Currency
{
    protected $currencies = null;
    protected $source = '';
    protected $target = '';
    protected $source_amount = 0;

    public function __construct()
    {
        $this->currencies = json_decode('{ "currencies": { "TWD": { "TWD": 1, "JPY": 3.669, "USD": 0.03281 }, "JPY": { "TWD": 0.26956, "JPY": 1, "USD": 0.00885 }, "USD": { "TWD": 30.444, "JPY": 111.801, "USD": 1 } } }', true)['currencies'];
    }

    public function getCurrencies()
    {
        return $this->currencies;
    }

    public function setSource($source, $amount)
    {
        if (!in_array($source, array_keys($this->currencies))) {
            throw new InvalidArgumentException();
        }
        $this->source = $source;
        $this->source_amount = $this->filterAmount($amount);
        return $this;
    }

    public function setTarget($target)
    {
        if (!in_array($target, array_keys($this->currencies))) {
            throw new InvalidArgumentException();
        }

        $this->target = $target;
        return $this;
    }

    private function getCurrency()
    {
        return $this->currencies[$this->source][$this->target];
    }

    private function filterAmount($amount)
    {
        return str_replace(',', '', str_replace('$', '', $amount));
    }

    public function transferCurrency()
    {
        $currency = $this->getCurrency();
        $amount = round($this->source_amount * $currency, 2);
        return $this->format($amount);
    }

    public function format($amount)
    {
        $integers = explode('.', $amount)[0];
        $floats = explode('.', $amount)[1] ?? '';
        $format_integers = [];
        $last_index = 0;
        foreach (array_reverse(str_split($integers)) as $i => $digit) {
            $last_index++;
            $format_integers[] = $digit;
            if ($i % 3 == 2) {
                $format_integers[] = ',';
                $last_index++;
            }
        }
        if (',' == $format_integers[$last_index - 1]) {
            array_pop($format_integers);
        }
        $format_integers[] = '$';
        $integers = implode('', array_reverse($format_integers));
        if ($floats) {
            return sprintf("%s.%s", $integers, $floats);
        }
        return $integers;
    }
}
