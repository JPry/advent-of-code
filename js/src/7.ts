import Command from "@oclif/command";

interface Bag {
    color: string
    bags?: Bags
}

type Bags = Record<string, Bag>

const targetColor = 'shiny gold'

function parseFile(file: string) {
    return file.split('\n')
        .filter((value: string) => value.length > 0)
}

function parseLine(line: string): Bag {
    const [color, rawContains] = line.split(' bags contain ')
    const contains = rawContains.split(', ').map((value) => {
        return value.replace(new RegExp('^\\d+ (.*) bags?\\.?$'), '$1')
    })

    let bags: Bags = {}
    if ('no other bags.' === contains[0]) {
        return { color: color }
    }

    contains.forEach((item: string) => {
        bags[item] = <Bag> {color: item}
    })

    return {
        color: color,
        bags: bags,
    }
}

/**
 * --- Day 7: Handy Haversacks ---

 You land at the regional airport in time for your next flight. In fact, it looks like you'll even have time to grab some food: all flights are currently delayed due to issues in luggage processing.

 Due to recent aviation regulations, many rules (your puzzle input) are being enforced about bags and their contents; bags must be color-coded and must contain specific quantities of other color-coded bags. Apparently, nobody responsible for these regulations considered how long they would take to enforce!

 For example, consider the following rules:

 light red bags contain 1 bright white bag, 2 muted yellow bags.
 dark orange bags contain 3 bright white bags, 4 muted yellow bags.
 bright white bags contain 1 shiny gold bag.
 muted yellow bags contain 2 shiny gold bags, 9 faded blue bags.
 shiny gold bags contain 1 dark olive bag, 2 vibrant plum bags.
 dark olive bags contain 3 faded blue bags, 4 dotted black bags.
 vibrant plum bags contain 5 faded blue bags, 6 dotted black bags.
 faded blue bags contain no other bags.
 dotted black bags contain no other bags.
 These rules specify the required contents for 9 bag types. In this example, every faded blue bag is empty, every vibrant plum bag contains 11 bags (5 faded blue and 6 dotted black), and so on.

 You have a shiny gold bag. If you wanted to carry it in at least one other bag, how many different bag colors would be valid for the outermost bag? (In other words: how many colors can, eventually, contain at least one shiny gold bag?)

 In the above rules, the following options would be available to you:

 A bright white bag, which can hold your shiny gold bag directly.
 A muted yellow bag, which can hold your shiny gold bag directly, plus some other bags.
 A dark orange bag, which can hold bright white and muted yellow bags, either of which could then hold your shiny gold bag.
 A light red bag, which can hold bright white and muted yellow bags, either of which could then hold your shiny gold bag.
 So, in this example, the number of bag colors that can eventually contain at least one shiny gold bag is 4.

 How many bag colors can eventually contain at least one shiny gold bag? (The list of rules is quite long; make sure you get all of it.)
 *
 * @param file
 * @param cmd
 */
function runPart1(file: string, cmd: Command) {
    const rules: string[] = parseFile(file)
    const allBags: Bags = {}
    let opened: string[] = []
    let canHoldGold: Set<string> = new Set()
    rules.forEach((rule: string) => {
        const bag = parseLine(rule)
        allBags[bag.color] = bag
    })

    let bagStack: string[] = []

    const openBag = (bag: Bag) => {
        if (opened.includes(bag.color)) {
            return
        }

        if (targetColor === bag.color) {
            return
        }

        bagStack.push(bag.color)
        opened.push(bag.color)

        for (let bagsKey in bag.bags) {
            if (!bag.bags.hasOwnProperty(bagsKey)) {
                continue
            }

            if (bagsKey === targetColor) {
                bagStack.forEach((value: string) => {
                    canHoldGold.add(value)
                })
                bagStack = []
                return
            } else if (canHoldGold.has(bagsKey)) {
                canHoldGold.add(bag.color)
                return
            } else {
                openBag(allBags[bagsKey])
            }
        }
    }

    for (let allBagsKey in allBags) {
        if (!allBags.hasOwnProperty(allBagsKey)) {
            continue
        }

        openBag(allBags[allBagsKey])
    }

    cmd.log(`Count that can hold gold: ${canHoldGold.size}`)
}

/**
 *
 *
 * @param file
 * @param cmd
 */
function runPart2(file: string, cmd: Command) {
    const groups: string[] = parseFile(file)

}


exports.runPart1 = runPart1
exports.runPart2 = runPart2
