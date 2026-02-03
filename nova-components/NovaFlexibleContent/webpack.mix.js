const mix = require('laravel-mix')
const path = require('path')

mix
  .setPublicPath('dist')
  .js('resources/js/field.js', 'js')
  .vue({ version: 3 })
  .sass('resources/sass/field.scss', 'css')
  .alias({
    'nova-mixins': path.join(__dirname,'./vendor/laravel/nova/resources/js/mixins')
  })
  .webpackConfig({
    externals: {
      vue: 'Vue',
      'laravel-nova': 'LaravelNova',
    },
    output: {
      uniqueName: 'salt/nova-flexible-content',
    },
  });
