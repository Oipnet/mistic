<?php
/**
 * Created by PhpStorm.
 * User: arnaudp
 * Date: 29/07/17
 * Time: 15:02
 */

namespace App\Middleware;


use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Negotiation\AbstractNegotiator;
use Negotiation\Negotiator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FormatNegociatorMiddleware implements MiddlewareInterface
{
    const KEY = 'FORMAT';

    private $default = 'html';

    /**
     * @var array Available formats with the mime types
     */
    private $formats = [
        //text
        'html' => [['html', 'htm', 'php'], ['text/html', 'application/xhtml+xml']],
        'txt' => [['txt'], ['text/plain']],
        'css' => [['css'], ['text/css']],
        'json' => [['json'], ['application/json', 'text/json', 'application/x-json']],
        'jsonp' => [['jsonp'], ['text/javascript', 'application/javascript', 'application/x-javascript']],
        'js' => [['js'], ['text/javascript', 'application/javascript', 'application/x-javascript']],
        //xml
        'rdf' => [['rdf'], ['application/rdf+xml']],
        'rss' => [['rss'], ['application/rss+xml']],
        'atom' => [['atom'], ['application/atom+xml']],
        'xml' => [['xml'], ['text/xml', 'application/xml', 'application/x-xml']],
        //images
        'bmp' => [['bmp'], ['image/bmp']],
        'gif' => [['gif'], ['image/gif']],
        'png' => [['png'], ['image/png', 'image/x-png']],
        'jpg' => [['jpg', 'jpeg', 'jpe'], ['image/jpeg', 'image/jpg']],
        'svg' => [['svg', 'svgz'], ['image/svg+xml']],
        'psd' => [['psd'], ['image/vnd.adobe.photoshop']],
        'eps' => [['ai', 'eps', 'ps'], ['application/postscript']],
        'ico' => [['ico'], ['image/x-icon', 'image/vnd.microsoft.icon']],
        //audio/video
        'mov' => [['mov', 'qt'], ['video/quicktime']],
        'mp3' => [['mp3'], ['audio/mpeg']],
        'mp4' => [['mp4'], ['video/mp4']],
        'ogg' => [['ogg'], ['audio/ogg']],
        'ogv' => [['ogv'], ['video/ogg']],
        'webm' => [['webm'], ['video/webm']],
        'webp' => [['webp'], ['image/webp']],
        //fonts
        'eot' => [['eot'], ['application/vnd.ms-fontobject']],
        'otf' => [['otf'], ['font/opentype', 'application/x-font-opentype']],
        'ttf' => [['ttf'], ['font/ttf', 'application/font-ttf', 'application/x-font-ttf']],
        'woff' => [['woff'], ['font/woff', 'application/font-woff', 'application/x-font-woff']],
        'woff2' => [['woff2'], ['font/woff2', 'application/font-woff2', 'application/x-font-woff2']],
        //other formats
        'pdf' => [['pdf'], ['application/pdf', 'application/x-download']],
        'zip' => [['zip'], ['application/zip', 'application/x-zip', 'application/x-zip-compressed']],
        'rar' => [['rar'], ['application/rar', 'application/x-rar', 'application/x-rar-compressed']],
        'exe' => [['exe'], ['application/x-msdownload']],
        'msi' => [['msi'], ['application/x-msdownload']],
        'cab' => [['cab'], ['application/vnd.ms-cab-compressed']],
        'doc' => [['doc'], ['application/msword']],
        'rtf' => [['rtf'], ['application/rtf']],
        'xls' => [['xls'], ['application/vnd.ms-excel']],
        'ppt' => [['ppt'], ['application/vnd.ms-powerpoint']],
        'odt' => [['odt'], ['application/vnd.oasis.opendocument.text']],
        'ods' => [['ods'], ['application/vnd.oasis.opendocument.spreadsheet']],
    ];

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $format = $this->getFromExtension($request)?:$this->getFromHeader($request)?:$this->default;
        $contentType = $this->formats[$format][1][0].'; charset=utf-8';

        $request = $request->withAttribute(self::KEY, $format);

        $response = $delegate->process($request);

        if (!$response->hasHeader('Content-Type')) {
            $response = $response->withHeader('Content-Type', $contentType);
        }

        return $response;
    }

    private function getFromExtension(ServerRequestInterface $request) {
        $extension = strtolower(pathinfo($request->getUri()->getPath(), PATHINFO_EXTENSION));

        if (empty($extension)) {

            return;
        }

        foreach ($this->formats as $format => $data) {
            if (in_array($extension, $data[0], true)) {
                return $format;
            }
        }
    }

    private function getFromHeader($request)
    {
        $headers = call_user_func_array('array_merge', array_column($this->formats, 1));
        $mime = $this->negotiateHeader($request->getHeaderLine('Accept'), new Negotiator(), $headers);
        if ($mime !== null) {
            foreach ($this->formats as $format => $data) {
                if (in_array($mime, $data[1], true)) {
                    return $format;
                }
            }
        }
    }

    private function negotiateHeader($accept, AbstractNegotiator $negotiator, array $priorities)
    {
        if (empty($accept) || empty($priorities)) {
            return;
        }
        try {
            $best = $negotiator->getBest($accept, $priorities);
        } catch (\Exception $exception) {
            return;
        }
        if ($best) {
            return $best->getValue();
        }
    }
}