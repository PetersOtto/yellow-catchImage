# yellow-CatchImage
Enables the use of the extension [ImageFilter](https://github.com/PetersOtto/yellow-ImageFilter) also for images on the `blog-start.html`.

<p align="center"><img src="screenshot-catchimage.png?raw=true" alt="Bildschirmfoto"></p>

## General
Sometimes you want to show specific pictures on the `start-blog.html`. For a portfolio page, for example.
If you use my [ImageFilter](https://github.com/PetersOtto/yellow-ImageFilter) extension, you will see that the filters are not applied to these images.
To change this, I have written this extension. This extension is a plugin/add on for the extension [ImageFilter](https://github.com/PetersOtto/yellow-ImageFilter).

I named the extension **CatchImage** because these images should catch the user and bring them to the post.

You should customize the layout file (`start-blog.html`) with css after installation. Basic css knowledge is required.



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

Now you should customize the layout file (start-blog.html) with css. For example, you could start with the following code. Put it into the `stockholm.css`. But remember, this is really just a mini example!

```
.main{
    flex-wrap: wrap;
    display: flex;
}

.entry{
    width: calc(50% - 0.4rem);
    padding: 0 0.2rem 0 0.2rem;
    
}
```

## Developer
PetersOtto. [Get help](https://datenstrom.se/yellow/help/)

Have fun! &#129395;
