# yellow-CatchImage
Enables the use of the extension **ImageFilter** also for images on the `blog-start.html`.

Sometimes you want to show specific pictures on the `start-blog.html`. For a portfolio page, for example.
If you use my **ImageFilter** extension, you will see that the filters are not applied to these images.
To change this, I have written this extension. This extension is a plugin/add on for the extension **ImageFilter**.

## Installation
[Download extension](https://github.com/PetersOtto/yellow-catchImage.git) and copy zip file into your `system/extensions` folder. Right click if you use Safari.  
[ImageFilter](https://github.com/PetersOtto/yellow-ImageFilter) is required!

Change the code of your `start-blog.html`. Insert the following code where the images should appear: 

```
<?php 
  $baseUrl = $page->getBase($multiLanguage = false); 
  $filenametype = $page->get("Catchimage"); 
  $catchImageAltText = $page->get("catchimagealttext"); 
  $catchImageFilter = $page->get("catchimagefilter"); 
  $catchImageTitle = $page->getHtml("titleContent"); 
  echo $this->yellow->extension->get("catchimage")->getCatchImage($filenametype, $baseUrl, $catchImageAltText, $catchImageFilter, $catchImageTitle)
?>
```

With the standard stockholm theme, it could look like this:

```
More Code above
<div class="<?php echo $page->getHtml("entryClass") ?>">
<?php 
  $baseUrl = $page->getBase($multiLanguage = false);
  $filenametype = $page->get("Catchimage"); 
  $catchImageAltText = $page->get("catchimagealttext"); 
  $catchImageFilter = $page->get("catchimagefilter");
  $catchImageTitle = $page->getHtml("titleContent");
  echo $this->yellow->extension->get("catchimage")->getCatchImage($filenametype, $baseUrl, $catchImageAltText, $catchImageFilter, $catchImageTitle);
?>
</div>
More code below

```

Complete the header of your blog posts with the following lines:
```
Catchimage: your-image.jpg
Catchimagealttext: image alt text
Catchimagefilter: imfi-lowsharpen

```

With the standard stockholm theme, it could look like this:

```
---
Title: Blog example page
Published: 2020-04-07
Author: Datenstrom
Layout: blog
Tag: Example
Catchimage: photo.jpg
Catchimagealttext: Desk with computer, cell phone, coffee, notebook and pencil case.
Catchimagefilter: imfi-lowsharpen
---
```
## Developer
PetersOtto. [Get help](https://datenstrom.se/yellow/help/)

Have fun! &#129395;
