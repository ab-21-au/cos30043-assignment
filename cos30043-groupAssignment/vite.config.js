import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'

// https://vite.dev/config/
export default defineConfig({
  base: '/cos30043/s104551544/Assign2/', // Please change to your user when hosting on mercury
  plugins: [vue(), vuetify()],
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost/cos30043-assignment/cos30043-groupAssignment',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, '/api')
      }
    }
  }
})
