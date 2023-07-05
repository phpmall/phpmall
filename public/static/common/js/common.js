function firstLetterToLowerCase(str) {
    return str.charAt(0).toLowerCase() + str.slice(1)
}

function convertToLowerCase(str, separator = '-') {
    return str.replace(/[A-Z]/g, (match) => separator + match.toLowerCase())
}
