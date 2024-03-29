=== Hyperdrive ===
Contributors: 3hartanto, ariffsetiawan, auraanar, ervannur, ipututoya, joedewaweb, jhabdas, nielslange, omrobbie, shantiscript
Tags: optimize, javascript, async js, async javascript, speed
Requires at least: 3.0
Tested up to: 4.8
Stable tag: trunk
License: GPL-3.0 or later
License URI: https://opensource.org/licenses/GPL-3.0

The fastest way to load pages in WordPress.

== Description ==

Hyperdrive is a WordPress plugin giving [supporting browsers](http://caniuse.com/#search=fetch) access to a performance optimization technique known as [Fetch Injection](https://hackcabin.com/post/managing-async-dependencies-javascript/), enabling themes and sites to download resources such as JavaScript and CSS asynchronously and in parallel while preserving execution order.

Using Fetch Injection will reduce perceived latency during page load when compared with traditional methods and has been shown to increase time to initial render by as much as 200-300% for new visitors. Returning visitors are capable of even greater performance gains thanks to modern browser caching techniques such as Service Workers, commonly used in Progressive Web Applications.

== Installation ==

1. Place `hyperdrive` inside the `/wp-content/plugins/` directory
2. Activate the plugin from from the WordPress Admin dashboard

== Contributing ==

Please visit [the official repository](https://github.com/wp-id/hyperdrive) on GitHub for contributing guidelines.

== Changelog ==

### 1.0.0-beta ###

* Initial beta pre-release for testing.
