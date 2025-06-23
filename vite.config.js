import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';
import fs from 'fs';

function getAllFiles(dir, exts, fileList = []) {
    const entries = fs.readdirSync(dir, { withFileTypes: true });

    for (const entry of entries) {
        const fullPath = path.join(dir, entry.name);
        if (entry.isDirectory()) {
            getAllFiles(fullPath, exts, fileList);
        } else if (exts.includes(path.extname(entry.name))) {
            // Viteに渡すパスは / 区切りに統一
            fileList.push(fullPath.replace(/\\/g, '/'));
        }
    }

    return fileList;
}

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');

    const jsFiles = getAllFiles('resources/js', ['.js']);
    const cssFiles = getAllFiles('resources/css', ['.css']);

    return {
        base: `${env.VITE_BASE_PATH || '/' }build/`,
        plugins: [
            laravel({
                input: [...jsFiles, ...cssFiles],
                refresh: true,
            }),
            tailwindcss(),
        ],
    };
});
