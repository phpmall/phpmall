export const firstLetterToLowerCase = (str: string): string => {
    return str.charAt(0).toLowerCase() + str.slice(1)
}

export const convertToLowerCase = (str: string, separator: string = '-'): string => {
    return str.replace(/[A-Z]/g, (match: string) => separator + match.toLowerCase())
}

export const replaceRight = (str: string, searchString: string, replacement: string): string => {
    const lastIndex = str.lastIndexOf(searchString);
    if (lastIndex === -1) {
        return str;
    }
    return str.slice(0, lastIndex) + str.slice(lastIndex).replace(searchString, replacement);
}