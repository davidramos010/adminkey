<?php

namespace app\components;


use Yii;
use yii\base\InvalidConfigException;
use yii\web\Response;

class Pdf extends \kartik\mpdf\Pdf
{
    public $evenPages = false;

    public function getNumPages()
    {
        return $this->_mpdf->page;
    }

    /**
     * Generates a PDF output
     *
     * @param string $content the input HTML content
     * @param string $file the name of the file. If not specified, the document will be sent to the browser inline
     * (i.e. [[DEST_BROWSER]]).
     * @param string $dest the output destination. Defaults to [[DEST_BROWSER]].
     *
     * @return mixed
     * @throws InvalidConfigException
     */
    public function output($content = '', $file = '', $dest = self::DEST_BROWSER)
    {
        $api = $this->getApi();
        $css = $this->getCss();
        $pdfAttachments = $this->getPdfAttachments();
        if (!empty($this->methods)) {
            foreach ($this->methods as $method => $param) {
                $this->execute($method, $param);
            }
        }
        if (!empty($css)) {
            $api->WriteHTML($css, 1);
            $api->WriteHTML($content, 2);
        } else {
            $api->WriteHTML($content);
        }
        if ($pdfAttachments) {
            $api->SetImportUse();
            $api->SetHeader(null);
            $api->SetFooter(null);
            foreach ($pdfAttachments as $attachment) {
                $this->writePdfAttachment($api, $attachment);
            }
        }
        if ($this->evenPages && $api->page % 2 > 0) {
            $api->WriteHTML('<pagebreak>');
        }
        $response = Yii::$app->response;
        // For non-web response, or for file / string output, use the mPDF function as it is
        if (!($response instanceof Response) || in_array($dest, [self::DEST_FILE, self::DEST_STRING])) {
            return $api->Output($file, $dest);
        }
        /**
         * Workaround for browser & download output. Otherwise, "Headers already sent" exception will be thrown. Steps:
         * - Set the destination to string
         * - Set response headers through yii\web\Response
         */
        $output = $api->Output($file, self::DEST_STRING);
        $response->format = Response::FORMAT_RAW;
        $headers = $response->getHeaders();
        $headers->set('Content-Type', 'application/pdf');
        $headers->set('Content-Transfer-Encoding', 'binary');
        $headers->set('Cache-Control', 'public, must-revalidate, max-age=0');
        $headers->set('Pragma', 'public');
        $headers->set('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        $headers->set('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
        if (!isset($_SERVER['HTTP_ACCEPT_ENCODING']) || empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
            // do not use length if server is using compression
            $headers->set('Content-Length', strlen($output));
        }
        $type = $dest == self::DEST_BROWSER ? 'inline; ' : 'attachment; ';
        $headers->set('Content-Disposition', $type . 'filename="' . $file . '"');
        return $output;
    }


}