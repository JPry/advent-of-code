import Command from "@oclif/command";

/**
 * Reduce by multiplying
 * @param a
 * @param value
 */
function reducer(a: number, value: number) {
    return Number(a) * Number(value)
}

function parseFile(file: string) {
    return file.split('\n')
        .filter((item: string) => item.length > 0)
        .map(Number)
}

/**
 * --- Day 1: Report Repair ---

 After saving Christmas five years in a row, you've decided to take a vacation at a nice resort on a tropical island. Surely, Christmas will go on without you.

 The tropical island has its own currency and is entirely cash-only. The gold coins used there have a little picture of a starfish; the locals just call them stars. None of the currency exchanges seem to have heard of them, but somehow, you'll need to find fifty of these coins by the time you arrive so you can pay the deposit on your room.

 To save your vacation, you need to get all fifty stars by December 25th.

 Collect stars by solving puzzles. Two puzzles will be made available on each day in the Advent calendar; the second puzzle is unlocked when you complete the first. Each puzzle grants one star. Good luck!

 Before you leave, the Elves in accounting just need you to fix your expense report (your puzzle input); apparently, something isn't quite adding up.

 Specifically, they need you to find the two entries that sum to 2020 and then multiply those two numbers together.

 For example, suppose your expense report contained the following:

 1721
 979
 366
 299
 675
 1456
 In this list, the two entries that sum to 2020 are 1721 and 299. Multiplying them together produces 1721 * 299 = 514579, so the correct answer is 514579.

 Of course, your expense report is much larger. Find the two entries that sum to 2020; what do you get if you multiply them together?
 *
 * @param file
 * @param cmd
 */
function runDay1(file: string, cmd: Command) {
    const data = parseFile(file)
    const small = data.filter((value: number) => value < 1000)
    const large = data.filter((value: number) => value >= 1000)
    const target: number = 2020
    let foundPair = false
    let found: number[] = []

    small.forEach((value: number) => {
        if (foundPair) {
            return
        }

        let item: any;
        for (item of large) {
            let sum: number = Number(value) + Number(item)
            if (target === sum) {
                foundPair = true
                found = [value, item]
                break
            }
        }
    })

    cmd.log(`Numbers found: ${found.toString()}`)
    cmd.log(`Product of numbers: ${found.reduce(reducer, 1)}`)
}

/**
 * The Elves in accounting are thankful for your help; one of them even offers you a starfish coin they had left over from a past vacation. They offer you a second one if you can find three numbers in your expense report that meet the same criteria.

 Using the above example again, the three entries that sum to 2020 are 979, 366, and 675. Multiplying them together produces the answer, 241861950.

 In your expense report, what is the product of the three entries that sum to 2020?
 * @param file
 * @param cmd
 */
function runDay2(file: string, cmd: Command) {
    const data = parseFile(file)
    const target: number = 2020
    let foundNumbers = false
    let found: number[] = []

    cmd.log(`Data is ${data.length} elements`)

    // Find smallest and second smallest numbers.
    const smallestReducer = (accumulated: number, current: number) => {
        return (Number(current) < Number(accumulated)) ? current : accumulated;
    }
    const smallest: number = data.reduce(smallestReducer, target)
    const secondSmallestReducer = (accumulated: number, current: number) => {
        return (Number(current) > Number(smallest) && Number(current) < Number(accumulated)) ? current : accumulated
    }
    const secondSmallest: number = data.reduce(secondSmallestReducer, target)

    cmd.log(`Smallest: ${smallest}`)
    cmd.log(`Second Smallest: ${secondSmallest}`)

    // Eliminate anything too big.
    const filtered = data.filter((value: number) => (Number(value) + Number(smallest) + Number(secondSmallest)) <= target)
    cmd.log(`Data reduced by ${Number(data.length) - Number(filtered.length)} elements.`)
    // cmd.log(`Data: \n${data.join('\n')}`)

    filtered.sort()

    let index = {
        first: 0,
        second: 1,
        third: 2
    }

    // Start with the small numbers.
    let lastIndex = filtered.length - 1
    while (!foundNumbers) {
        let num1: number = Number(filtered[index.first])
        let num2: number = Number(filtered[index.second])
        let num3: number = Number(filtered[index.third])
        let sum: number = num1 + num2 + num3

        // cmd.log( `Testing ${num1}, ${num2}, ${num3}. Sum is: ${sum}`)

        if (target === sum) {
            foundNumbers = true;
            found = [num1, num2, num3]
        }

        /*
        - not the end, third number increments
        - get to the end, second number increments. third number goes to second +1
        - second number get to second from end, first number increments,
         */

        if (index.third < lastIndex) {
            index.third++
        } else {
            if (index.first === lastIndex - 2) {
                cmd.log("Your logic is broken :'( ")
                break
            }

            if (index.second < (lastIndex - 1)) {
                index.second++
                index.third = index.second + 1
            } else {
                index.first++
                index.second = index.first + 1
                index.third = index.second + 1
            }
        }
    }

    cmd.log(`Found: ${found.toString()}`)
    cmd.log(`Product of numbers: ${found.reduce(reducer, 1)}`)
}


exports.runDay1 = runDay1
exports.runDay2 = runDay2
