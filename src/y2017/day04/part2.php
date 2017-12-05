<?php
/**
 * For added security, yet another system policy has been put in place. Now, a valid passphrase must contain no two
 * words that are anagrams of each other - that is, a passphrase is invalid if any word's letters can be rearranged to
 * form any other word in the passphrase.
 *
 * For example:
 *
 * abcde fghij is a valid passphrase.
 * abcde xyz ecdab is not valid - the letters from the third word can be rearranged to form the first word.
 * a ab abc abd abf abj is a valid passphrase, because all letters need to be used when forming another word.
 * iiii oiii ooii oooi oooo is valid.
 * oiii ioii iioi iiio is not valid - any of these words can be rearranged to form any other word.
 * Under this new system policy, how many passphrases are valid?
 */

namespace JPry\AdventOfCode\y2017\day04;

function makeArray(string $phrases): array
{
    $phrasesArray = array_filter(explode("\n", $phrases));
    foreach ($phrasesArray as &$phrase) {
        $phrase = array_filter(explode(' ', $phrase));
    }

    return $phrasesArray;
}

function getValid(array $phrases): array
{
    foreach ($phrases as $key => $phrase) {
        if (containsDuplicateWord($phrase)) {
            unset($phrases[$key]);
        }
    }

    return $phrases;
}

function secureValid(array $phrases): array
{
    foreach ($phrases as $key => $phrase) {
        // Convert each word to a sorted array.
        foreach ($phrase as &$word) {
            $word = str_split($word, 1);
            sort($word);
        }

        if (containsDuplicateWord($phrase)) {
            unset($phrases[$key]);
        }
    }

    return $phrases;
}

function containsDuplicateWord(array $phrase): bool
{
    do {
        $check = array_shift($phrase);
        if (in_array($check, $phrase)) {
            return true;
        }
    } while (!empty($phrase));

    return false;
}

$test1 = <<< TEST
abcde fghij
abcde xyz ecdab
a ab abc abd abf abj
iiii oiii ooii oooi oooo
oiii ioii iioi iiio
TEST;

$input  = file_get_contents(__DIR__ . '/input.txt');
$valid  = getValid(makeArray($input));
$secure = secureValid($valid);

echo 'input valid: ' . count($valid) . PHP_EOL;
echo 'test valid: ' . count(secureValid(getValid(makeArray($test1)))) . PHP_EOL;
echo 'secure valid: ' . count($secure) . PHP_EOL;
