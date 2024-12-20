# yellow-CatchImage
Enable the use of [ImageFilter](https://github.com/PetersOtto/yellow-ImageFilter) also for images on the `blog-start.html`.

<p align="center"><img src="screenshot-catchimage.png?raw=true" alt="Bildschirmfoto"></p>

## General

The extension is named »CatchImage« because these images should catch the user and bring them to the post.

Datenstrom Yellow shows the title of the post and a small excerpt of the post on the start page of the blog »start-blog.html«.
If you want to create a portfolio page, you often need an image on the start page of the blog »start-blog.html«.
This can be implemented with »CatchImage«. In addition, the full range of functions of »ImageFilter« can be used. 

[ImageFilter](https://github.com/PetersOtto/yellow-ImageFilter) is required.

### How to use

You should customize the layout file `start-blog.html` with css after installation. Basic css knowledge is required.  
In the easiest case change the code of your `start-blog.html` like the following example. Insert the following code where the images should appear: 

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

Have a look to »ImageFilter«
* https://github.com/PetersOtto/yellow-ImageFilter

## Developer
PetersOtto. [Get help](https://datenstrom.se/yellow/help/)

Have fun! &#129395;
