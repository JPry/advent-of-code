<?php

dataset('day2', function() {
	yield ['7 6 4 2 1', true];
	yield ['1 3 2 4 5', true];
	yield ['8 6 4 4 1', true];
	yield ['1 3 6 7 9', true];
	yield ['51 52 55 58 60 61 62 61', true];
	yield ['86 85 86 89 92 94 97', true];
	yield ['90 89 86 84 83 79', true];
	yield ['97 96 93 91 85', true];
	yield ['29 26 24 25 21', true];
	yield ['36 37 40 43 47', true];
	yield ['43 44 47 48 49 54', true];
	yield ['35 33 31 29 27 25 22 18', true];
	yield ['77 76 73 70 64', true];
	yield ['68 65 69 72 74 77 80 83', true];
	yield ['37 40 42 43 44 47 51', true];
	yield ['70 73 76 79 86', true];
	yield ['2 4 6 9 11 14 18', true];
});

dataset('day 10', function() {
	yield [<<<TEST1
...0...
...1...
...2...
6543456
7.....7
8.....8
9.....9
TEST1, 2];

	yield [<<<TEST2
..90..9
...1.98
...2..7
6543456
765.987
876....
987....
TEST2, 4];

	yield [<<<TEST3
89010123
78121874
87430965
96549874
45678903
32019012
01329801
10456732
TEST3, 36];
});

dataset('day 10 part 2', function() {
	yield [
		<<<TEST1
89010123
78121874
87430965
96549874
45678903
32019012
01329801
10456732
TEST1,
		81
	];
});

dataset('day 11', function() {
	yield['0', ['1']];
	yield['1', ['2024']];
	yield['20', ['2', '0']];
	yield['24', ['2', '4']];
	yield['2024', ['20', '24']];
});
