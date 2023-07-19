export const replaceRight = (str: string, searchString: string, replacement: string): string => {
    var lastIndex = str.lastIndexOf(searchString);
    if (lastIndex === -1) {
        return str;
    }
    return str.slice(0, lastIndex) + str.slice(lastIndex).replace(searchString, replacement);
}