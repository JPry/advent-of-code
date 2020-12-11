import {Command, flags} from '@oclif/command'
import cli from 'cli-ux'

const fs = require('fs')

class Aoc extends Command {
    static description = 'Run Advent of Code puzzle'

    static flags = {
        version: flags.version({char: 'v'}),
        help: flags.help({char: 'h'}),
        test: flags.boolean({char: 't'}),
    }

    static args = [
        {name: 'day', required: true}
    ]

    async delay(ms: number) {
        return new Promise(res => setTimeout(res, ms));
    }

    async run() {
        const {args, flags} = this.parse(Aoc)
        const test = flags.test ?? false
        const day = args.day
        const relativeFile = `data/${day}${test ? '.test' : ''}.txt`
        const file = `${process.cwd()}/${relativeFile}`

        this.debug(`cwd: ${process.cwd()}`)

        if (!fs.existsSync(file)) {
            throw new Error(`missing file: ${relativeFile}`)
        }

        cli.action.start('loading the file');
        const data = fs.readFileSync(file).toString()
        cli.action.stop()

        this.debug(`Running for day ${day}. Test mode? ${test}`)
        this.debug(`File: ${relativeFile}`)

        const dayFunction = require(`./${day}`)

        cli.action.start('Solving part 1')
        dayFunction.runPart1(data, this)
        cli.action.stop()

        cli.action.start('Solving part 2')
        await this.delay(2000)
        dayFunction.runPart2(data, this)
        cli.action.stop()
    }
}

export = Aoc
