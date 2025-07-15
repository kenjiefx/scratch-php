/**
 * Extracts all <h3> headings from the given HTML string.
 * @param html 
 * @returns 
 */
export const extractH3Headings = (html: string): string[] => {
    const h3Regex = /<h3[^>]*>(.*?)<\/h3>/g
    const matches: string[] = []
    let match: RegExpExecArray | null
    while ((match = h3Regex.exec(html)) !== null) {
      const textContent = match[1].replace(/<[^>]+>/g, '').trim()
      matches.push(textContent)
    }
    return matches
}