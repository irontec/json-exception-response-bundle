<?php
/**
 * This file is part of the JsonExceptionResponseBundle.
 */

namespace Irontec\JsonExceptionResponseBundle\EventListener;

use \Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpKernel\Event\ExceptionEvent;
use \Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Irontec <info@irontec.com>
 * @author ddniel16 <ddniel16>
 * @link https://github.com/irontec
 */
class JsonExceptionResponseListener
{

    /**
     * @var string
     */
    private $environment;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(string $environment, TranslatorInterface $translator)
    {
        $this->environment = $environment;
        $this->translator = $translator;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {

        if ($event->getRequest()->getMethod() === 'OPTIONS') {
            $event->setResponse(new JsonResponse(array(), Response::HTTP_NO_CONTENT));
            return;
        }

        $exception = $event->getThrowable();

        if (method_exists($exception, 'getStatusCode')) {
            $code = $this->getCode($exception->getStatusCode());
            $message = Response::$statusTexts[$code];
        } else {
            $code = $this->getCode($exception->getCode());
            $message = $this->getMessage($exception->getMessage(), $code);
        }

        $responseData = array(
            'error' => array(
                'code' => $code,
                'message' => $this->getMessage($message, $code)
            )
        );

        if ($this->environment === 'dev') {
            $traces = $exception->getTrace();
            if (is_array($traces)) {
                $responseData['error']['debug'] = $traces[0];
            }
        }

        $event->setResponse(new JsonResponse($responseData, $code));

    }

    /**
     * Comprueba si el STATUS CODE es valido, en caso contrario devuelve el STATUS CODE de "HTTP_INTERNAL_SERVER_ERROR"
     * @param int $code exception code
     * @return int code
     */
    private function getCode(int $code): int
    {

        if (array_key_exists($code, Response::$statusTexts) === false) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return $code;

    }

    /**
     * En base al exception message genera el mensaje de erro final.
     * Si no hay contenido en el mensaje devuelve el mensaje del STATUS CODE
     * El string del exception message se intenta pasar por el sistema de traducción
     * @param string $message exception message
     * @param int $code STATUS CODE
     * @return string mensaje de error
     */
    private function getMessage(?string $message, int $code): string
    {

        $message = trim($message);

        if ($message === '') {
            return Response::$statusTexts[$code];
        }

        if ($this->translator instanceof TranslatorInterface) {
            $message = $this->translator->trans($message);
        }

        return $message;

    }

}
