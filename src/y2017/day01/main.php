<?php
/**
 * The night before Christmas, one of Santa's Elves calls you in a panic. "The printer's broken! We can't print the
 * Naughty or Nice List!" By the time you make it to sub-basement 17, there are only a few minutes until midnight. "We
 * have a big problem," she says; "there must be almost fifty bugs in this system, but nothing else can print The List.
 * Stand in this square, quick! There's no time to explain; if you can convince them to pay you in stars, you'll be
 * able to--" She pulls a lever and the world goes blurry.
 *
 * When your eyes can focus again, everything seems a lot more pixelated than before. She must have sent you inside the
 * computer! You check the system clock: 25 milliseconds until midnight. With that much time, you should be able to
 * collect all fifty stars by December 25th.
 *
 * Collect stars by solving puzzles. Two puzzles will be made available on each day millisecond in the advent calendar;
 * the second puzzle is unlocked when you complete the first. Each puzzle grants one star. Good luck!
 *
 * You're standing in a room with "digitization quarantine" written in LEDs along one wall. The only door is locked,
 * but it includes a small interface. "Restricted Area - Strictly No Digitized Users Allowed."
 *
 * It goes on to explain that you may only leave by solving a captcha to prove you're not a human. Apparently, you only
 * get one millisecond to solve the captcha: too fast for a normal human, but it feels like hours to you.
 *
 * The captcha requires you to review a sequence of digits (your puzzle input) and find the sum of all digits that
 * match the next digit in the list. The list is circular, so the digit after the last digit is the first digit in the
 * list.
 *
 * For example:
 *
 * - 1122 produces a sum of 3 (1 + 2) because the first digit (1) matches the second digit and the third digit (2)
 * matches the fourth digit.
 * - 1111 produces 4 because each digit (all 1) matches the next.
 * - 1234 produces 0 because no digit matches the next.
 * - 91212129 produces 9 because the only digit that matches the next one is the last digit, 9.
 *
 * What is the solution to your captcha?
 */

$test1 = '1212';
$test2 = '1221';
$test3 = '123425';
$test4 = '123123';
$test5 = '12131415';

$list = '34997744892914653296827871613388552993634935173733597474997393431324121718942484674492133736486619515246829248477836544451943938832848157199224116563715646126431493563772112714741546635764665586452858349326658345524573681224829221829772728531278893357146638772291782796744812479595172578555931968285326741191558735491923682586844185476584124677856856612582263263124715916498254659761312225295947328671873729594182695425852559718922816832816341259695766322357565252335851264933471555351536363944572763621761489944217787785564355131756948331413652646811626742168857634856234347432698931371757454156396432993421795675147273229642441888776517165375965288923515378871773449714189311167849788519479274172617334378412661574885156988171532483385528342851358599792154331889342985168528186562873736117113242271863318873917355428393173152783223727362282169982597123525671895452937118687191281382949335937173323862618172284254741935865963877359477126188879481911148827453781546789437317581568931445259912541273353345254171252588344612386649134562638758915336976347291218848744548755462493981871543949697331735577243658722111371552363179584543521149944247848176793571855164329415143753479297879926959141597695174674386467854776481689314612324534729187335368471697738925271618243312864656442299938886755679996568297498965651652337961837876468596749433454633975722561971935459554979713344313292511447288939379369279487299557326137798219646395436241742751581363752896833892713543627966633788455384129347637693559713174477262914916598991823983686226378396341554219544683439536933338185723832743964258335163993324191589246399535845434167819135413916443764931668386817282279877264296262823999224943974974489892778799656723453849139194948368998995531261224669478559359689167934624681622834931223728318247832134758581882736415334187562342375144693398771223127132562692525629392889723242374746911936313136382354858767169452656224519128287899264831463597663461857119132312578648894815417348364532372836621644176295776978942783714778954864719541832176633892147845693752248565147794357864859961462918847471158244516279178346514129117328285132341339595664283';

/**
 * Provide a sum of all digits where the position matches $jump places ahead.
 *
 * @param string $list String list.
 * @param int    $jump Jump position.
 *
 * @return int
 */
function sum_list($list, $jump)
{
    $length = strlen($list);
    $last   = $length - 1;
    $to_sum = [];

    for ($i = 0; $i < $length; $i++) {
        // Handle items looping around.
        $compare = $i + $jump;
        if ($compare > $last) {
            $compare -= $length;
        }

        if ($list[$i] === $list[$compare]) {
            $to_sum[] = (int) $list[$i];
        }
    }

    return array_sum($to_sum);
}

$sum  = sum_list($list, 1);
$sum2 = sum_list($list, strlen($list) / 2);

echo "First sum is [{$sum}]\n";
echo "Second sum is [{$sum2}]\n";

foreach (range(1, 5) as $item) {
    $test_list = ${"test{$item}"};
    echo "test {$item}: " . sum_list($test_list, strlen($test_list) / 2) . PHP_EOL;
}

