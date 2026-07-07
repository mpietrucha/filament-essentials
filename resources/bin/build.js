import esbuild from 'esbuild'

async function compile(options) {
    const context = await esbuild.context(options)

    await context.rebuild()
    await context.dispose()
}

const defaultOptions = {
    define: {
        'process.env.NODE_ENV': `'production'`,
    },
    bundle: true,
    mainFields: ['module', 'main'],
    platform: 'neutral',
    sourcemap: 'inline',
    sourcesContent: false,
    treeShaking: true,
    target: ['es2020'],
    minify: true,
}

compile({
    ...defaultOptions,
    outfile: './resources/dist/index.js',
    entryPoints: ['./resources/js/index.js'],
})
