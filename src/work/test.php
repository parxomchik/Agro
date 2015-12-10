<?php

function pdf3text($filename) {

    // Read the data from pdf file
    $infile = @file_get_contents($filename, FILE_BINARY);
    if (empty($infile))
        return "";

    // Get all text data.
    $transformations = array();
    $texts = array();

    // Get the list of all objects.
    preg_match_all("#obj(.*)endobj#ismU", $infile, $objects);
    $objects = @$objects[1];

    // Select objects with streams.
    for ($i = 0; $i < count($objects); $i++) {
        $currentObject = $objects[$i];

        // Check if an object includes data stream.
        if (preg_match("#stream(.*)endstream#ismU", $currentObject, $stream)) {
            $stream = ltrim($stream[1]);

            // Check object parameters and look for text data.
            $options = getObjectOptions($currentObject);
            if (!(empty($options["Length1"]) && empty($options["Type"]) && empty($options["Subtype"])))
                continue;

            // So, we have text data. Decode it.
            $data = getDecodedStream($stream, $options);
            if (strlen($data)) {
                if (preg_match_all("#BT(.*)ET#ismU", $data, $textContainers)) {
                    $textContainers = @$textContainers[1];
                    getDirtyTexts($texts, $textContainers);
                } else
                    getCharTransformations($transformations, $data);
            }
        }

    }

    // Analyze text blocks taking into account character transformations and return results.
    return getTextUsingTransformations($texts, $transformations);
}

include(dirname(__FILE__).'/../../kernel/pdf2text.php');
$a = new PDF2Text();
$a->setFilename(dirname(__FILE__).'/../../../InformDovidka.pdf'); //grab the test file at http://www.newyorklivearts.org/Videographer_RFP.pdf
$a->decodePDF();
// echo $a->output();

echo pdf3text(dirname(__FILE__).'/../../../InformDovidka.pdf');

?>