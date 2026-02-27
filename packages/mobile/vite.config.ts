import { defineConfig } from "vite";
import uni from "@dcloudio/vite-plugin-uni";

// https://vitejs.dev/config/
export default defineConfig({
  base: '/mobile/',  // 示例：自定义 base path
  build: {
    outDir: '../../public/mobile'  // 输出目录
  },
  plugins: [uni()],
});
