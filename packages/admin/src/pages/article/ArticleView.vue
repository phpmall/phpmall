<template>
  <div class="article-view">
    <div class="page-title">文章管理</div>

    <!-- 搜索栏 -->
    <el-card class="search-form" shadow="never">
      <el-form :model="searchForm" inline>
        <el-form-item>
          <el-input
            v-model="searchForm.keyword"
            placeholder="请输入文章标题或关键词"
            clearable
            style="width: 240px"
          />
        </el-form-item>
        <el-form-item>
          <el-select
            v-model="searchForm.status"
            placeholder="全部状态"
            clearable
            style="width: 120px"
          >
            <el-option label="已发布" :value="1" />
            <el-option label="草稿" :value="0" />
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-select
            v-model="searchForm.category"
            placeholder="全部分类"
            clearable
            filterable
            style="width: 150px"
          >
            <el-option
              v-for="category in categories"
              :key="category.id"
              :label="category.name"
              :value="category.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" :icon="Search" @click="handleSearch"> 搜索 </el-button>
          <el-button @click="handleReset">重置</el-button>
        </el-form-item>
      </el-form>
    </el-card>

    <!-- 文章列表 -->
    <el-card class="table-container" shadow="never">
      <div class="table-header">
        <h3>文章列表</h3>
        <el-button type="primary" :icon="Plus" @click="handleCreate"> 新增文章 </el-button>
      </div>

      <el-table :data="articles" style="width: 100%" v-loading="loading">
        <el-table-column prop="id" label="ID" width="60" />
        <el-table-column prop="title" label="标题" />
        <el-table-column prop="category_name" label="分类" width="120">
          <template #default="{ row }">
            {{ row.category_name || '-' }}
          </template>
        </el-table-column>
        <el-table-column prop="author" label="作者" width="100">
          <template #default="{ row }">
            {{ row.author || '-' }}
          </template>
        </el-table-column>
        <el-table-column prop="status" label="状态" width="100">
          <template #default="{ row }">
            <el-tag v-if="row.status === 1" type="success">已发布</el-tag>
            <el-tag v-else type="warning">草稿</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="views" label="浏览量" width="120">
          <template #default="{ row }">
            {{ row.views || 0 }}
          </template>
        </el-table-column>
        <el-table-column prop="created_at" label="创建时间" width="160">
          <template #default="{ row }">
            {{ formatDate(row.created_at) }}
          </template>
        </el-table-column>
        <el-table-column label="操作" width="240" fixed="right">
          <template #default="{ row }">
            <el-button type="primary" size="small" :icon="Edit" @click="handleEdit(row.id)">
              编辑
            </el-button>
            <el-button size="small" :icon="View" @click="handleView(row.id)"> 查看 </el-button>
            <el-button type="danger" size="small" :icon="Delete" @click="handleDelete(row.id)">
              删除
            </el-button>
          </template>
        </el-table-column>
        <template #empty>
          <el-empty description="暂无数据" />
        </template>
      </el-table>

      <!-- 分页 -->
      <el-pagination
        v-if="pagination.total_pages > 1"
        v-model:current-page="pagination.current_page"
        v-model:page-size="pagination.per_page"
        :total="pagination.total_records"
        :page-sizes="[10, 20, 50, 100]"
        layout="total, sizes, prev, pager, next, jumper"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        style="margin-top: 20px; justify-content: flex-end"
      />
    </el-card>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { ElMessage, ElMessageBox } from 'element-plus'
import { Search, Plus, Edit, View, Delete } from '@element-plus/icons-vue'

const router = useRouter()

// 搜索表单
const searchForm = reactive({
  keyword: '',
  status: '',
  category: '',
})

// 分类列表
const categories = ref<any[]>([])

// 文章列表
const articles = ref<any[]>([])

// 加载状态
const loading = ref(false)

// 分页信息
const pagination = reactive({
  current_page: 1,
  per_page: 10,
  total_records: 0,
  total_pages: 0,
})

// 格式化日期
const formatDate = (date: string) => {
  if (!date) return '-'
  return new Date(date).toLocaleString('zh-CN', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  })
}

// 搜索
const handleSearch = () => {
  pagination.current_page = 1
  fetchArticles()
}

// 重置
const handleReset = () => {
  searchForm.keyword = ''
  searchForm.status = ''
  searchForm.category = ''
  pagination.current_page = 1
  fetchArticles()
}

// 新增文章
const handleCreate = () => {
  router.push('/admin/article/create')
}

// 编辑文章
const handleEdit = (id: number) => {
  router.push(`/admin/article/edit/${id}`)
}

// 查看文章
const handleView = (id: number) => {
  router.push(`/admin/article/view/${id}`)
}

// 删除文章
const handleDelete = (id: number) => {
  ElMessageBox.confirm('确定要删除这篇文章吗？', '提示', {
    confirmButtonText: '确定',
    cancelButtonText: '取消',
    type: 'warning',
  })
    .then(() => {
      // 调用删除接口
      // deleteArticleApi(id).then(() => {
      //   ElMessage.success('删除成功')
      //   fetchArticles()
      // })
      window.location.href = `/admin/article/delete/${id}`
    })
    .catch(() => {
      // 取消删除
    })
}

// 分页大小改变
const handleSizeChange = (size: number) => {
  pagination.per_page = size
  fetchArticles()
}

// 当前页改变
const handleCurrentChange = (page: number) => {
  pagination.current_page = page
  fetchArticles()
}

// 获取文章列表
const fetchArticles = async () => {
  loading.value = true
  try {
    // 这里应该调用实际的API接口
    // const response = await getArticlesApi({
    //   ...searchForm,
    //   page: pagination.current_page,
    //   per_page: pagination.per_page
    // })
    // articles.value = response.data
    // pagination.total_records = response.total
    // pagination.total_pages = response.total_pages
  } catch (error) {
    ElMessage.error('获取文章列表失败')
  } finally {
    loading.value = false
  }
}

// 获取分类列表
const fetchCategories = async () => {
  try {
    // 这里应该调用实际的API接口
    // const response = await getCategoriesApi()
    // categories.value = response.data
  } catch (error) {
    ElMessage.error('获取分类列表失败')
  }
}

onMounted(() => {
  fetchCategories()
  fetchArticles()
})
</script>

<style scoped>
.article-view {
  padding: 20px;
}

.page-title {
  margin-bottom: 20px;
  font-size: 20px;
  font-weight: bold;
}

.search-form {
  margin-bottom: 20px;
}

.table-container {
}

.table-header {
  margin-bottom: 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.table-header h3 {
  margin: 0;
}
</style>
