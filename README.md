# Google Hash Code 2021 Pizza Problem in PHP

[Problem statement](https://bytefreaks.net/google/hash-code/google-hash-code-2021-practice-problem)

## 2 Solutions (alternatives):
* Solution 1: Quick
* Solution 2: Optimized

### Solution 1 - Quick str8-fwd solution
1. Sort pizzas by number of ingredients (descending).
2. Deliver from the sorted array of pizzas first to as many 4-member teams as possible,
then 3-member and finally 2-member.

### Solution 2 - Optimized
1. Sort pizzas by number of ingredients (descending).
2. Deliver first to as many 4-member teams as possible,
then 3-member and finally 2-member.
3. The next pizza to select from the available pizzas list is the one that
 optimizes the delivery's ingredients.
 * It uses 2 different algorithms for the selection of the best pizza.
 * First algorithm is for problems with many pizzas (>1000 -> files C, D & E).
 * It selects the pizza with the more new ingredients, compared to the delivery's ingredients.
 * Second algorithm is for problems with fewer pizzas (<1000 -> files A & B).
 * It selects the pizza with the less wasted ingredients percentage, compared to the delivery's ingredients.

## Times
1. Solution 1 executes in 26 seconds.
2. Solution 2 executes in 300 minutes.

## Scores

### Solution 1
* a_example.in: 65
* b_little_bit_of_everything.in: 5,628
* c_many_ingredients.in: 686,187,748
* d_many_pizzas.in: 5,855,908
* e_many_teams.in: 8,318,751
* TOTAL: 700,368,100

### Solution 2
* a_example.in: 74
* b_little_bit_of_everything.in: 10,165
* c_many_ingredients.in: 705,021,030
* d_many_pizzas.in: 7,773,443
* e_many_teams.in: 9,243,902
* TOTAL: 722,048,614
