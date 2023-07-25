import type {RouteRecordRaw} from "vue-router";
import {replaceRight} from "@/utils/str";

const allRoutes: Array<RouteRecordRaw> = []
const pages = import.meta.glob('@/pages/**/**.vue')
Object.keys(pages).forEach(item => {
    const matches: RegExpMatchArray = item.match(/\/pages\/(.+)\.vue/) as RegExpMatchArray
    const pathInfo: string = matches?.slice(1)[0] as string;
    if (pathInfo.search('components') === -1 && pathInfo.search('layout') === -1) {
        allRoutes.push({
            path: `/${pathInfo}`,
            name: pathInfo.replace(/\//g, '.'),
            component: pages[item]
        })
    }
})

const getRoutes = (prefix: string) => {
    const routes: Array<RouteRecordRaw> = []
    allRoutes.forEach(item => {
        if (item.path.substring(0, prefix.length) === prefix) {
            item.path = replaceRight(item.path, '/index', '')
            item.path = item.path.substring(prefix.length)
            routes.push(item)
        }
    })
    return routes
}

export {
    allRoutes,
    getRoutes
}
