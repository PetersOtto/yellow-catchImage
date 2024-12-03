<?php
class YellowCatchImage
{
    const VERSION = '0.9.4';

    public $yellow;  // access to API

    // Handle initialisation
    public function onLoad($yellow)
    {
        $this->yellow = $yellow;
        $this->yellow->system->setDefault('catchImageDefaultAltText', 'Lesen Sie den Beitrag: ');
    }

    // Start with main method
    public function getCatchImage($filenametype, $baseUrl, $catchImageAltText, $catchImageFilter, $catchImageTitle)
    {
        $output = null;
        $srcNew = null;
        $srcNewInside = null;
        $srcOriginal = $this->yellow->lookup->findMediaDirectory('coreImageLocation') . $filenametype;

        // Check if »webp« should use
        $useWebp = $this->yellow->system->get('imageFilterUseWebp');

        // Get image width and height.
        list($width, $height, $type, $widthAndHeight) = getimagesize($srcOriginal);
    
        // Split filename into name and type
        $splitFilenameType = explode('.', $filenametype);
        $originalFilename = $splitFilenameType[0];
        $originalType = $splitFilenameType[1];
        $isTypeAllowed = $this->yellow->extension->get('imagefilter')->checkIfTypeIsAllowed($originalType);

        // Check if filter is given in posts header
        // Get it without »imfi-« and eliminate wrong choosed »imfi-webp«
        if (!empty($catchImageFilter)) {
            $catchImageFilter = $this->getFilter($catchImageFilter);
            if ($catchImageFilter === 'webp') {
                $catchImageFilter = '';
            }
        }

        // Get »$defaultFilter« and set it to »$choosedFilter«
        // Eliminate wrong choosed »imfi-webp« in »system.ini«
        $defaultFilter = $this->getFilter($this->yellow->system->get('imageFilterDefaultImfi'));
        if ($defaultFilter === 'webp') {
            $defaultFilter = 'original';
        }
        $choosedFilter = $defaultFilter;

        // Check if »$defaultFilter« and »$catchImageFilter« have insert »original«
        $compareDefaultCatchImageOriginal = $this->checkIfTwoOriginal($defaultFilter, $catchImageFilter);

        // Check if filter is available in extension »ImageFilter« or »ImageFilterCollection«
        $isFilterCatchAvailableExternal = $this->checkIfFilterIsAvailableExternal($catchImageFilter); 
        $isFilterCatchAvailableInternal = $this->checkIfFilterIsAvailableInternal($catchImageFilter);
        $isFilterDefaultAvailableExternal = $this->checkIfFilterIsAvailableExternal($defaultFilter);
        $isFilterDefaultAvailableInternal = $this->checkIfFilterIsAvailableInternal($defaultFilter);
        $isFilterDefaultAvailable = $this->checkIfFilterDefaultIsAvailable($defaultFilter);
        $isFilterAvailable = $this->checkIfFilterIsAvailable($isFilterCatchAvailableExternal, $isFilterCatchAvailableInternal, $isFilterDefaultAvailable); 
        
        if ($isFilterAvailable && !empty($catchImageFilter)) {                                                                
            $choosedFilter = $catchImageFilter; 
        }

        // If »defaultFilter« is not available
        if ($isFilterDefaultAvailable === false) {                                                                
            $choosedFilter = 'original'; 
            $isFilterAvailable = true;
        }

        // If filter is available or »$catchImageFilter == 'original'« set it as choosed.
        // If not -> »choosedFilter« stay »$defaultFilter«
        if ($isFilterAvailable && !empty($catchImageFilter)) {                                                                
            $choosedFilter = $catchImageFilter; 
        }
        
        // Check if »webp« should use
        if ($choosedFilter == 'original' &&  $useWebp == true && $isTypeAllowed === true ) {
            $choosedFilter = 'webp';
            $isFilterAvailable = true;
        }

        // Generate new Filename
        $newFilename = '/' . $originalFilename . '-' . $choosedFilter;

        // Use original image without »webp«
        if ((($choosedFilter == 'original' || $choosedFilter == 'webp') &&  $useWebp == false) || $isTypeAllowed === false || $compareDefaultCatchImageOriginal === true  ) {
            $choosedFilter = '';
            $newFilename = $originalFilename;
        }

        // Generate new src to image. Inside to convert image
        // If filter is available and »webp« should use
        if ($useWebp == true && $isFilterAvailable) {
            $srcNewInside = $this->yellow->lookup->findMediaDirectory('coreImageLocation') . $choosedFilter . $newFilename . '.webp';
            $srcNew = $baseUrl . $this->yellow->system->getHTML('CoreImageLocation') . $choosedFilter . $newFilename . '.webp';
        }

        // If filter is available, use without »webp« or type is not allowed (svg)
        if (($useWebp != true && ($isFilterAvailable || empty($choosedFilter))) || $isTypeAllowed == false || $compareDefaultCatchImageOriginal === true ){
            $srcNewInside = $this->yellow->lookup->findMediaDirectory('coreImageLocation') . $choosedFilter . $newFilename . '.' . $originalType;
            $srcNew = $baseUrl . $this->yellow->system->getHTML('CoreImageLocation') . $choosedFilter . $newFilename . '.' . $originalType;
        }

        // gernerate/convert new directory and image if nessesary 
        if (($isFilterAvailable || empty($choosedFilter)) || $choosedFilter == 'webp') {
            $pathNewInside = $this->yellow->lookup->findMediaDirectory('coreImageLocation') . $choosedFilter . '/';
            if (!is_dir($pathNewInside)) {
                mkdir($pathNewInside);
            }
            if ($isFilterCatchAvailableInternal || $isFilterDefaultAvailableInternal || $choosedFilter == 'webp') {
                $this->yellow->extension->get('imagefilter')->generateNewImageInternal($choosedFilter, $srcOriginal, $srcNewInside, $originalType, $isTypeAllowed);
            }

            if ($isFilterCatchAvailableExternal || $isFilterDefaultAvailableExternal) {
                $this->yellow->extension->get('imagefilter')->generateNewImageExternal($choosedFilter, $srcOriginal, $srcNewInside, $originalType, $isTypeAllowed);
            }
        }

        // Generate Output
        $output = '<img src="';
        $output .= htmlspecialchars($srcNew);
        if ($originalType === 'svg') {
            $output .= '"' . ' ' . 'alt="';
        } else{
            $output .= '"' . ' ' . $widthAndHeight . ' ' . 'alt="';
        }
        if (empty($catchImageAltText)) {
            $output .= $this->yellow->system->get('catchImageDefaultAltText') . ' ' . htmlspecialchars($catchImageTitle);
        } else {
            $output .= htmlspecialchars($catchImageAltText);
        }
        $output .= "\">\n";

        return $output;
    }

    // Compare »defaultFilter« with »$catchImageFilter«
    public function checkIfTwoOriginal($defaultFilter, $catchImageFilter)
    {
        if ($defaultFilter === 'original' && $catchImageFilter === 'original'){
            $compareDefaultCatchImageOriginal = true;
        }else{
            $compareDefaultCatchImageOriginal = false;
        }
        return $compareDefaultCatchImageOriginal;
    }

    // Check if filter is available 
    public function checkIfFilterIsAvailable($isFilterCatchAvailableExternal, $isFilterCatchAvailableInternal, $isFilterDefaultAvailable)
    {
        if ($isFilterCatchAvailableExternal || $isFilterCatchAvailableInternal || $isFilterDefaultAvailable){
            $isFilterAvailable = true;
        } else {
            $isFilterAvailable = false;
        }
        return $isFilterAvailable;
    }

    // Check if filter is available in »imagefilter.php 
    public function checkIfFilterDefaultIsAvailable($toCheckFilter)
    {
        $isFilterCatchAvailableExternal = $this->checkIfFilterIsAvailableExternal($toCheckFilter); 
        $isFilterCatchAvailableInternal = $this->checkIfFilterIsAvailableInternal($toCheckFilter);

        if (($isFilterCatchAvailableExternal || $isFilterCatchAvailableInternal)) {
            $isFilterAvailable = true;
        } else {
            $isFilterAvailable = false;
        }
        return $isFilterAvailable;
    }

    // Check if the extension »imageFillter« exist and if filter is available in »imagefilter.php«
    public function checkIfFilterIsAvailableInternal($toCheckFilter)
    {
        if ($this->yellow->extension->isExisting('imagefilter')) {
            $isFilterCatchAvailableInternal = method_exists($this->yellow->extension->get('imagefilter'), $toCheckFilter);
        } else {
            $isFilterCatchAvailableInternal = false;
        }
        return $isFilterCatchAvailableInternal;
    }
    
    // Check if the extension »imageFillterCollection« exist and if filter is available in »imagefiltercollection.php«
    public function checkIfFilterIsAvailableExternal($toCheckFilter)
    {
        if ($this->yellow->extension->isExisting('imagefiltercollection')) {
            $isFilterCatchAvailableExternal = method_exists($this->yellow->extension->get('imagefiltercollection'), $toCheckFilter);
        } else {
            $isFilterCatchAvailableExternal = false;
        }
        return $isFilterCatchAvailableExternal;
    }

    // Check if Filter contain »imfi« and return filter without »imfi«
    public function getFilter($toCheckImageFilter)
    {
        
        if ((strpos($toCheckImageFilter, 'imfi') !== false)) {
            $toCheckImageFilter = strtolower($toCheckImageFilter);
            $toCheckImageFilter = explode('-', $toCheckImageFilter);
            $toCheckImageFilter = preg_replace('/\s+/', '', $toCheckImageFilter[1]);
        } 
        return $toCheckImageFilter;
    }
}
