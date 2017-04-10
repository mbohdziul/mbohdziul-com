<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\HttpFoundation\Request;

$console = new Application('mb, personal website', 'n/a');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);

/**
 * get photos from flickr & save them to db
 */
$console->register('flickr:fetch')
    ->setDefinition(
        array())
    ->setDescription('Fetch all photos from Flickr and save they url\'s into db')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $time_start = microtime(true);

        // clear db
        $app['db']->query('TRUNCATE TABLE flickr__photo_set');
        $app['db']->query('TRUNCATE TABLE flickr__photo');

        // size of pictures to fetch
        // https://www.flickr.com/services/api/flickr.photos.getSizes.html
        $photoSize = 8; // size 8 -> large
        $photoSizeThumb = 4; // size 4 -> small, 320x240

        // fetch sets
        $set = $photoSets = flickrRestRequest($app, 'flickr.photosets.getList');
        if ($set['stat'] == 'ok') {
            $output->writeln('fetch sets status: ok');
            $output->writeln('photoSets:');
            foreach ($photoSets['photosets']['photoset'] as $key => $set) {
                //save each set to db
                $app['db']->insert('flickr__photo_set', array(
                    'title' => $set['title']['_content']
                ));
                $setId = $app['db']->lastInsertId();
                $output->writeln('>>>  ' . $set['title']['_content'] . ' (total photos: ' . $set['photos'] . ')');

                // fetch all photos from each set
                $photosInOneSet = flickrRestRequest($app, 'flickr.photosets.getPhotos', [
                    'photoset_id' => $set['id'],
//                    'page' => '1',
//                    'per_page' => '5'
                ])['photoset'];

                // get details of each photo
                foreach ($photosInOneSet['photo'] as $key2 => $photo) {

                    $photoDetails = flickrRestRequest($app, 'flickr.photos.getSizes', [
                        'photo_id' => $photo['id'],
                    ])['sizes'];

                    //save each photo to db
                    $app['db']->insert('flickr__photo', array(
                        'photo_set_id' => $setId,
                        'thumb_url' => $photoDetails['size'][$photoSizeThumb]['source'],
                        'thumb_width' => $photoDetails['size'][$photoSizeThumb]['width'],
                        'thumb_height' => $photoDetails['size'][$photoSizeThumb]['height'],
                        'photo_url' => $photoDetails['size'][$photoSize]['source'],
                        'photo_width' => $photoDetails['size'][$photoSize]['width'],
                        'photo_height' => $photoDetails['size'][$photoSize]['height'],
                        'title' => $photo['title'],
                        'description' => $photo['description']
                    ));
                    $output->write('.');
                }
                $output->writeln('');
            }
            $output->writeln('fetch completed');

            // clear cache
            $app['cache']->delete('photoSets');
            $app['cache']->delete('photos');
            $output->writeln('cache cleared');
        } else {
            $output->writeln('fetch sets status: ' . $set['stat']);
        }

        $time_end = microtime(true);
        $output->writeln('total execution time:: ' . number_format($time_end - $time_start, 2) . ' seconds');
    });


function flickrRestRequest(Silex\Application $app, $method, $additionalParams = array())
{
    $request = Request::create('/');
    $app->handle($request);
    $myRequest = $app['guzzle']->get('/services/rest', [
        'query' => array_merge([
            'method' => $method,
            'api_key' => $app['flickr.api_key'],
            'user_id' => $app['flickr.user_id'],
            'format' => 'php_serial'
        ], $additionalParams)
    ]);

    if ($myRequest->getStatusCode() == '200') {
        return unserialize($myRequest->getBody()->getContents());
    } else {
        return null;
    }
}

return $console;
