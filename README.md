# symfony-kmeans

Ubuntu
======================

on root folder run the following commands:
* `rm -rf bin`
* `rm -rf vendor`
* `composer install` if composer not installed run `php composer.phar install`

First, make sure you install Node.js and also the Yarn package manager. The following instructions depend on whether you are installing Encore in a Symfony application or not.
* `yarn install`

To build the assets, run:
compile assets once
* `yarn encore dev`
if you prefer npm, run:
* `npm run dev`

or, recompile assets automatically when files change
* `yarn encore dev --watch`
if you prefer npm, run:
* `npm run watch`

on deploy, create a production build
* `yarn encore production`
if you prefer npm, run:
* `npm run build`


* to start local server `sudo symfony server:start`
