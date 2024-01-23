export const fixedEncodeURIComponent = (str: string): string => {
    return encodeURIComponent(str).replace(/[!'()*]/g, function (c: string): string {
        return '%' + c.charCodeAt(0).toString(16).toUpperCase();
    });
}
