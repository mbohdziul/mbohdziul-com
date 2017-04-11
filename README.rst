Personal website
==================

The main goal of this little project was to create a personal website using photos uploaded to flickr.

It should be fast, easy to use and provide a simple way to sync with flickr.


Setting up site
----------------------------

- clone code from github repo

- create new database (mysql), and run code from dbase.sql file in it

- install dependencies with  `Composer`_
.. code-block:: console
    composer install

- create two config files, one from /config/prod-dist.pl -> prod.php, and second  /src/app-dist.php -> app.php

- update them with Your flickr api key and userId (prod.php) and database credentials in app.php

- get photos from Your flickr albums using flickr:fetch

.. code-block:: console

    17:22:18 mbohdziul@Mac {~/web/mb}:: php bin/console flickr:fetch
    fetch sets status: ok
    photoSets:
    >>>  Iran (total photos: 26)
    ..........................
    >>>  Cuba (total photos: 44)
    ............................................
    >>>  Bangkok, Thailand (total photos: 14)
    ..............
    >>>  Luang Prabang, Laos (total photos: 44)
    ............................................
    >>>  Angkor Wat, Cambodia (total photos: 139)
    ...........................................................................................................................................
    >>>  Kuala Lumpur, Malaysia (total photos: 15)
    ...............
    >>>  Turkey (total photos: 115)
    ...................................................................................................................
    >>>  New Delhi, India (total photos: 52)
    ....................................................
    >>>  Bornholm, Denmark (total photos: 37)
    .....................................
    >>>  Niagara Falls, Canada (total photos: 7)
    .......
    fetch completed
    cache cleared
    total execution time:: 533.39 seconds

It does not actually download any files, just getting from flickr api information's about albums/photos and putting them into database.

- point Your vhost to /web folder, or just use built-in php server
.. code-block:: console

    php -S 127.0.0.1:8000

- done


What's inside?
----------------------------

- `SilexSkeleton`_ as base
- `Guzzle`_ used for communication with flick api
- `DoctrineCache`_ for caching

.. _Composer: http://getcomposer.org/
.. _SilexSkeleton: https://github.com/silexphp/Silex-Skeleton
.. _Guzzle: https://packagist.org/packages/rebangm/silex-guzzlehttp-provider
.. _DoctrineCache: https://packagist.org/packages/sergiors/doctrine-cache-service-provider