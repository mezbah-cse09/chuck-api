<?php

/**
 * JokesController.php - created Mar 6, 2016 3:03:18 PM
 *
 * @copyright Copyright (c) pinkbigmacmedia
 *
 */
namespace Chuck\App\Api\Controller;

/**
 *
 * JokesController
 *
 * @package Chuck\App\Api
 *
 */
class JokesController
{
    use \Chuck\Util\LoggerTrait;

    /**
     *
     * @var \Chuck\JokeFacade
     */
    protected $jokeFacade;

    /**
     *
     * @param  \Silex\Application $app
     * @param  string $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(
        \Silex\Application $app,
        \Symfony\Component\HttpFoundation\Request $request,
        $id
    ) {
        $jokeWindow = $app['chuck.joke']->window($id);

        return $app['twig']->render('joke.html', [
            'page_title'    => trim(substr($jokeWindow->getCurrent()->getValue(), 0, 120), '.') . ' ...',
            'current_joke'  => [
                'id'    => $jokeWindow->getCurrent()->getId(),
                'title' => trim(substr($jokeWindow->getCurrent()->getValue(), 0, 120), '.') . ' ...',
                'url'   => $app['url_generator']->generate('api.get_joke', ['id' => $id]),
                'value' => $jokeWindow->getCurrent()->getValue()
            ],
            'next_joke'     => [
                'id'    => $next = $jokeWindow->getNext()->getId(),
                'title' => trim(substr($jokeWindow->getNext()->getValue(), 0, 120), '.') . ' ...',
                'url'   => $app['url_generator']->generate('api.get_joke', ['id' => $next]),
                'value' => $jokeWindow->getNext()->getValue()
            ],
            'previous_joke' => [
                'id'    => $prev = $jokeWindow->getPrevious()->getId(),
                'title' => trim(substr($jokeWindow->getPrevious()->getValue(), 0, 120), '.') . ' ...',
                'url'   => $app['url_generator']->generate('api.get_joke', ['id' => $prev]),
                'value' => $jokeWindow->getPrevious()->getValue()
            ]
        ]);
    }

    /**
     *
     * @param  \Silex\Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function randomAction(
        \Silex\Application $app,
        \Symfony\Component\HttpFoundation\Request $request
    ) {
        $joke = $app['chuck.joke']->random();

        return new \Symfony\Component\HttpFoundation\JsonResponse(
            [
                'icon_url' => 'https://api.chucknorris.io/img/avatar/chuck-norris.png',
                'id'       => $joke->getId(),
                'value'    => $joke->getValue()
            ],
            200,
            [
                'Access-Control-Allow-Origin'      => '*',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Methods'     => 'GET, HEAD',
                'Access-Control-Allow-Headers'     => 'Content-Type, Accept, X-Requested-With'
            ]
        );
    }
}
