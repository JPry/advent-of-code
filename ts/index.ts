import {Command} from 'commander';
import fs from 'fs';

function getInputForDay(day: number, year: number, test: boolean = false, fileName?: string): string {
    const filePath = `${__dirname}/../input/${year}/${day}/${test ? 'test' : 'input'}.txt`;
    return fs.readFileSync(filePath, 'utf-8');
}

type DayInput = string;
type DaySolver = (input: DayInput) => void;

const daySolvers: { [key: string]: DaySolver } = {
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
