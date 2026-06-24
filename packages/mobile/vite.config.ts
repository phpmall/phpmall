import { defineConfig } from "vite";
import uni from "@dcloudio/vite-plugin-uni";
import { lazyPlugins } from 'vite-plus';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: lazyPlugins(() => [uni()]),
});
