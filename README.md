Symfony2 Extension [deprecated]
===============================

[![Build Status](https://travis-ci.org/phpspec/Symfony2Extension.png?branch=master)](https://travis-ci.org/phpspec/Symfony2Extension)

Symfony2 extension for PhpSpec. The extension is pretty much dead. Since we aim on writing code decoupled from the framework, PhpSpec works very well without a special extension for Symfony.

There were only two features provided by this extension - stubbing the container and generating specs in a bundle.

Stubbing the container is a bad practice. The only place we might need to access the container is a controller. It's prefered to  register the controller as a service, so stubbing it is not needed.

Placing specs in a bundle is not a recommended way either. It's cleaner to have all specs in a single folder. 
