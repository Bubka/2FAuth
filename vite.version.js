import * as fs from 'fs';
import { Engine } from 'php-parser';

const parser = new Engine({
    parser: {
        extractDoc: false,
    },
    ast: {
        withPositions: true,
    },
})

const phpFile = fs.readFileSync("./config/2fauth.php")
const phpContent = parser.parseCode(phpFile)
const version = phpContent.children[4].expr.items[0].value.value

export default version