# Tumblr Theme Garden

> [!TIP]
> [Take TumblrThemeGarden for a spin in one-click right now on WordPress Playground!](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/Automattic/tumblr-theme-translator/refs/heads/trunk/blueprint.json)

Tumblr Theme Garden is a WordPress plugin that allows you to use Tumblr Themes on your WordPress site to display your content in the way your edgy teenage self would have loved.

This plugin will form the basis of the Tumblr Blog Network on WordPress.com eventually. This current iteration is designed to be released on WordPress.org/plugins/ for use by anyone.

> [!IMPORTANT] 
> TumblrThemeGarden will not be used as the UI for the Blog Network migration, that will still exist in the Native Tumblr UI. Instead TumblrThemeGarden will be the rendering engine for the Blog Network, and it's current UI features exist purely to make this usable by anyone wanting to run their own instance of TumblrThemeGarden.

## Features

- **Tumblr Theme Garden**: 1-Click installs of any theme from the Tumblr Theme Garden.
- **1:1 Aesthetics**: Every care has been taken to ensure TumblrThemeGarden will render your chosen Tumblr Theme faithfully.
- **Customizer Support**: Enjoy the same customization options for your chosen theme you would get on Tumblr, update colors, layouts, etc.

## Installation

### From ZIP

1. Download the plugin and upload it to your WordPress site's `wp-content/plugins` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Install your first theme by going to 'Tumblr Themes' from the 'Appearance' menu.

### From GitHub

1. Clone the repository in your WordPress site's `wp-content/plugins` directory.
2. Run `npm install` to install the dependencies.
3. Run `npm run build` to build the plugin.
4. Run `composer i --no-dev` to build the PHP autoloaders.
5. Activate the plugin through the 'Plugins' menu in WordPress.
6. Install your first theme by going to 'Tumblr Themes' from the 'Appearance' menu.

## Usage

- Install your first theme by going to 'Tumblr Themes' from the 'Appearance' menu.
- After activating your chosen theme you'll be taken to the site customizer to refine your chosen theme with customization options.
- Publish your customizer changes and visit the front-end of your site to see your new edgelord theme in all its glory.

## Limitations

Tumblr Themes come with a number of limitations, no menu support, no widget support, to name a few. You should expect more of a What-You-See-Is-What-You-Get compared to the current abilities WordPress native themes have.

TumblrThemeGarden is also working to backfill a number of missing features in WordPress that Tumblr currently has, but WordPress does not support out of the box. Whilst these features are in development, you may notice inconsistencies or broken behaviour on your chosen theme. These include:

- Playcount tracking for audio and video files.
- Blog following.
- Related tags.
- Featured tags - [Check this plugin out for initial implementation.](https://github.com/Automattic/Featured-Tags)
- Post submissions/question-and-answer system.
- Reblogging.
- Content sources.
- Related posts.
- Likes.
- Panorama post format.
- Daily post archive pages.

## Development

### Prerequisites

- Node.js and npm
- WordPress development environment, such as [Studio](https://developer.wordpress.com/studio/).

### Building the Plugin

Run the following commands to build the plugin:

```bash
npm install
npm run build
composer i --no-dev
```

### Running in Development Mode

To start the development server, use:

```bash
npm run start
composer i
```

### Creating a Plugin ZIP

To create a plugin ZIP file, use:

```bash
npm run plugin-zip
```

### Running Tests

If neccessary, a Docker setup is available for the WordPress tests, since they require MySQL, unlike Studio which uses SQLite.

After installing and starting Docker, run the following command to start the containers:
```bash
docker-compose up -d
```

Add this to your shell config (.bashrc, .zshrc):

```bash
export WP_TESTS_DIR="/tmp/wordpress-tests-lib"
```

To run the tests, follow these steps:

1. **Start Docker**: To use the Docker config in this repo fun the following in your terminal:

	```bash
	docker-compose up -d
	```

2. **Install the WordPress Test Suite**: You need to install the WordPress test suite. You can do this by running the following command in your terminal:

    ```bash
    bash bin/install-wp-tests.sh <db_name> <db_user> <db_pass> <db_host> <wp_version>
    ```

    Replace `<db_name>`, `<db_user>`, `<db_pass>`, `<db_host>`, and `<wp_version>` with your database name, database user, database password, database host, and WordPress version, respectively. If you've used the defaults it will be something like:

	```bash
	bash bin/install-wp-tests.sh wordpress_test wp_test password 127.0.0.1:3306
	```

3. **Run the Tests**: Once the test suite is installed, you can run the tests using:

    ```bash
    vendor/bin/phpunit 
    ```

4. **Troubleshooting**: If you encounter any issues, ensure that your database credentials are correct and that the database is accessible. Also, verify that the WordPress version specified is available.

### Linting & Code Standards

This project uses a combination of ESLint, Stylelint, and PHP_CodeSniffer to enforce WordPress code standards.

#### JavaScript

To lint JavaScript files, we use ESLint. You can run the linter with the following command:

```bash
npm run lint:scripts
```

To automatically fix some of the issues, you can use:

```bash
npm run lint:fix:scripts
```

#### CSS

To lint CSS files, we use Stylelint. You can run the linter with the following command:

```bash
npm run lint:styles
```

To automatically fix some of the issues, you can use:

```bash
npm run lint:fix:styles
```

#### PHP

For PHP files, we use PHP_CodeSniffer. You can run the linter with the following command:

```bash
composer run lint:php
```

To automatically fix some of the issues, you can use:

```bash
composer run format:php
```
