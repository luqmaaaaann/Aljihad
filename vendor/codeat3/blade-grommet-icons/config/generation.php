<?php

use Codeat3\BladeIconGeneration\IconProcessor;

$svgNormalization = static function (string $tempFilepath, array $iconSet) {

    // perform generic optimizations
    $iconProcessor = new IconProcessor($tempFilepath, $iconSet);
    $iconProcessor
        ->optimize()
        ->postOptimizationAsString(function ($svgLine){
            $replacePattern = [
                '/stroke="#[0-9A-Za-z\s]+"/s' => 'stroke="currentColor"',
                '/fill\="\#[0-9A-Za-z\s]+"/s' => 'fill="currentColor"',
                '/fill\="black"/s' => 'fill="currentColor"',
                '/stroke\="black"/s' => 'stroke="currentColor"',
            ];
            return preg_replace(array_keys($replacePattern), array_values($replacePattern), $svgLine);
        })
        ->save();
};

return [
    [
        // Define a source directory for the sets like a node_modules/ or vendor/ directory...
        'source' => __DIR__.'/../dist/public/img',

        // Define a destination directory for your icons. The below is a good default...
        'destination' => __DIR__.'/../resources/svg',

        // Enable "safe" mode which will prevent deletion of old icons...
        'safe' => false,

        // Call an optional callback to manipulate the icon
        // with the pathname of the icon and the settings from above...
        'after' => $svgNormalization,

        'is-solid' => true,
    ],
];
