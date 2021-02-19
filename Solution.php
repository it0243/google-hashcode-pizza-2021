<?php

$FILES = [
  'a_example.in',
  'b_little_bit_of_everything.in',
  'c_many_ingredients.in',
  'd_many_pizzas.in',
  'e_many_teams.in',
];

foreach ($FILES as $FILE) {
  $in = fopen($FILE, "r");

  list($num_pizzas, $T2, $T3, $T4) = explode(' ', trim(fgets($in)));

  $pizzas = [];
  $all_ingredients = [];

  for ($i = 0; $i < $num_pizzas; $i++) {
    // Get ingredients as an array
    $pizza_description = explode(' ', trim(fgets($in)));
    // Remove the first element which is the number of ingredients
    $ingredient_count = array_shift($pizza_description);
    $pizzas[$i] = [
      'id' => $i,
      'ingredients' => $pizza_description,
      'count' => $ingredient_count,
    ];
    $all_ingredients = array_merge($all_ingredients, $pizza_description);
  }
  fclose($in);

  $all_ingredients = array_unique($all_ingredients);

  $deliveries = [];
  $deliveries_count = 0;
  $available_pizzas = $pizzas;
  $total_score = 0;
  usort($available_pizzas, function ($a, $b) {
    return $a['count'] < $b['count'];
  });

  $available_teams_map = [
    4 => $T4,
    3 => $T3,
    2 => $T2,
  ];
  // iterate for all team sizes starting from larger
  for ($team_size=4; $team_size>=2; $team_size--) {
    $available_teams = $available_teams_map[$team_size];
    // 1. there should be more available pizzas than the team size (delivery count of pizzas)
    // 2. also do not leave one pizza out, aka if available pizzas are 5, choose 2+3 instead of 4+1, as the one left will remain unused
    // 3. the available teams for a specific team size should be > 0
    while (count($available_pizzas) >= $team_size && count($available_pizzas)- $team_size <> 1 && $available_teams > 0) {
      $available_teams--;
      $delivery_pizzas = [];
      $delivery_ingredients = [];
      for ($i = 0; $i < $team_size; $i++) {
        // $pizza = find_pizza_with_max_ingredients($delivery_ingredients, $all_ingredients, $available_pizzas);
        $pizza = array_shift($available_pizzas);
        $delivery_pizzas[] = $pizza['id'];
        $delivery_ingredients = array_unique(array_merge($delivery_ingredients, $pizza['ingredients']));
      }
      // score of the delivery is the square of unique ingredients
      $delivery_score = pow(count($delivery_ingredients), 2);
      $total_score += $delivery_score;
      $deliveries[] = [
        'id' => $deliveries_count++,
        'size' => count($delivery_pizzas),
        'pizzas' => $delivery_pizzas,
        'ingredients_count' => count($delivery_ingredients)
      ];
    }
  }
  d("$FILE: $total_score");

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

function find_pizza_with_max_ingredients($delivery_ingredients, $all_ingredients, &$available_pizzas) {
  $missing_ingredients = array_intersect($all_ingredients, $delivery_ingredients);
  usort($available_pizzas, function ($a, $b) use ($missing_ingredients) {
    return count_missing_in_pizza($a, $missing_ingredients) < count_missing_in_pizza($b, $missing_ingredients);
  });
  return array_shift($available_pizzas);
}

function count_missing_in_pizza($pizza, $missing_ingredients) {
  return count(array_intersect($pizza['ingredients'], $missing_ingredients));
}

function d($output) {
  if (is_string($output)) {
    echo $output . "\n";
  } else {
    print_r($output);
  }
}
