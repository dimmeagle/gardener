<?php

class Garden {

    public array $trees;
    public array $harvest;
    public array $culture;

    public function __construct () {

        $this->culture[] = 'apple';
        $this->culture[] = 'pea';
        $this->trees = array();
        $this->seed('apple', 10);
        $this->seed('pea', 15);
        $this->harvest = array(
            'apple' => array (
                0 => 0,
                1 => 0
            ),
            'pea' => array (
                0 => 0,
                1 => 0
            )
        );

    }

    public function __destruct() {

    }

    public function seed ($fruit, $qty) {

        if ($qty < 0) {
            if (count ($this->trees[$fruit]) <= abs ($qty)) {
                unset ($this->trees[$fruit]);
                unset ($this->harvest[$fruit]);
            } else {
                $numberOfTrees = count ($this->trees[$fruit]);
                for ($i = $numberOfTrees + $qty; $i < $numberOfTrees; $i++) {
                    unset ($this->trees[$fruit][$i]);
                }
            }
        } elseif ($qty > 0) {
            for ($i = 0; $i < $qty; $i++) {
                $this->trees[$fruit][] = array();
            }
        } else {
            return;
        }
    }

    public function harvest()
    {

        $fruitLimits = array(
            'apple' => array(
                'min' => 40,
                'max' => 50
            ),
            'pea' => array(
                'min' => 0,
                'max' => 20
            ),
            'other' => array(
                'min' => 0,
                'max' => 20
            )
        );

        $fruitWeights = array(
            'apple' => array(
                'min' => 150,
                'max' => 180
            ),
            'pea' => array(
                'min' => 130,
                'max' => 170
            ),
            'other' => array(
                'min' => 130,
                'max' => 180
            )
        );

        foreach ($this->trees as $treeName => &$trees) {
            $totalFruits = 0;
            foreach ($trees as &$tree) {
                $fruitsPerTree = array_key_exists($treeName, $fruitLimits) ?
                    rand($fruitLimits[$treeName]['min'], $fruitLimits[$treeName]['max']) :
                    rand($fruitLimits['other']['min'], $fruitLimits['other']['max']);
                $totalFruits += $fruitsPerTree;
                for ($i = 0; $i < $fruitsPerTree; $i++) {
                    $tree[] = array_key_exists($treeName, $fruitWeights) ?
                        rand($fruitWeights[$treeName]['min'], $fruitWeights[$treeName]['max']) :
                        rand($fruitWeights['other']['min'], $fruitWeights['other']['max']);
                }
                $this->harvest[$treeName][0] += count($tree);
                $this->harvest[$treeName][1] += array_sum($tree);
            }
        }
    }

    public function seedTrees() {

        $input  = trim(fread(STDIN, 100));
        if (!in_array($input, array_keys($this->culture)) && $input != 'q' && $input != count($this->culture)) {
            echo 'Wrong choice. Please try again' . PHP_EOL;
            return 1;
        } elseif (in_array ($input, array_keys($this->culture)) && $input != 'q') {
            echo 'Qty of trees: ';
            $qty = '';
            do {
                fscanf(STDIN, "%d", $qty);
                if (!is_numeric($qty)) {
                    echo 'Must be a number. Qty of trees: ';
                } else break;
            } while (is_numeric($qty));
            $this->seed($this->culture[$input], $qty);
            echo $qty . ' ' . $this->culture[$input] . ' trees added into the Garden.' . PHP_EOL;
            return 1;
        } elseif ($input == count ($this->culture)) {
            $this->newTree();
            return 1;
        }
        return 0;
    }

    public function listTrees() {

        foreach ($this->culture as $key => $tree) {
            echo "\t[" . $key . '] ' . $tree;
            if (array_key_exists($tree, $this->trees)) {
                echo ' (' . count($this->trees[$tree]) . ')' . PHP_EOL;
            } else {
                echo PHP_EOL;
            }
        }
        echo "\t[" . count ($this->culture) . '] add new tree type' . PHP_EOL;
        echo "\t[q] finish seeding trees" . PHP_EOL;
        echo 'Select a tree to seed: ';

    }

    public function newTree() {

        echo 'Name of new tree: ';
        fscanf (STDIN, "%s", $input);
        if (!in_array (trim($input), array_values($this->culture))) {
            $this->culture[] = trim($input);
        } else {
            echo 'This tree is already in the Garden. Seed something else' . PHP_EOL;
        }


    }

    public function reportHarvest() {

        if (!empty ($this->harvest))
            {
                echo 'Total harvest this time is:' . PHP_EOL;
                foreach ($this->harvest as $tree => $harvest) {
                    echo "\t" . $tree . ': ' . $harvest[0] . ' pcs (' . $harvest[1] / 1000 . ' kg)' . PHP_EOL;
                }
            } else echo 'The garden is empty. No fruits this time.'  . PHP_EOL;
    }
}
