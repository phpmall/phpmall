function firstLetterToLowerCase(str: string): string {
    return str.charAt(0).toLowerCase() + str.slice(1)
}

function convertToLowerCase(str: string, separator: string = '-'): string {
    return str.replace(/[A-Z]/g, (match: string) => separator + match.toLowerCase())
}
