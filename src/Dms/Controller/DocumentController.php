<?php
/**
 * github.com/buse974/Dms (https://github.com/buse974/Dms).
 *
 * Controller Document
 */
namespace Dms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Dms\Model\Dms;
use Zend\View\Model\JsonModel;
use Dms\Service\DmsService as Sp;
use Dms\Document\Document;
use Dms\Document\NoFileException;

/**
 * Class DocumentController.
 */
class DocumentController extends AbstractActionController
{
    /**
     * Print File.
     *
     * @return string
     */
    public function getAction()
    {
        try {
            $this->dms()->getService()->get($this->params('file'));
        } catch (NoFileException $e) {
            return new JsonModel(['error' => $e->getMessage()]);
        }
    }

    /**
     * Download Document.
     *
     * @return string
     */
    public function getDownloadAction()
    {
        $content = null;
        try {
            $document = $this->dms()->getService()->getDocument($this->params('file'));

            $content = $document->getDatas();
            $headers = $this->getResponse()->getHeaders();
            $headers->addHeaderLine('Content-type', 'application/octet-stream');
            $headers->addHeaderLine('Content-Transfer-Encoding', $document->getEncoding());
            $headers->addHeaderLine('Content-Length', strlen($content));
            $name = $document->getName();
            $headers->addHeaderLine('Content-Disposition', sprintf('filename=%s', ((empty($name)) ? $file.'.'.$document->getFormat() : $name)));
        } catch (NoFileException $e) {
            $content = $e->getMessage();
        }

        return $this->getResponse()->setContent($content);
    }

    /**
     * Get Info Type Document.
     *
     * @return string
     */
    public function getTypeAction()
    {
        $content = null;
        try {
            $content = $this->dms()->getService()->getInfo($this->params('file'), 'type');
        } catch (NoFileException $e) {
            $content = $e->getMessage();
        }

        return $this->getResponse()->setContent($content);
    }

    /**
     * Get Info Format Document.
     *
     * @return string
     */
    public function getFormatAction()
    {
        $content = null;
        try {
            $content = $this->dms()->getService()->getInfo($this->params('file'), 'format');
        } catch (NoFileException $e) {
            $content = $e->getMessage();
        }

        return $this->getResponse()->setContent($content);
    }

    /**
     * Get Info name Document.
     *
     * @return string
     */
    public function getNameAction()
    {
        $content = null;
        try {
            $content = $this->dms()->getService()->getInfo($this->params('file'), 'name');
        } catch (NoFileException $e) {
            $content = $e->getMessage();
        }

        return $this->getResponse()->setContent($content);
    }

    /**
     * Get Info description Document.
     *
     * @return string
     */
    public function getDescriptionAction()
    {
        $content = null;
        try {
            $content = $this->dms()->getService()->getInfo($this->params('file'), 'description');
        } catch (NoFileException $e) {
            $content = $e->getMessage();
        }

        return $this->getResponse()->setContent($content);
    }

    /**
     * Save For Upload File.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function saveAction()
    {
        foreach ($this->dms()->getHearders() as $key => $value) {
            $this->getResponse()->getHeaders()->addHeaderLine($key, $value);
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $ret = [];
        $request = $this->getRequest();
        if ($request->isPost()) {
            $files = $request->getFiles()->toArray();

            foreach ($files as $name_file => $file) {
                if (isset($file['name'])) {
                    $file = [$file];
                }
                foreach ($file as $f) {
                    $document['support'] = Document::SUPPORT_FILE_MULTI_PART_STR;
                    $document['coding'] = 'binary';
                    $document['data'] = [$name_file => $f];
                    $document['name'] = $f['name'];
                    $document['type'] = $f['type'];
                    $document['weight'] = $f['size'];

                    $doc = $this->dms()->getService()->add($document);
                    if (isset($ret[$name_file])) {
                        if (is_array($ret[$name_file])) {
                            $ret[$name_file][] = $doc;
                        } else {
                            $ret[$name_file] = [$ret[$name_file], $doc];
                        }
                    } else {
                        $ret[$name_file] = $doc;
                    }
                }
            }
        }

        return new JsonModel($ret);
    }

    /**
     * Progress Upload.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function progressAction()
    {
        foreach ($this->dms()->getHearders() as $key => $value) {
            $this->getResponse()->getHeaders()->addHeaderLine($key, $value);
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return new JsonModel(Sp::progressAction($this->params()->fromPost('uploadUID')));
    }

    /**
     * Init Session.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function initSessionAction()
    {
        foreach ($this->dms()->getHearders() as $key => $value) {
            $this->getResponse()->getHeaders()->addHeaderLine($key, $value);
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return new JsonModel(array('result' => true));
    }
}
