export const toSnakeCase = (input: string): string => {
    return input
        .replace(/[^a-zA-Z0-9]+/g, ' ')
        .trim()
        .replace(/\s+/g, '_')
        .toLowerCase()
}