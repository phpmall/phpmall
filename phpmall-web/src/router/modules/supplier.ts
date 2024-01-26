import type { RouteRecordRaw } from 'vue-router'

export const supplierRoutes: RouteRecordRaw[] = [
  {
    path: '',
    name: 'supplier',
    redirect: {
      name: 'supplier.dashboard'
    }
  },
  {
    path: 'dashboard',
    name: 'supplier.dashboard',
    component: () => import('@/modules/supplier/Dashboard.vue'),
    meta: { title: '工作台' }
  },
  {
    path: 'customer',
    name: 'supplier.customer',
    component: () => import('@/modules/supplier/customer/Customer.vue'),
    meta: { title: '客户管理' }
  },
  {
    path: 'order',
    name: 'supplier.order',
    component: () => import('@/modules/supplier/order/Order.vue'),
    meta: { title: '订单管理' }
  },
  {
    path: 'schedule',
    name: 'supplier.schedule',
    component: () => import('@/modules/supplier/schedule/Schedule.vue'),
    meta: { title: '生产管理' }
  },
  {
    path: 'warehouse',
    name: 'supplier.warehouse',
    component: () => import('@/modules/supplier/warehouse/Warehouse.vue'),
    meta: { title: '仓库管理' }
  },
  {
    path: 'finance',
    name: 'supplier.finance',
    component: () => import('@/modules/supplier/finance/Finance.vue'),
    meta: { title: '财务管理' }
  },
  {
    path: 'report',
    name: 'supplier.report',
    component: () => import('@/modules/supplier/report/Report.vue'),
    meta: { title: '数据报表' }
  },
  {
    path: 'printer',
    name: 'supplier.printer',
    component: () => import('@/modules/supplier/printer/Printer.vue'),
    meta: { title: '分包商管理' }
  },
  {
    path: 'setting',
    name: 'supplier.setting',
    component: () => import('@/modules/supplier/setting/Setting.vue'),
    meta: { title: '系统设置' }
  }
]
