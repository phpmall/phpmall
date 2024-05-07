import type { RouteRecordRaw } from 'vue-router'
import NotFound from '@/components/NotFound/index.vue'

export const portalRoutes: RouteRecordRaw = {
  path: '/',
  component: () => import('./Layout.vue'),
      children: [
        {
          path: '',
          name: 'admin.dashboard',
          component: () => import('@/views/Dashboard.vue'),
          meta: { title: '工作台' }
        },
        {
          path: 'customer',
          name: 'admin.customer',
          component: () => import('@/views/customer/Customer.vue'),
          meta: { title: '客户管理' }
        },
        {
          path: 'order',
          name: 'admin.order',
          component: () => import('@/views/order/Order.vue'),
          meta: { title: '订单管理' }
        },
        {
          path: 'schedule',
          name: 'admin.schedule',
          component: () => import('@/views/schedule/Schedule.vue'),
          meta: { title: '生产管理' }
        },
        {
          path: 'warehouse',
          name: 'admin.warehouse',
          component: () => import('@/views/warehouse/Warehouse.vue'),
          meta: { title: '仓库管理' }
        },
        {
          path: 'finance',
          name: 'admin.finance',
          component: () => import('@/views/finance/Finance.vue'),
          meta: { title: '财务管理' }
        },
        {
          path: 'report',
          name: 'admin.report',
          component: () => import('@/views/report/Report.vue'),
          meta: { title: '数据报表' }
        },
        {
          path: 'printer',
          name: 'admin.printer',
          component: () => import('@/views/printer/Printer.vue'),
          meta: { title: '分包商管理' }
        },
        {
          path: 'setting',
          name: 'admin.setting',
          component: () => import('@/views/setting/Setting.vue'),
          meta: { title: '系统设置' }
        }
      ]
}
