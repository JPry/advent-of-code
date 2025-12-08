import {Command} from 'commander';
import fs from 'fs';

function getInputForDay(day: number, year: number, test: boolean = false, fileName?: string): string {
    const filePath = `${__dirname}/../input/${year}/${day}/${test ? 'test' : 'input'}.txt`;
    return fs.readFileSync(filePath, 'utf-8');
}

type DayInput = string;
type DaySolver = (input: DayInput) => void;

const daySolvers: { [key: string]: DaySolver } = {
    '01': (input: DayInput) => {
        let zeroCount1: number = 0;
        let zeroCount2: number = 0;
        let currentPosition: number = 50;
        let newPosition: number;
        const splitInput = input.split("\n").filter((line) => line.trim() !== '');

        splitInput.forEach((line: string) => {
            const matches = line.match(/([R|L])(\d+)/) || [];

            if (matches.length < 3) {
                console.warn(`Invalid instruction: ${line}`);
                return;
            }

            const direction = matches[1];
            const distance = parseInt(matches[2], 10);
            let revolutions = Math.floor(distance / 100);
            const remainder = distance % 100;

            /*
             * Part 2 Logic:
             *
             * We need to track how many times we cross position 0.
             * Each full revolution (100 units) guarantees crossing position 0 once.
             * Therefore, for every full revolution in the distance, we increment zeroCount2 by 1.
             * After accounting for full revolutions, we check the remainder distance to see if it crosses position 0.
             *
             *
             * When moving right (R):
             * - If the current position + distance crosses or lands on 0, increment zeroCount2.
             *
             * When moving left (L):
             * - If the current position - distance crosses or lands on 0, increment zeroCount2.
             * - Starting position of 1, moving left 1 lands on 0 (crosses once).
             * - Starting position of 1, moving left 150 lands on 51, crosses 0 twice (once at 0 and once more after a full revolution).
             */

            switch (direction) {
                case 'R':
                    newPosition = currentPosition + remainder;
                    if (newPosition === 100) {
                        newPosition = 0;
                    } else if (newPosition > 100) {
                        newPosition = newPosition % 100;
                        revolutions++;
                    }
                    break;
                case 'L':
                    newPosition = currentPosition - remainder;
                    if (newPosition < 0) {
                        newPosition += 100;
                        if (currentPosition !== 0) {
                            revolutions++;
                        }
                    }
                    break;
                default:
                    throw new Error(`Unknown direction: ${direction}`);
            }

            zeroCount2 += revolutions;
            currentPosition = newPosition;

            if (currentPosition === 0) {
                zeroCount1++;
                zeroCount2++;
                return;
            }
        });

        console.log(`Part 1: Number of times position 0 was reached: ${zeroCount1}`);
        console.log(`Part 2: Number of times position 0 was crossed: ${zeroCount2}`);
    },
};

// Set up the CLI program.
const program = new Command();
program
    .name('jpry-advent-of-code')
    .description('CLI tool for Advent of Code challenges')
    .version('1.0.0');

// Register command to solve a specific day's challenge.
program
    .command('solve', {isDefault: true})
    .argument(
        '[day]',
        'Day of the challenge to solve',
        (value) => {
            const day = parseInt(value, 10);
            if (day < 1 || day > 25) {
                throw new Error('Day must be between 1 and 25');
            }
            return day.toString().padStart(2, '0');
        },
        new Date().getDate().toString().padStart(2, '0')
    )
    .argument(
        '[year]',
        'Year of the challenge to solve',
        (value) => parseInt(value, 10),
        new Date().getFullYear()
    )
    .option('-t, --test', 'Use test input instead of real input')
    .description('Solve the challenge for the specified day')
    .action((day, year, options) => {
        console.debug(`Solving challenge for year ${year} day ${day}...`);

        if (!daySolvers.hasOwnProperty(day)) {
            throw new Error(`No solver implemented for day ${day}`);
        }

        const input = getInputForDay(day, year, options.test);
        daySolvers[day](input);
    });

program.parse();
