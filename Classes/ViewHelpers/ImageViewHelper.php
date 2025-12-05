<?php

declare(strict_types=1);

namespace Brightside\Personnel\ViewHelpers;

use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\File as FalFile;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper; // <<-- CHANGED BASE CLASS
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Core\Resource\FileInterface;

// NOTE: We no longer extend AbstractTagBasedViewHelper
class ImageViewHelper extends AbstractViewHelper
{
    /**
     * @var ResourceFactory
     */
    protected ResourceFactory $resourceFactory;

    /**
     * @var ImageService
     */
    protected ImageService $imageService;

    // We do not need $tagName anymore.

    /**
     * Inject ResourceFactory.
     */
    public function injectResourceFactory(ResourceFactory $resourceFactory): void
    {
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * Inject ImageService (TYPO3\CMS\Extbase\Service\ImageService).
     */
    public function injectImageService(ImageService $imageService): void
    {
        $this->imageService = $imageService; 
    }

    /**
     * Initialize arguments.
     * if we want them, since AbstractViewHelper doesn't handle tags.
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('src', 'string', 'a path to a file, a combined FAL identifier or an uid (int). If $treatIdAsReference is set, the integer is considered the uid of the sys_file_reference record. If you already got a FAL object, consider using the $image parameter instead');
        $this->registerArgument('treatIdAsReference', 'bool', 'given src argument is a sys_file_reference record', false, false);
        $this->registerArgument('image', 'object', 'a FAL object');
        $this->registerArgument('crop', 'string|bool', 'overrule cropping of image (setting to FALSE disables the cropping set in FileReference)');
        $this->registerArgument('cropVariant', 'string', 'select a cropping variant, in case multiple croppings have been specified or stored in FileReference', false, 'default');
        $this->registerArgument('width', 'string', 'width of the image.');
        $this->registerArgument('height', 'string', 'height of the image.');
        $this->registerArgument('minWidth', 'int', 'minimum width of the image');
        $this->registerArgument('minHeight', 'int', 'minimum width of the image');
        $this->registerArgument('maxWidth', 'int', 'minimum width of the image');
        $this->registerArgument('maxHeight', 'int', 'minimum width of the image');
        $this->registerArgument('absolute', 'bool', 'Force absolute URL', false, false);
        $this->registerArgument('alt', 'string', 'Alternative text for the image.', false, '');
        $this->registerArgument('title', 'string', 'Title text for the image.', false, '');
    }

    /**
     * Renders the image data as a vCard PHOTO property string.
     */
    public function render(): string
    {
        if ((is_null($this->arguments['src']) && is_null($this->arguments['image'])) || (!is_null($this->arguments['src']) && !is_null($this->arguments['image']))) {
            throw new Exception('You must either specify a string src or a File object.', 1382284106);
        }

        try {
            // --- 1. Get the FAL File Object ---
            /** @var FileInterface|FileReference $image */
            $image = $this->getFileObject();
            if (!$image instanceof FileInterface && !$image instanceof FileReference) {
                return '';
            }

            // --- 2. Define Cropping & Processing ---
            $cropString = $this->arguments['crop'] ?? ($image instanceof FileReference ? $image->getProperty('crop') : null);

            $cropVariantCollection = CropVariantCollection::create((string)$cropString);
            $cropVariant = $this->arguments['cropVariant'] ?: 'default';
            $cropArea = $cropVariantCollection->getCropArea($cropVariant);
            
            $cropArray = null;
            if (!$cropArea->isEmpty()) {
                $cropAreaObject = $cropArea->makeAbsoluteBasedOnFile($image);
                $cropArray = method_exists($cropAreaObject, 'toArray') ? $cropAreaObject->toArray() : null;
            }

            $processingInstructions = [
                'width' => $this->arguments['width'],
                'height' => $this->arguments['height'],
                'minWidth' => $this->arguments['minWidth'],
                'minHeight' => $this->arguments['minHeight'],
                'maxWidth' => $this->arguments['maxWidth'],
                'maxHeight' => $this->arguments['maxHeight'],
                'crop' => $cropArray, 
                'absolute' => (bool)$this->arguments['absolute'],
            ];
            
            // --- 3. Process the Image ---
            /** @var ProcessedFile $processedImage */
            $processedImage = $this->imageService->applyProcessingInstructions($image, $processingInstructions);
            
            // --- 4. Get Data and Mime Type ---
            $mimeType = $processedImage->getMimeType();
            $extension = pathinfo($processedImage->getName(), PATHINFO_EXTENSION);
            
            // vCard specification typically prefers JPEG/JPG or PNG
            $vCardMime = match ($mimeType) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                default => 'jpeg', // Fallback
            };

            $data = $processedImage->getContents();
            $base64Data = base64_encode($data);
            
            // --- 5. Return vCard PHOTO String ---
            $vCardString = 'PHOTO;' . strtoupper($vCardMime) . ';ENCODING=BASE64:' . $base64Data;
            
            return $vCardString;
            
        } catch (ResourceDoesNotExistException | \UnexpectedValueException | \RuntimeException | \InvalidArgumentException $e) {
        }
        
        return '';
    }

    /**
     * @return FileReference|FalFile|null
     * @throws Exception
     */
    protected function getFileObject(): FileReference|FalFile|null
    {
        if ($this->arguments['image'] !== null) {
            return $this->arguments['image'];
        }
        
        $src = (string)$this->arguments['src'];
        if ((bool)$this->arguments['treatIdAsReference'] === true) {
            return $this->resourceFactory->getFileReferenceObject($src);
        }

        if (is_numeric($src) && (int)$src > 0) {
            return $this->resourceFactory->getFileObject($src);
        }

        return $this->resourceFactory->retrieveFileOrUsePublicUrl($src);
    }
}