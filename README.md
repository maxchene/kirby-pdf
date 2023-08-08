# Kirby PDF

This plugin allow you to choose a PDF engine to render HTML as PDF.

## WIP

For now this plugin is only working with [WkHtmlToPdf](https://wkhtmltopdf.org/) installed on your server.

More engines will be coming soon: tcpdf, dompdf, mpdf.

## Rendering

### Rendering with current template

This is the simplest render. PDF engine will parse the current page with her current template and css to render the PDF
file.

For this rendering, you might want to use [print media queries](https://developer.mozilla.org/en-US/docs/Web/CSS/@media)
and [page media query](https://developer.mozilla.org/fr/docs/Web/CSS/@page) to fit your needs.

### Dedicated pdf template

Sometimes rendering a pdf with media queries can be tricky, and sometimes you'll want a different rendering between HTML
page and PDF file for the same data.

In this case, you can configure a dedicated template for the PDF rendering using a custom css file and custom HTML tree.

To activate dedicated template, you have to create a new template with the same name as your page template in
the ``pdf``
subdirectory of your ``templates`` folder.

This is an example with a ``recipe`` page template :

```
├── snippets
├── templates
│   ├── pdf
│   │   ├── recipe.php   <- used to render the PDF file
│   ├── recipe.php       <- used to render the HTML page
```

So, in the ``templates/pdf/recipe.php``, you can use a totally different layout and css to render the PDF file.

## Configuration

To configure kirby pdf plugin, add a ``maxchene.kirbypdf``
entry in your ``site/config/config.php``

````php
'maxchene.kirbypdf' => [
    'margin' => [
        'bottom' => 10,
        'left' => 10,
        'right' => 10,
        'top' => 10,
    ],
    'orientation' => 'portrait'
]
````

Orientation can be either ``portrait`` or ``landscape``, default is portrait.

Note that margins are in millimeters (mm).

## How to use in your templates

Here is a quick example of how you can use page to PDF in your template:

```html
<h1>Guacamole recipe</h1>

<a href="<?= $page->pdfUrl();?>" target="_blank">
  Here is a link to pdf file, will open in new browser tab
</a>


<a href="<?= $page->pdfUrl();?>" download="my-pdf-filename.pdf">
  This will force PDF download with provided filename
</a>
```

## TODO

- Add more PDF engines
- Allow users to disable PDF rendering by page template
