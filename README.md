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
3. The next pizza to select for a delivery is the 1st found with half new ingredients
of the ones that it already has, otherwise the next one with max ingredients.

## Times
1. Solution 1 executes in 26 seconds.
2. Solution 2 executes in 19 minutes.

## Scores

### Solution 1
* a_example.in: 65
* b_little_bit_of_everything.in: 5,628
* c_many_ingredients.in: 686,187,748
* d_many_pizzas.in: 5,855,908
* e_many_teams.in: 8,318,751
* TOTAL: 700,368,100

### Solution 2
* a_example.in: 65
* b_little_bit_of_everything.in: 6,084
* c_many_ingredients.in: 687,301,871
* d_many_pizzas.in: 6,319,493
* e_many_teams.in: 8,754,231
* TOTAL: 702,381,744
