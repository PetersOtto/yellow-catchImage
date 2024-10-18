<?php
class YellowCatchImage
{
    const VERSION = '0.9.1';

    public $yellow;  // access to API

    // Handle initialisation
    public function onLoad($yellow)
    {
        $this->yellow = $yellow;
        $this->yellow->system->setDefault('catchImageDefaultAltText', 'Lesen Sie den Beitrag:');
    }

    public function getCatchImage($filenametype, $baseUrl, $catchImageAltText, $catchImageFilter, $catchImageTitle)
    {
        $output = null;
        $srcNew = null;
        $filterAvailableInternal = null;
        $filterAvailableExternal = null;
        $choosedFilter = null;
        $isFilterAvailableExternal = null;
        $isFilterAvailableInternal = null;
        $srcNewInside = null;
        $supportedType = null;
        $defaultFilter = strtolower($this->yellow->system->get('imageFilterDefaultImfi'));
        $defaultFilter = explode('-', $defaultFilter);
        $defaultFilter = preg_replace('/\s+/', '', $defaultFilter[1]);
        $useWebp = $this->yellow->system->get('imageFilterUseWebp');
        $splitFilenameType = explode('.', $filenametype);
        $originalFilename = $splitFilenameType[0];
        $originalType = $splitFilenameType[1];
        $srcOriginal = $this->yellow->lookup->findMediaDirectory('coreImageLocation') . $filenametype;

        if ($originalType == 'jpeg' || $originalType == 'jpg' || $originalType == 'png' || $originalType == 'webp') {
            $supportedType = '1';
        } else {
            $supportedType = '0';
        }

        if (empty($catchImageFilter)) {
            $choosedFilter = $defaultFilter;
        } else {
            $catchImageFilter = strtolower($catchImageFilter);
            if (strpos($catchImageFilter, 'imfi') !== false) {
                $catchImageFilter = explode('-', $catchImageFilter);
                $catchImageFilter = preg_replace('/\s+/', '', $catchImageFilter[1]);
                $choosedFilter = $catchImageFilter;
            } else {
                $choosedFilter = $defaultFilter;
            }
        }

        if ($choosedFilter == 'original' && $this->yellow->system->get('imageFilterUseWebp') == 1) {
            $choosedFilter = 'webp';
        }
        if ($choosedFilter == 'original' && $this->yellow->system->get('imageFilterUseWebp') == 0) {
            $choosedFilter = '';
        }

        $newFilename = $originalFilename . '-' . $choosedFilter;

        $isFilterAvailableExternal = $this->checkIfFilterIsAvailableExternal($choosedFilter);
        $isFilterAvailableInternal = $this->checkIfFilterIsAvailableInternal($choosedFilter);

        if ($this->yellow->system->get('imageFilterUseWebp') == 1 && ($isFilterAvailableExternal || $isFilterAvailableInternal)) {
            $srcNewInside = $this->yellow->lookup->findMediaDirectory('coreImageLocation') . "$choosedFilter/" . $newFilename . '.webp';
            $srcNew = $baseUrl . $this->yellow->system->getHTML('CoreImageLocation') . "$choosedFilter/" . $newFilename . '.webp';
        }

        if ($this->yellow->system->get('imageFilterUseWebp') != 1 && ($isFilterAvailableExternal || $isFilterAvailableInternal || empty($choosedFilter))) {
            $srcNewInside = $this->yellow->lookup->findMediaDirectory('coreImageLocation') . "$choosedFilter/" . $newFilename . '.' . $originalType;
            $srcNew = $baseUrl . $this->yellow->system->getHTML('CoreImageLocation') . "$choosedFilter/" . $newFilename . '.' . $originalType;
        }

        if ($supportedType == '0') {
            $newFilename = $originalFilename;
        }

        if ($supportedType == '0') {
            $srcNewInside = $this->yellow->lookup->findMediaDirectory('coreImageLocation') . $newFilename . '.' . $originalType;
            $srcNew = $baseUrl . $this->yellow->system->getHTML('CoreImageLocation') . $newFilename . '.' . $originalType;
        }

        if ($isFilterAvailableExternal || $isFilterAvailableInternal || empty($choosedFilter)) {
            $pathNewInside = $this->yellow->lookup->findMediaDirectory('coreImageLocation') . $choosedFilter . '/';
            if (!is_dir($pathNewInside)) {
                mkdir($pathNewInside);
            }

            if ($isFilterAvailableInternal && $supportedType == '1') {
                $this->yellow->extension->get('imagefilter')->generateNewImageInternal($choosedFilter, $srcOriginal, $srcNewInside, $originalType);
            }

            if ($isFilterAvailableExternal && $supportedType == '1') {
                $this->yellow->extension->get('imagefilter')->generateNewImageExternal($choosedFilter, $srcOriginal, $srcNewInside, $originalType);
            }
        }

        $output = '<img class="catchimage" src="';
        $output .= "$srcNew";
        $output .= '" alt="';
        if (empty($catchImageAltText)) {
            $output .= $this->yellow->system->get('catchImageDefaultAltText') . ' ' . $catchImageTitle;
        } else {
            $output .= "$catchImageAltText";
        }
        $output .= "\">\n";

        return $output;
    }

    public function checkIfFilterIsAvailableInternal($choosedFilter)
    {
        $isFilterAvailableInternal = method_exists($this->yellow->extension->get('imagefilter'), $choosedFilter);
        return $isFilterAvailableInternal;
    }

    public function checkIfFilterIsAvailableExternal($choosedFilter)
    {
        if ($this->yellow->extension->isExisting('imagefiltercollection')) {
            $isFilterAvailableExternal = method_exists($this->yellow->extension->get('imagefiltercollection'), $choosedFilter);
        } else {
            $isFilterAvailableExternal = false;
        }
        return $isFilterAvailableExternal;
    }
}
