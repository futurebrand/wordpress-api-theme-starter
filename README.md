# PROJECT_NAME

## What do you need?

- WordPress Core

## First steps

- Download the [WordPress Core](https://br.wordpress.org/txt-download/) zip and create a folder for your project with the zip content;

- Clone the repository to `wp-content/themes`;

- Copy the plugins from repository folder `_extras/plugins` and paste to `wp-content/plugins`;

- Create a `wp-config.php` file. On your root wordpress folder, use `wp-config-sample.php` as reference and create `wp-config.php`. After that, change to your localhost configs and change variable `$table_prefix` to `{project_slug_name}_`;

- Create an empty localhost database and import from `_extras/database`; Before that, if your localhost is different than `http://{LOCALHOST_PROJECT_URL}` do this trick on your `wp-config.php`, before this `require_once(ABSPATH . 'wp-settings.php');`:

```
define('WP_HOME','http://{LOCALHOST_PROJECT_URL}');
define('WP_SITEURL','http://{LOCALHOST_PROJECT_URL}');
```

## Plugins

- [Advanced Custom Fields PRO](https://www.advancedcustomfields.com)
- [Admin Columns](https://br.wordpress.org/plugins/codepress-admin-columns/)