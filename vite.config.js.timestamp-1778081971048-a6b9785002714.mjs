// vite.config.js
import { defineConfig } from "file:///C:/laragon/www/wagner/juncao-laravel-vue/node_modules/vite/dist/node/index.js";
import laravel from "file:///C:/laragon/www/wagner/juncao-laravel-vue/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///C:/laragon/www/wagner/juncao-laravel-vue/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import { viteStaticCopy } from "file:///C:/laragon/www/wagner/juncao-laravel-vue/node_modules/vite-plugin-static-copy/dist/index.js";
var vite_config_default = defineConfig({
  plugins: [
    vue(),
    laravel({
      input: ["resources/css/app.css", "resources/js/app.js"],
      refresh: true
    }),
    viteStaticCopy({
      targets: [
        { src: "resources/assets/admin/css", dest: "admin" },
        { src: "resources/assets/admin/data", dest: "admin" },
        { src: "resources/assets/admin/fonts", dest: "admin" },
        { src: "resources/assets/admin/images", dest: "admin" },
        { src: "resources/assets/admin/js", dest: "admin" },
        { src: "resources/js/assets/css/main.css", dest: "client" }
      ]
    })
  ],
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js"
    }
  },
  server: {
    host: "0.0.0.0",
    port: 5173,
    strictPort: true,
    hmr: {
      // host: 'localhost',
      host: "192.168.100.10"
    }
  },
  build: {
    minify: "esbuild",
    sourcemap: false,
    target: "es2015",
    chunkSizeWarningLimit: 500
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxsYXJhZ29uXFxcXHd3d1xcXFx3YWduZXJcXFxcanVuY2FvLWxhcmF2ZWwtdnVlXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCJDOlxcXFxsYXJhZ29uXFxcXHd3d1xcXFx3YWduZXJcXFxcanVuY2FvLWxhcmF2ZWwtdnVlXFxcXHZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9DOi9sYXJhZ29uL3d3dy93YWduZXIvanVuY2FvLWxhcmF2ZWwtdnVlL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcbmltcG9ydCB7IHZpdGVTdGF0aWNDb3B5IH0gZnJvbSAndml0ZS1wbHVnaW4tc3RhdGljLWNvcHknO1xuXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xuICBwbHVnaW5zOiBbXG4gICAgdnVlKCksXG4gICAgbGFyYXZlbCh7XG4gICAgICBpbnB1dDogWydyZXNvdXJjZXMvY3NzL2FwcC5jc3MnLCAncmVzb3VyY2VzL2pzL2FwcC5qcyddLFxuICAgICAgcmVmcmVzaDogdHJ1ZSxcbiAgICB9KSxcbiAgICB2aXRlU3RhdGljQ29weSh7XG4gICAgICB0YXJnZXRzOiBbXG4gICAgICAgIHsgc3JjOiAncmVzb3VyY2VzL2Fzc2V0cy9hZG1pbi9jc3MnLCBkZXN0OiAnYWRtaW4nIH0sXG4gICAgICAgIHsgc3JjOiAncmVzb3VyY2VzL2Fzc2V0cy9hZG1pbi9kYXRhJywgZGVzdDogJ2FkbWluJyB9LFxuICAgICAgICB7IHNyYzogJ3Jlc291cmNlcy9hc3NldHMvYWRtaW4vZm9udHMnLCBkZXN0OiAnYWRtaW4nIH0sXG4gICAgICAgIHsgc3JjOiAncmVzb3VyY2VzL2Fzc2V0cy9hZG1pbi9pbWFnZXMnLCBkZXN0OiAnYWRtaW4nIH0sXG4gICAgICAgIHsgc3JjOiAncmVzb3VyY2VzL2Fzc2V0cy9hZG1pbi9qcycsIGRlc3Q6ICdhZG1pbicgfSxcbiAgICAgICAgeyBzcmM6ICdyZXNvdXJjZXMvanMvYXNzZXRzL2Nzcy9tYWluLmNzcycsIGRlc3Q6ICdjbGllbnQnIH0sXG4gICAgICBdLFxuICAgIH0pLFxuICBdLFxuICByZXNvbHZlOiB7XG4gICAgYWxpYXM6IHtcbiAgICAgIHZ1ZTogJ3Z1ZS9kaXN0L3Z1ZS5lc20tYnVuZGxlci5qcycsXG4gICAgfSxcbiAgfSxcbiAgc2VydmVyOiB7XG4gICAgaG9zdDogJzAuMC4wLjAnLFxuICAgIHBvcnQ6IDUxNzMsXG4gICAgc3RyaWN0UG9ydDogdHJ1ZSxcbiAgICBobXI6IHtcbiAgICAgIC8vIGhvc3Q6ICdsb2NhbGhvc3QnLFxuICAgICAgaG9zdDogJzE5Mi4xNjguMTAwLjEwJyxcbiAgICB9LFxuICB9LFxuICBidWlsZDoge1xuICAgIG1pbmlmeTogJ2VzYnVpbGQnLFxuICAgIHNvdXJjZW1hcDogZmFsc2UsXG4gICAgdGFyZ2V0OiAnZXMyMDE1JyxcbiAgICBjaHVua1NpemVXYXJuaW5nTGltaXQ6IDUwMCxcbiAgfSxcbn0pO1xuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUFvVCxTQUFTLG9CQUFvQjtBQUNqVixPQUFPLGFBQWE7QUFDcEIsT0FBTyxTQUFTO0FBQ2hCLFNBQVMsc0JBQXNCO0FBRS9CLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQzFCLFNBQVM7QUFBQSxJQUNQLElBQUk7QUFBQSxJQUNKLFFBQVE7QUFBQSxNQUNOLE9BQU8sQ0FBQyx5QkFBeUIscUJBQXFCO0FBQUEsTUFDdEQsU0FBUztBQUFBLElBQ1gsQ0FBQztBQUFBLElBQ0QsZUFBZTtBQUFBLE1BQ2IsU0FBUztBQUFBLFFBQ1AsRUFBRSxLQUFLLDhCQUE4QixNQUFNLFFBQVE7QUFBQSxRQUNuRCxFQUFFLEtBQUssK0JBQStCLE1BQU0sUUFBUTtBQUFBLFFBQ3BELEVBQUUsS0FBSyxnQ0FBZ0MsTUFBTSxRQUFRO0FBQUEsUUFDckQsRUFBRSxLQUFLLGlDQUFpQyxNQUFNLFFBQVE7QUFBQSxRQUN0RCxFQUFFLEtBQUssNkJBQTZCLE1BQU0sUUFBUTtBQUFBLFFBQ2xELEVBQUUsS0FBSyxvQ0FBb0MsTUFBTSxTQUFTO0FBQUEsTUFDNUQ7QUFBQSxJQUNGLENBQUM7QUFBQSxFQUNIO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDUCxPQUFPO0FBQUEsTUFDTCxLQUFLO0FBQUEsSUFDUDtBQUFBLEVBQ0Y7QUFBQSxFQUNBLFFBQVE7QUFBQSxJQUNOLE1BQU07QUFBQSxJQUNOLE1BQU07QUFBQSxJQUNOLFlBQVk7QUFBQSxJQUNaLEtBQUs7QUFBQTtBQUFBLE1BRUgsTUFBTTtBQUFBLElBQ1I7QUFBQSxFQUNGO0FBQUEsRUFDQSxPQUFPO0FBQUEsSUFDTCxRQUFRO0FBQUEsSUFDUixXQUFXO0FBQUEsSUFDWCxRQUFRO0FBQUEsSUFDUix1QkFBdUI7QUFBQSxFQUN6QjtBQUNGLENBQUM7IiwKICAibmFtZXMiOiBbXQp9Cg==
