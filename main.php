<?php
    include ('garden.class.php');
    $input = '';
    echo 'Hi, new Gardener!' . PHP_EOL;
do {
    echo 'Want to start a new garden? (Y/N) [Y]? ';
    fscanf(STDIN, "%s", $answer);
    if (empty ($answer) || in_array(strtolower(trim($answer)), array ('y', 'yes'))) {
        $garden = null;
        $garden = new Garden;
        echo 'The Garden is created!' . PHP_EOL;
        sleep(1);
        echo 'Let\'s seed it with trees:' . PHP_EOL;
        do {
            $garden->listTrees();
            $input = $garden->seedTrees();
        } while ($input);
        $garden->harvest();
        $garden->reportHarvest();
    } elseif (in_array(strtolower(trim($answer)), array ('n', 'no', 'nope'))) {
        $answer = 'no';
    } else {
        echo 'I did\'t get it. ';
    }
} while ($answer != 'no');
echo 'Bye!' . PHP_EOL;
?>
