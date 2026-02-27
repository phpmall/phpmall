<template>
    <el-container class="layout-container">
        <!-- 头部 -->
        <el-header class="layout-header">
            <div class="logo">CMS管理系统</div>
            <div class="header-right">
                <el-dropdown>
                    <span class="el-dropdown-link">
                        <el-avatar src="//t.cn/RCzsdCq" :size="32" />
                        <span style="margin-left: 10px">管理员</span>
                        <el-icon class="el-icon--right"><arrow-down /></el-icon>
                    </span>
                    <template #dropdown>
                        <el-dropdown-menu>
                            <el-dropdown-item>个人信息</el-dropdown-item>
                            <el-dropdown-item>修改密码</el-dropdown-item>
                            <el-dropdown-item divided>退出登录</el-dropdown-item>
                        </el-dropdown-menu>
                    </template>
                </el-dropdown>
            </div>
        </el-header>

        <el-container>
            <!-- 侧边栏 -->
            <el-aside class="layout-aside" width="200px">
                <el-menu
                    :default-openeds="['1']"
                    class="el-menu-vertical"
                    background-color="#2F4056"
                    text-color="#C2C2C2"
                    active-text-color="#fff"
                >
                    <el-sub-menu index="1">
                        <template #title>
                            <span>内容管理</span>
                        </template>
                        <el-menu-item
                            index="1-1"
                            @click="openTab('/admin/article/list', '文章管理')"
                        >
                            文章管理
                        </el-menu-item>
                        <el-menu-item
                            index="1-2"
                            @click="openTab('/admin/category/list', '栏目管理')"
                        >
                            栏目管理
                        </el-menu-item>
                        <el-menu-item index="1-3" @click="openTab('/admin/tag/list', '标签管理')">
                            标签管理
                        </el-menu-item>
                        <el-menu-item
                            index="1-4"
                            @click="openTab('/admin/comment/list', '评论管理')"
                        >
                            评论管理
                        </el-menu-item>
                    </el-sub-menu>

                    <el-sub-menu index="2">
                        <template #title>
                            <span>用户管理</span>
                        </template>
                        <el-menu-item index="2-1" @click="openTab('/admin/user/list', '用户列表')">
                            用户列表
                        </el-menu-item>
                        <el-menu-item index="2-2" @click="openTab('/admin/user/role', '角色管理')">
                            角色管理
                        </el-menu-item>
                        <el-menu-item
                            index="2-3"
                            @click="openTab('/admin/user/permission', '权限管理')"
                        >
                            权限管理
                        </el-menu-item>
                    </el-sub-menu>

                    <el-sub-menu index="3">
                        <template #title>
                            <span>系统设置</span>
                        </template>
                        <el-menu-item
                            index="3-1"
                            @click="openTab('/admin/system/config', '基本设置')"
                        >
                            基本设置
                        </el-menu-item>
                        <el-menu-item index="3-2" @click="openTab('/admin/system/seo', 'SEO设置')">
                            SEO设置
                        </el-menu-item>
                        <el-menu-item
                            index="3-3"
                            @click="openTab('/admin/system/template', '模板管理')"
                        >
                            模板管理
                        </el-menu-item>
                        <el-menu-item
                            index="3-4"
                            @click="openTab('/admin/system/attachment', '附件管理')"
                        >
                            附件管理
                        </el-menu-item>
                    </el-sub-menu>

                    <el-sub-menu index="4">
                        <template #title>
                            <span>扩展功能</span>
                        </template>
                        <el-menu-item
                            index="4-1"
                            @click="openTab('/admin/plugin/list', '插件管理')"
                        >
                            插件管理
                        </el-menu-item>
                        <el-menu-item
                            index="4-2"
                            @click="openTab('/admin/advert/list', '广告管理')"
                        >
                            广告管理
                        </el-menu-item>
                        <el-menu-item index="4-3" @click="openTab('/admin/link/list', '友情链接')">
                            友情链接
                        </el-menu-item>
                        <el-menu-item index="4-4" @click="openTab('/admin/form/list', '表单管理')">
                            表单管理
                        </el-menu-item>
                    </el-sub-menu>

                    <el-sub-menu index="5">
                        <template #title>
                            <span>日志管理</span>
                        </template>
                        <el-menu-item
                            index="5-1"
                            @click="openTab('/admin/log/operation', '操作日志')"
                        >
                            操作日志
                        </el-menu-item>
                        <el-menu-item index="5-2" @click="openTab('/admin/log/login', '登录日志')">
                            登录日志
                        </el-menu-item>
                        <el-menu-item index="5-3" @click="openTab('/admin/log/error', '错误日志')">
                            错误日志
                        </el-menu-item>
                    </el-sub-menu>
                </el-menu>
            </el-aside>

            <!-- 主内容区 -->
            <el-main class="layout-main">
                <el-tabs v-model="activeTab" type="card" closable @tab-remove="removeTab">
                    <el-tab-pane
                        v-for="item in tabs"
                        :key="item.name"
                        :label="item.title"
                        :name="item.name"
                        :closable="item.closable"
                    >
                        <iframe
                            :src="item.url"
                            frameborder="0"
                            style="width: 100%; height: calc(100vh - 150px)"
                        ></iframe>
                    </el-tab-pane>
                </el-tabs>
            </el-main>
        </el-container>

        <!-- 底部 -->
        <el-footer class="layout-footer">
            &copy; {{ new Date().getFullYear() }} PHPCMS管理系统 - Powered by PHPCMS
        </el-footer>
    </el-container>
</template>

<script setup>
    import { ref } from 'vue'
    import { ArrowDown } from '@element-plus/icons-vue'

    const activeTab = ref('home')
    const tabs = ref([
        {
            name: 'home',
            title: '首页',
            url: '/admin/home',
            closable: false,
        },
    ])

    const openTab = (url, title) => {
        const tabName = 'tab-' + Date.now()
        const existTab = tabs.value.find((tab) => tab.url === url)

        if (existTab) {
            activeTab.value = existTab.name
        } else {
            tabs.value.push({
                name: tabName,
                title: title,
                url: url,
                closable: true,
            })
            activeTab.value = tabName
        }
    }

    const removeTab = (targetName) => {
        const index = tabs.value.findIndex((tab) => tab.name === targetName)
        if (index !== -1) {
            tabs.value.splice(index, 1)
            if (activeTab.value === targetName) {
                activeTab.value = tabs.value[Math.max(0, index - 1)].name
            }
        }
    }
</script>

<style scoped>
    .layout-container {
        height: 100vh;
    }

    .layout-header {
        background-color: #23262e;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
    }

    .logo {
        color: #fff;
        font-size: 18px;
        font-weight: bold;
    }

    .header-right {
        display: flex;
        align-items: center;
    }

    .el-dropdown-link {
        display: flex;
        align-items: center;
        cursor: pointer;
        color: #fff;
    }

    .layout-aside {
        background-color: #2f4056;
    }

    .el-menu-vertical {
        border-right: none;
    }

    .el-menu-vertical:not(.el-menu--collapse) {
        width: 200px;
    }

    :deep(.el-sub-menu__title:hover),
    :deep(.el-menu-item:hover) {
        background-color: #1e9fff !important;
        color: #fff !important;
    }

    :deep(.el-menu-item.is-active) {
        background-color: #1e9fff !important;
        color: #fff !important;
    }

    .layout-main {
        padding: 0;
        background-color: #f5f5f5;
    }

    :deep(.el-tabs) {
        height: 100%;
    }

    :deep(.el-tabs__content) {
        height: calc(100% - 40px);
    }

    :deep(.el-tab-pane) {
        height: 100%;
    }

    .layout-footer {
        text-align: center;
        line-height: 60px;
        background-color: #f5f5f5;
        border-top: 1px solid #e6e6e6;
    }
</style>
