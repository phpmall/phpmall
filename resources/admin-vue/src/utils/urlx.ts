export const encodeURIComponent2 = (str: string): string => {
    return encodeURIComponent(str).replace(/[!'()*]/g, function (c: string): string {
        return '%' + c.charCodeAt(0).toString(16).toUpperCase();
    });
}

export const decodeURIComponent2 = (url: string, def: string = '/'): string => {
    if (url) {
        try {
            return decodeURIComponent(url);
        } catch (e) {
            return def
        }
    }

    return def
}