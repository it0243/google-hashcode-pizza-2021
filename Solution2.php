<?php

/**
 * This is a deviation of Solution.php.
 * It tries to calculate the next pizza to choose based on maximum new ingredients.
 */

$FILES = [
  'a_example.in',
  'b_little_bit_of_everything.in',
  'c_many_ingredients.in',
  'd_many_pizzas.in',
  'e_many_teams.in',
];

$total_score = 0;
foreach ($FILES as $FILE) {
  // READ INPUT
  $in = fopen($FILE, "r");

  list($num_pizzas, $T2, $T3, $T4) = explode(' ', trim(fgets($in)));

  $pizzas = [];
  $deliveries = [];
  $all_ingredients = [];
  $file_score = 0;
  $available_teams_map = [
    4 => $T4,
    3 => $T3,
    2 => $T2,
  ];

  for ($i = 0; $i < $num_pizzas; $i++) {
    // Get ingredients as an array
    $pizza_description = explode(' ', trim(fgets($in)));
    // Remove the first element which is the number of ingredients
    $ingredient_count = array_shift($pizza_description);
    $pizzas[$i] = [
      'id' => $i,
      'ingredients' => $pizza_description,
      'count' => $ingredient_count
    ];
  }
  fclose($in);

  $available_pizzas = $pizzas;
  usort($available_pizzas, function ($a, $b) {
    return $a['count'] < $b['count'];
  });

  // iterate for all team sizes starting from larger
  for ($team_size=4; $team_size>=2; $team_size--) {
    $available_teams = $available_teams_map[$team_size];
    // 1. there should be more available pizzas than the team size (delivery count of pizzas)
    // 2. also do not leave one pizza out, aka if available pizzas are 5, choose 2+3 instead of 4+1, as the one left will remain unused
    // 3. the available teams for a specific team size should be > 0
    $calculated = false;
    while (count($available_pizzas) >= $team_size && count($available_pizzas)- $team_size <> 1 && $available_teams > 0) {
      $available_teams--;
      $delivery_pizzas = [];
      $delivery_ingredients = [];
      for ($i = 0; $i < $team_size; $i++) {
        // the first pizza of a delivery can be the first from the ordered by size (desc) pizzas list
        // the rest pizzas of the delivery should be selected based on an optimization algorithm
        if ($i >= 1) {
          $pizza = find_pizza_with_max_ingredients($delivery_ingredients, $available_pizzas, $num_pizzas);
          $calculated == true;
        } else {
          $pizza = array_shift($available_pizzas);
        }
        $delivery_pizzas[] = $pizza['id'];
        $delivery_ingredients = array_unique(array_merge($delivery_ingredients, $pizza['ingredients']));
      }
      // score of the delivery is the square of unique ingredients
      $delivery_score = pow(count($delivery_ingredients), 2);
      $file_score += $delivery_score;
      $deliveries[] = [
        'size' => count($delivery_pizzas),
        'pizzas' => $delivery_pizzas,
      ];
    }
  }
  $total_score += $file_score;
  d("$FILE: " . n($file_score));

  // WRITE OUTPUT
  $out = fopen(pathinfo($FILE, PATHINFO_FILENAME) . '.out', 'w');
  $deliveries_count = count($deliveries);
  fputs($out, "$deliveries_count\n");

  foreach($deliveries as $delivery) {
    $size = $delivery['size'];
    $pizzas = implode(' ', $delivery['pizzas']);
    fputs($out, "$size $pizzas\n");
  }
  fclose($out);
}
d("TOTAL: " . n($total_score));

/**
 * Formats numbers with thousand seperator ,
 */
function n($number) {
  return number_format($number, 0, '', ',');
}

/**
 * Returns a pizza from the available pizzas list that optimizes the delivery's ingredients.
 * It uses 2 different algorithms for the selection of the best pizza.
 * First algorithm is for problems with many pizzas (>1000 -> files C, D & E).
 * It selects the pizza with the more new ingredients, compared to the delivery's ingredients.
 * Second algorithm is for problems with fewer pizzas (<1000 -> files A & B).
 * It selects the pizza with the less wasted ingredients percentage, compared to the delivery's ingredients.
 */
function find_pizza_with_max_ingredients($delivery_ingredients, &$available_pizzas, $num_pizzas) {
  if ($num_pizzas > 1000) {
    usort($available_pizzas, function ($a, $b) use ($delivery_ingredients) {
      return count_new_in_pizza($a, $delivery_ingredients) < count_new_in_pizza($b, $delivery_ingredients);
    });
  } else {
    usort($available_pizzas, function ($a, $b) use ($delivery_ingredients) {
      return percentage_wasted_in_pizza($a, $delivery_ingredients) > percentage_wasted_in_pizza($b, $delivery_ingredients);
    });
  }
  return array_shift($available_pizzas);
}

function count_new_in_pizza($pizza, $delivery_ingredients) {
  return count(array_diff($pizza['ingredients'], $delivery_ingredients));
}

function percentage_wasted_in_pizza($pizza, $delivery_ingredients) {
  return count(array_intersect($pizza['ingredients'], $delivery_ingredients)) / $pizza['count'];
}

function d($output) {
  if (is_string($output)) {
    echo $output . "\n";
  } else {
    print_r($output);
  }
}
