import { extractH3Headings } from "./utils/extractH3s"
import { toSnakeCase } from "./utils/toSnakeCase"

const md = require('markdown-it')()
const path  = require('path')
const root = path.resolve(__dirname, '../../')
const pageJsonDir = path.join(root, 'pages')
const pageMdContentDir = path.join(root, 'docs', 'pages')
const fs = require('fs')
const glob = require('glob')
const globPattern = path.join(pageJsonDir, '**/*.json').replace(/\\/g, '/')

type ScratchJSON = {
    data: {
        content: string,
        updatedAt: number
    }
}

const addAnchorToH3s = (content: string): string => {
    const allH3s = extractH3Headings(content)
    const snakeCaseH3s = allH3s.map(h3 => toSnakeCase(h3))
    let updatedContent = content
    snakeCaseH3s.forEach((h3, index) => {
        const anchor = `id="${h3}"`
        const h3Regex = new RegExp(`<h3>(.*?)</h3>`, 'i')
        updatedContent = updatedContent.replace(h3Regex, `<h3 ${anchor}>$1</h3>`)
    })
    return updatedContent
}
// Read all JSON files in the pages directory
const files = glob.sync(globPattern)
files.map(file => {
    const dirloc = path.dirname(file)
    const filename = path.basename(file)
    const relativePath = path.relative(pageJsonDir, dirloc).replace(/\\/g, '/')
    const mdPath = path.join(pageMdContentDir, relativePath, filename.replace('.json', '.md')).replace(/\\/g, '/')
    const mdContent = fs.readFileSync(mdPath, 'utf8')
    let convertedContent = md.render(mdContent)
    convertedContent = addAnchorToH3s(convertedContent)
    const pageJson = JSON.parse(fs.readFileSync(file, 'utf8'))
    if (!pageJson.data || !pageJson.data.content) {
        pageJson.data = {
            content: convertedContent,
            updatedAt: Date.now()
        }
        fs.writeFileSync(file, JSON.stringify(pageJson, null, 2))
        return
    }
    if (pageJson.data.content !== convertedContent) {
        pageJson.data.content = convertedContent
        pageJson.data.updatedAt = Date.now()
        fs.writeFileSync(file, JSON.stringify(pageJson, null, 2))
    }
})

