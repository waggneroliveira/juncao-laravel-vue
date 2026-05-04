// vite.config.js
import { defineConfig } from "file:///C:/laragon/www/wagner/juncao-laravel-vue/node_modules/vite/dist/node/index.js";
import laravel from "file:///C:/laragon/www/wagner/juncao-laravel-vue/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///C:/laragon/www/wagner/juncao-laravel-vue/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import { viteStaticCopy } from "file:///C:/laragon/www/wagner/juncao-laravel-vue/node_modules/vite-plugin-static-copy/dist/index.js";
var vite_config_default = defineConfig({
  plugins: [
    vue(),
    laravel({
      input: ["resources/js/app.js"],
      refresh: true
    }),
    viteStaticCopy({
      targets: [
        { src: "resources/assets/admin/css", dest: "admin" },
        { src: "resources/assets/admin/data", dest: "admin" },
        { src: "resources/assets/admin/fonts", dest: "admin" },
        { src: "resources/assets/admin/images", dest: "admin" },
        { src: "resources/assets/admin/js", dest: "admin" },
        { src: "resources/assets/client/images", dest: "client" },
        { src: "resources/assets/client/css", dest: "client" }
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
      host: "192.168.100.1"
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
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxsYXJhZ29uXFxcXHd3d1xcXFx3YWduZXJcXFxcanVuY2FvLWxhcmF2ZWwtdnVlXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCJDOlxcXFxsYXJhZ29uXFxcXHd3d1xcXFx3YWduZXJcXFxcanVuY2FvLWxhcmF2ZWwtdnVlXFxcXHZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9DOi9sYXJhZ29uL3d3dy93YWduZXIvanVuY2FvLWxhcmF2ZWwtdnVlL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcbmltcG9ydCB7IHZpdGVTdGF0aWNDb3B5IH0gZnJvbSAndml0ZS1wbHVnaW4tc3RhdGljLWNvcHknO1xuXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xuICBwbHVnaW5zOiBbXG4gICAgdnVlKCksXG4gICAgbGFyYXZlbCh7XG4gICAgICBpbnB1dDogWydyZXNvdXJjZXMvanMvYXBwLmpzJ10sXG4gICAgICByZWZyZXNoOiB0cnVlLFxuICAgIH0pLFxuICAgIHZpdGVTdGF0aWNDb3B5KHtcbiAgICAgIHRhcmdldHM6IFtcbiAgICAgICAgeyBzcmM6ICdyZXNvdXJjZXMvYXNzZXRzL2FkbWluL2NzcycsIGRlc3Q6ICdhZG1pbicgfSxcbiAgICAgICAgeyBzcmM6ICdyZXNvdXJjZXMvYXNzZXRzL2FkbWluL2RhdGEnLCBkZXN0OiAnYWRtaW4nIH0sXG4gICAgICAgIHsgc3JjOiAncmVzb3VyY2VzL2Fzc2V0cy9hZG1pbi9mb250cycsIGRlc3Q6ICdhZG1pbicgfSxcbiAgICAgICAgeyBzcmM6ICdyZXNvdXJjZXMvYXNzZXRzL2FkbWluL2ltYWdlcycsIGRlc3Q6ICdhZG1pbicgfSxcbiAgICAgICAgeyBzcmM6ICdyZXNvdXJjZXMvYXNzZXRzL2FkbWluL2pzJywgZGVzdDogJ2FkbWluJyB9LFxuICAgICAgICB7IHNyYzogJ3Jlc291cmNlcy9hc3NldHMvY2xpZW50L2ltYWdlcycsIGRlc3Q6ICdjbGllbnQnIH0sXG4gICAgICAgIHsgc3JjOiAncmVzb3VyY2VzL2Fzc2V0cy9jbGllbnQvY3NzJywgZGVzdDogJ2NsaWVudCcgfSxcbiAgICAgIF0sXG4gICAgfSksXG4gIF0sXG4gIHJlc29sdmU6IHtcbiAgICBhbGlhczoge1xuICAgICAgdnVlOiAndnVlL2Rpc3QvdnVlLmVzbS1idW5kbGVyLmpzJyxcbiAgICB9LFxuICB9LFxuICBzZXJ2ZXI6IHtcbiAgICBob3N0OiAnMC4wLjAuMCcsXG4gICAgcG9ydDogNTE3MyxcbiAgICBzdHJpY3RQb3J0OiB0cnVlLFxuICAgIGhtcjoge1xuICAgICAgaG9zdDogJzE5Mi4xNjguMTAwLjEnLFxuICAgIH0sXG4gIH0sXG4gIGJ1aWxkOiB7XG4gICAgbWluaWZ5OiAnZXNidWlsZCcsXG4gICAgc291cmNlbWFwOiBmYWxzZSxcbiAgICB0YXJnZXQ6ICdlczIwMTUnLFxuICAgIGNodW5rU2l6ZVdhcm5pbmdMaW1pdDogNTAwLFxuICB9LFxufSk7XG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQW9ULFNBQVMsb0JBQW9CO0FBQ2pWLE9BQU8sYUFBYTtBQUNwQixPQUFPLFNBQVM7QUFDaEIsU0FBUyxzQkFBc0I7QUFFL0IsSUFBTyxzQkFBUSxhQUFhO0FBQUEsRUFDMUIsU0FBUztBQUFBLElBQ1AsSUFBSTtBQUFBLElBQ0osUUFBUTtBQUFBLE1BQ04sT0FBTyxDQUFDLHFCQUFxQjtBQUFBLE1BQzdCLFNBQVM7QUFBQSxJQUNYLENBQUM7QUFBQSxJQUNELGVBQWU7QUFBQSxNQUNiLFNBQVM7QUFBQSxRQUNQLEVBQUUsS0FBSyw4QkFBOEIsTUFBTSxRQUFRO0FBQUEsUUFDbkQsRUFBRSxLQUFLLCtCQUErQixNQUFNLFFBQVE7QUFBQSxRQUNwRCxFQUFFLEtBQUssZ0NBQWdDLE1BQU0sUUFBUTtBQUFBLFFBQ3JELEVBQUUsS0FBSyxpQ0FBaUMsTUFBTSxRQUFRO0FBQUEsUUFDdEQsRUFBRSxLQUFLLDZCQUE2QixNQUFNLFFBQVE7QUFBQSxRQUNsRCxFQUFFLEtBQUssa0NBQWtDLE1BQU0sU0FBUztBQUFBLFFBQ3hELEVBQUUsS0FBSywrQkFBK0IsTUFBTSxTQUFTO0FBQUEsTUFDdkQ7QUFBQSxJQUNGLENBQUM7QUFBQSxFQUNIO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDUCxPQUFPO0FBQUEsTUFDTCxLQUFLO0FBQUEsSUFDUDtBQUFBLEVBQ0Y7QUFBQSxFQUNBLFFBQVE7QUFBQSxJQUNOLE1BQU07QUFBQSxJQUNOLE1BQU07QUFBQSxJQUNOLFlBQVk7QUFBQSxJQUNaLEtBQUs7QUFBQSxNQUNILE1BQU07QUFBQSxJQUNSO0FBQUEsRUFDRjtBQUFBLEVBQ0EsT0FBTztBQUFBLElBQ0wsUUFBUTtBQUFBLElBQ1IsV0FBVztBQUFBLElBQ1gsUUFBUTtBQUFBLElBQ1IsdUJBQXVCO0FBQUEsRUFDekI7QUFDRixDQUFDOyIsCiAgIm5hbWVzIjogW10KfQo=
