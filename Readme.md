MagentoHackathon BestsellersSorting Extension
=====================
Add your description here
Facts
-----
- version: 1.0.0
- extension key: MagentoHackathon_BestsellersSorting
- [extension on GitHub](https://github.com/magento-hackathon/bestsellers-sorting-m2)

Description
-----------
Bestsellers sorting of products in categories.

Requirements
------------
- PHP >= 5.6.0
- Mage_Core

Compatibility
-------------
- Magento >= 2.0

Installation Instructions
-------------------------
1. Install the extension via Composer.
2. Clear the cache, logout from the admin panel and then login again.
3. Activate the extension

Features
--------
1. Creates attribute for sorting products by product sales in defined periods (e.g. Spring Sale)

ToDo
----
1. Populate it based on the bestsellers reporting routine (daily, monthly, yearly etc.)
2. Create indexer for the bestseller attribute data
3. Implement configuration for the periods. Nice to have: Ability to define period on category level

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/magentohackathon/MagentoHackathon_BestsellersSorting/issues).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------


Quick Installation Guide
---------------------------------------------

    composer config repositories.bestsellers-sorting-m2 vcs git@github.com:magento-hackathon/bestsellers-sorting-m2.git
    composer require magento-hackathon/module-bestsellers-sorting dev-master
    bin/magento module:enable MagentoHackathon_BestsellersSorting
    bin/magento setup:upgrade
    

Licence
-------
[GPLv3](https://www.gnu.org/licenses/gpl-3.0.en.html)

Copyright
---------
(c) 2017 MagentoHackathon
