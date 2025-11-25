import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/login-custom.css',
                'resources/css/css-admin/admin-layout.css',
                'resources/css/css-admin/admin-dashboard.css',
                'resources/css/css-admin/admin-copy.css',
                'resources/css/css-admin/admin-faturamento.css',
                'resources/css/css-admin/admin-time.css',
                'resources/css/css-admin/admin-perfil.css',
                'resources/css/css-admin/admin-multiselect.css',
                'resources/css/css-admin/admin-editors.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        // 👇 Este plugin copia automaticamente o manifest da subpasta .vite/
        {
            name: 'fix-manifest-location',
            closeBundle() {
                const source = path.resolve('public/build/.vite/manifest.json');
                const dest = path.resolve('public/build/manifest.json');
                if (fs.existsSync(source)) {
                    fs.copyFileSync(source, dest);
                    console.log('✅ Manifest.json movido para public/build/manifest.json');
                }
            },
        },
    ],
    build: {
        outDir: 'public/build',
        assetsDir: 'assets',
        manifest: true,
        emptyOutDir: true,
    },
});
