# Package

[![Latest Version](https://img.shields.io/github/release/networkteam/json-seq.svg?style=flat-square)](https://github.com/networkteam/json-seq/releases)
[![Build Status](https://img.shields.io/travis/networkteam/json-seq.svg?style=flat-square)](https://travis-ci.org/networkteam/json-seq)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/networkteam/json-seq.svg?style=flat-square)](https://scrutinizer-ci.com/g/networkteam/json-seq)
[![Quality Score](https://img.shields.io/scrutinizer/g/networkteam/json-seq.svg?style=flat-square)](https://scrutinizer-ci.com/g/networkteam/json-seq)
[![Total Downloads](https://img.shields.io/packagist/dt/networkteam/json-seq.svg?style=flat-square)](https://packagist.org/packages/networkteam/json-seq)

**JSON Text Sequences (RFC7464) encoder and decoder for PHP**

JSON Text Sequences define a streamable JSON encoding and parsing based on a delimiter for multiple JSON encoded texts.

See https://tools.ietf.org/html/rfc7464 for the JSON Text Sequences RFC.

## Install

Via Composer

``` bash
$ composer require networkteam/json-seq
```


## Usage

Encoding data in JSON Text Sequences: 

``` php
$stringWriter = new StringWriter();
$encoder = new Encoder($stringWriter);

$encoder->emit(['id' => '1', ...]);
$encoder->emit(['id' => '2', ...]);

$result = $stringWriter->getString();
```

Decoding JSON Text Sequences from a string:

``` php
$jsonTexts = "\x1E{...}\x0A\x1E{...}\x0A";

$decoder = new StringDecoder();
foreach ($decoder->decode($jsonTexts) as $data) {
    // process $data
}
```

## Testing

``` bash
$ composer test
```


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
