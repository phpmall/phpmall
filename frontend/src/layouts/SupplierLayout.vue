<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import {
  Compass,
  DataAnalysis,
  PieChart,
  Setting,
  Connection,
  Calendar,
  User,
  CreditCard,
  ScaleToOriginal,
  Coin
} from '@element-plus/icons-vue'
</script>

<template>
  <el-container>
    <el-header class="">
      <RouterLink :to="{ name: 'supplier' }">慧开印务管理系统</RouterLink>
    </el-header>
    <el-container style="height: calc(100vh - 60px)">
      <el-aside width="150px">
        <el-menu class="h-full">
          <el-menu-item index="1">
            <el-icon><DataAnalysis /></el-icon>
            <span><RouterLink :to="{ name: 'supplier.dashboard' }">工作台</RouterLink></span>
          </el-menu-item>

          <el-menu-item index="2">
            <el-icon><User /></el-icon>
            <span><RouterLink :to="{ name: 'supplier.customer' }">客户管理</RouterLink></span>
          </el-menu-item>

          <el-menu-item index="3">
            <el-icon><ScaleToOriginal /></el-icon>
            <span><RouterLink :to="{ name: 'supplier.order' }">订单管理</RouterLink></span>
          </el-menu-item>

          <el-menu-item index="4">
            <el-icon><Calendar /></el-icon>
            <span><RouterLink :to="{ name: 'supplier.schedule' }">生产管理</RouterLink></span>
          </el-menu-item>

          <el-menu-item index="5">
            <el-icon><Coin /></el-icon>
            <span><RouterLink :to="{ name: 'supplier.warehouse' }">仓库管理</RouterLink></span>
          </el-menu-item>

          <el-menu-item index="6">
            <el-icon><CreditCard /></el-icon>
            <span><RouterLink :to="{ name: 'supplier.finance' }">财务管理</RouterLink></span>
          </el-menu-item>

          <el-menu-item index="7">
            <el-icon><PieChart /></el-icon>
            <span><RouterLink :to="{ name: 'supplier.report' }">数据报表</RouterLink></span>
          </el-menu-item>

          <el-menu-item index="8">
            <el-icon><Connection /></el-icon>
            <span><RouterLink :to="{ name: 'supplier.printer' }">分包管理</RouterLink></span>
          </el-menu-item>

          <el-menu-item index="9">
            <el-icon><Compass /></el-icon>
            <span><RouterLink :to="{ name: 'supplier.printer' }">应用管理</RouterLink></span>
          </el-menu-item>

          <el-menu-item index="10">
            <el-icon><Setting /></el-icon>
            <span><RouterLink :to="{ name: 'supplier.setting' }">系统设置</RouterLink></span>
          </el-menu-item>
        </el-menu>
      </el-aside>
      <el-main>
        <RouterView />
      </el-main>
    </el-container>
  </el-container>
</template>
